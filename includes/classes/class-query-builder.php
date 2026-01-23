<?php

namespace GatherPress\References;

defined( 'ABSPATH' ) || exit;

/**
 * Query Builder
 *
 * Constructs WP_Query arguments based on filters and configuration.
 *
 * @since 0.1.0
 */
class Query_Builder {
	/**
	 * Config manager instance
	 *
	 * @var Config_Manager
	 */
	private Config_Manager $config_manager;

	/**
	 * Constructor
	 *
	 * @since 0.1.0
	 * @param Config_Manager $config_manager Config manager instance.
	 */
	public function __construct( Config_Manager $config_manager ) {
		$this->config_manager = $config_manager;
	}

	/**
	 * Build query arguments
	 *
	 * @since 0.1.0
	 * @param string $post_type   Post type slug.
	 * @param int    $ref_term_id Reference term ID.
	 * @param int    $year        Year filter.
	 * @param string $type        Reference type filter.
	 * @return array<string, mixed> WP_Query arguments.
	 */
	public function build_args( string $post_type, int $ref_term_id, int $year, string $type ): array {
		$config = $this->config_manager->get_config( $post_type );
		
		if ( ! $config ) {
			return array();
		}
		
		$args = $this->get_base_args( $post_type );
		
		// Add taxonomy filters.
		$tax_query = $this->build_tax_query( $config, $ref_term_id, $type );
		if ( ! empty( $tax_query ) ) {
			$args['tax_query'] = $tax_query;
		}
		
		// Add year filter.
		if ( $year > 0 ) {
			$args['date_query'] = array(
				array( 'year' => $year ),
			);
		}
		
		return apply_filters( 'gatherpress_references_query_args', $args, $post_type, $ref_term_id, $year, $type );
	}

	/**
	 * Get base query arguments
	 *
	 * @since 0.1.0
	 * @param string $post_type Post type slug.
	 * @return array<string, mixed> Base arguments.
	 */
	private function get_base_args( string $post_type ): array {
		$args = array(
			'post_type'              => $post_type,
			'posts_per_page'         => 9999,
			'post_status'            => 'publish',
			'orderby'                => 'date',
			'order'                  => 'DESC',
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => true,
		);
		
		if ( $post_type === 'gatherpress_event' ) {
			$args['gatherpress_event_query'] = 'past';
		}
		
		return $args;
	}

	/**
	 * Build taxonomy query
	 *
	 * @since 0.1.0
	 * @param array{ref_tax: string, ref_types: array<int, string>} $config     Configuration.
	 * @param int                                                   $ref_term_id Reference term ID.
	 * @param string                                                $type        Type filter.
	 * @return array<string|int, mixed> Tax query.
	 */
	private function build_tax_query( array $config, int $ref_term_id, string $type ): array {
		$tax_query = array();
		
		if ( $ref_term_id > 0 && ! empty( $config['ref_tax'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $config['ref_tax'],
				'field'    => 'term_id',
				'terms'    => $ref_term_id,
			);
		}
		
		if ( $type !== 'all' ) {
			$type_query = $this->build_type_query( $config, $type );
			if ( ! empty( $type_query ) ) {
				$tax_query[] = $type_query;
			}
		}
		
		if ( count( $tax_query ) > 1 ) {
			$tax_query = array_merge( array( 'relation' => 'AND' ), $tax_query );
		}
		
		return $tax_query;
	}

	/**
	 * Build type query
	 *
	 * @since 0.1.0
	 * @param array{ref_tax: string, ref_types: array<int, string>} $config Configuration.
	 * @param string                                                $type   Type filter.
	 * @return array<string|int, mixed> Type query.
	 */
	private function build_type_query( array $config, string $type ): array {
		if ( ! in_array( $type, $config['ref_types'], true ) ) {
			return array();
		}
		
		if ( count( $config['ref_types'] ) === 1 ) {
			return array(
				'taxonomy' => $type,
				'operator' => 'EXISTS',
			);
		}
		
		$type_query = array( 'relation' => 'OR' );
		foreach ( $config['ref_types'] as $taxonomy ) {
			$type_query[] = array(
				'taxonomy' => $taxonomy,
				'operator' => 'EXISTS',
			);
		}
		
		return $type_query;
	}
}