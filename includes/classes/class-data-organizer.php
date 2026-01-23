<?php

namespace GatherPress\References;

defined( 'ABSPATH' ) || exit;

/**
 * Data Organizer
 *
 * Handles organization of query results into structured reference data.
 *
 * @since 0.1.0
 */
class Data_Organizer {
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
	 * Organize query results
	 *
	 * @since 0.1.0
	 * @param string    $post_type Post type slug.
	 * @param \WP_Query $query     Query object.
	 * @param string    $type      Type filter.
	 * @return array<string, array<string, array<int, string>>> Organized references.
	 */
	public function organize_results( string $post_type, \WP_Query $query, string $type ): array {
		if ( empty( $query->posts ) ) {
			return array();
		}
		
		$config = $this->config_manager->get_config( $post_type );
		
		if ( ! $config ) {
			return array();
		}
		
		/** @var array<int, int> $post_ids */
		$post_ids = $query->posts;
		
		$post_dates = $this->get_post_dates( $post_type, $post_ids );
		$post_terms = $this->get_post_terms( $post_ids, $config['ref_types'] );
		
		$references = $this->group_by_year( $post_ids, $post_dates, $post_terms, $config['ref_types'], $type );
		
		return $references;
	}

	/**
	 * Get post dates
	 *
	 * @since 0.1.0
	 * @param string          $post_type Post type slug.
	 * @param array<int, int> $post_ids  Post IDs.
	 * @return array<int, object{post_id: string, year: string}> Post dates.
	 */
	private function get_post_dates( string $post_type, array $post_ids ): array {
		global $wpdb;
		
		if ( empty( $post_ids ) ) {
			return array();
		}
		
		$safe_ids     = array_map( 'intval', $post_ids );
		$placeholders = implode( ',', array_fill( 0, count( $safe_ids ), '%d' ) );
		
		if ( $post_type === 'gatherpress_event' ) {
			$table = $wpdb->prefix . 'gatherpress_events';
			/** @var literal-string $sql */
			$sql = "SELECT post_id, YEAR(datetime_start_gmt) AS year
					FROM {$table}
					WHERE post_id IN ({$placeholders})
					ORDER BY datetime_start_gmt DESC";
		} else {
			/** @var literal-string $sql */
			$sql = "SELECT ID as post_id, YEAR(post_date) AS year
					FROM {$wpdb->posts}
					WHERE ID IN ({$placeholders})
					ORDER BY post_date DESC";
		}
		
		$results = $wpdb->get_results(
			$wpdb->prepare( $sql, ...$safe_ids ),
			OBJECT_K
		);
		
		if ( null === $results || ! is_array( $results ) ) {
			return array();
		}
		
		/** @var array<int, object{post_id: string, year: string}> $results */
		return $results;
	}

	/**
	 * Get post terms
	 *
	 * @since 0.1.0
	 * @param array<int, int>    $post_ids   Post IDs.
	 * @param array<int, string> $taxonomies Taxonomies.
	 * @return array<int, ?array<string, array<\WP_Term>>> Post terms.
	 */
	private function get_post_terms( array $post_ids, array $taxonomies ): array {
		if ( empty( $post_ids ) || empty( $taxonomies ) ) {
			return array();
		}
		
		$organized_terms = array();
		
		foreach ( $post_ids as $post_id ) {
			$organized_terms[ $post_id ] = array();
			
			foreach ( $taxonomies as $taxonomy ) {
				$terms = get_the_terms( $post_id, $taxonomy );
				
				if ( $terms && ! is_wp_error( $terms ) ) {
					$organized_terms[ $post_id ][ $taxonomy ] = $terms;
				}
			}
		}
		
		return $organized_terms;
	}

	/**
	 * Group terms by year
	 *
	 * @since 0.1.0
	 * @param array<int, int>                                   $post_ids   Post IDs.
	 * @param array<int, object{post_id: string, year: string}> $post_dates Post dates.
	 * @param array<int, ?array<string, array<\WP_Term>>>       $post_terms Post terms.
	 * @param array<int, string>                                $taxonomies Taxonomies.
	 * @param string                                            $type_filter Type filter.
	 * @return array<string, array<string, array<int, string>>> Grouped references.
	 */
	private function group_by_year( array $post_ids, array $post_dates, array $post_terms, array $taxonomies, string $type_filter ): array {
		$references = array();
		
		foreach ( $post_ids as $post_id ) {
			if ( ! isset( $post_dates[ $post_id ] ) ) {
				continue;
			}
			
			$year  = $post_dates[ $post_id ]->year;
			$terms = isset( $post_terms[ $post_id ] ) ? $post_terms[ $post_id ] : array();
			
			if ( ! isset( $references[ $year ] ) ) {
				$references[ $year ] = $this->init_year_structure( $taxonomies );
			}
			
			$this->add_terms_to_year( $references[ $year ], $terms, $taxonomies );
		}
		
		$references = $this->sort_term_names( $references );
		$references = $this->remove_empty_arrays( $references, $type_filter );
		
		return $references;
	}

	/**
	 * Initialize year structure
	 *
	 * @since 0.1.0
	 * @param array<int, string> $taxonomies Taxonomies.
	 * @return array<string, array<int, string>> Year structure.
	 */
	private function init_year_structure( array $taxonomies ): array {
		$structure = array();
		foreach ( $taxonomies as $taxonomy ) {
			$structure[ $taxonomy ] = array();
		}
		return $structure;
	}

	/**
	 * Add terms to year
	 *
	 * @since 0.1.0
	 * @param array<string, array<int, string>>       $year_data  Year data.
	 * @param ?array<string, array<\WP_Term>>         $terms      Terms.
	 * @param array<int, string>                      $taxonomies Taxonomies.
	 * @return void
	 */
	private function add_terms_to_year( array &$year_data, ?array $terms, array $taxonomies ): void {
		if ( empty( $terms ) ) {
			return;
		}
		
		foreach ( $taxonomies as $taxonomy ) {
			if ( ! isset( $terms[ $taxonomy ] ) ) {
				continue;
			}
			
			foreach ( $terms[ $taxonomy ] as $term ) {
				if ( ! in_array( $term->name, $year_data[ $taxonomy ], true ) ) {
					$year_data[ $taxonomy ][] = $term->name;
				}
			}
		}
	}

	/**
	 * Sort term names
	 *
	 * @since 0.1.0
	 * @param array<string, array<string, array<int, string>>> $references References.
	 * @return array<string, array<string, array<int, string>>> Sorted references.
	 */
	private function sort_term_names( array $references ): array {
		foreach ( $references as $year => $year_data ) {
			foreach ( $year_data as $taxonomy => $items ) {
				natcasesort( $references[ $year ][ $taxonomy ] );
				$references[ $year ][ $taxonomy ] = array_values( $references[ $year ][ $taxonomy ] );
			}
		}
		return $references;
	}

	/**
	 * Remove empty arrays
	 *
	 * @since 0.1.0
	 * @param array<string, array<string, array<int, string>>> $references  References.
	 * @param string                                           $type_filter Type filter.
	 * @return array<string, array<string, array<int, string>>> Cleaned references.
	 */
	private function remove_empty_arrays( array $references, string $type_filter ): array {
		foreach ( $references as $year => $year_data ) {
			if ( $type_filter !== 'all' ) {
				if ( ! isset( $year_data[ $type_filter ] ) || empty( $year_data[ $type_filter ] ) ) {
					unset( $references[ $year ] );
					continue;
				}
				$references[ $year ] = array(
					$type_filter => $year_data[ $type_filter ],
				);
			} else {
				foreach ( $year_data as $taxonomy => $items ) {
					if ( empty( $items ) ) {
						unset( $references[ $year ][ $taxonomy ] );
					}
				}
				
				if ( empty( $references[ $year ] ) ) {
					unset( $references[ $year ] );
				}
			}
		}
		
		return $references;
	}

	/**
	 * Sort years
	 *
	 * @since 0.1.0
	 * @param array<string, array<string, array<int, string>>> $references  References.
	 * @param string                                           $sort_order  Sort order.
	 * @return array<string, array<string, array<int, string>>> Sorted references.
	 */
	public function sort_years( array $references, string $sort_order ): array {
		if ( empty( $references ) ) {
			return $references;
		}
		
		$years = array_keys( $references );
		
		if ( $sort_order === 'asc' ) {
			sort( $years, SORT_NUMERIC );
		} else {
			rsort( $years, SORT_NUMERIC );
		}
		
		$sorted = array();
		foreach ( $years as $year ) {
			$sorted[ (string) $year ] = $references[ (string) $year ];
		}
		
		return $sorted;
	}
}