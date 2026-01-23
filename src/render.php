<?php
/**
 * GatherPress References Block - Frontend Renderer
 *
 * This file handles the server-side rendering of the GatherPress References block.
 * It integrates with the main plugin's singleton architecture for data retrieval
 * and rendering.
 *
 * @package GatherPress_References
 * @since 0.1.0
 */

namespace GatherPress\References;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if( ! class_exists( Block_Renderer::class ) ) {
	/**
	 * Block Renderer
	 *
	 * Singleton class responsible for rendering the references block on the frontend.
	 * Handles attribute extraction, data retrieval, and HTML generation.
	 *
	 * @since 0.1.0
	 */
	class Block_Renderer {
		/**
		 * Singleton instance
		 *
		 * @var Block_Renderer|null
		 */
		private static ?Block_Renderer $instance = null;

		/**
		 * Config manager instance
		 *
		 * @var Config_Manager
		 */
		private Config_Manager $config_manager;

		/**
		 * Cache manager instance
		 *
		 * @var Cache_Manager
		 */
		private Cache_Manager $cache_manager;

		/**
		 * Query builder instance
		 *
		 * @var Query_Builder
		 */
		private Query_Builder $query_builder;

		/**
		 * Data organizer instance
		 *
		 * @var Data_Organizer
		 */
		private Data_Organizer $data_organizer;

		/**
		 * Constructor
		 *
		 * @since 0.1.0
		 */
		private function __construct() {
			$plugin = Plugin::get_instance();
			
			$this->config_manager  = new Config_Manager();
			$this->cache_manager   = $plugin->get_cache_manager();
			$this->query_builder   = $plugin->get_query_builder();
			$this->data_organizer  = $plugin->get_data_organizer();
		}

		/**
		 * Get singleton instance
		 *
		 * @since 0.1.0
		 * @return Block_Renderer Instance.
		 */
		public static function get_instance(): Block_Renderer {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Render the block
		 *
		 * @since 0.1.0
		 * @param array<string, mixed> $attributes Block attributes.
		 * @param string              $content    Block content.
		 * @param \WP_Block            $block      Block instance.
		 * @return string Rendered block HTML.
		 */
		public function render( array $attributes, string $content, \WP_Block $block ): string {
			// Extract and sanitize attributes.
			$render_data = $this->prepare_render_data( $attributes );
			
			if ( ! $render_data ) {
				return '';
			}
			
			// Get references data.
			$references = $this->get_references_data(
				$render_data['post_type'],
				$render_data['ref_term_id'],
				$render_data['year'],
				$render_data['type']
			);
			
			if ( empty( $references ) ) {
				return '';
			}
			
			// Sort years.
			$references = $this->data_organizer->sort_years( $references, $render_data['year_sort'] );
			
			// Generate HTML.
			return $this->generate_html(
				$references,
				$render_data['type'],
				$render_data['type_labels'],
				$render_data['heading_level'],
				$render_data['secondary_heading_level'],
				$block
			);
		}

		/**
		 * Prepare render data from attributes
		 *
		 * @since 0.1.0
		 * @param array<string, mixed> $attributes Block attributes.
		 * @return ?array{post_type: string, ref_term_id: int, year: int, type: string, heading_level: int, secondary_heading_level: int, year_sort: string, type_labels: array<string, string>}
		 */
		private function prepare_render_data( array $attributes ): ?array {
			// Extract attributes.
			$post_type      = isset( $attributes['postType'] ) ? sanitize_text_field( $attributes['postType'] ) : '';
			$ref_term_id    = isset( $attributes['refTermId'] ) ? intval( $attributes['refTermId'] ) : 0;
			$year           = isset( $attributes['year'] ) ? intval( $attributes['year'] ) : 0;
			$type           = isset( $attributes['referenceType'] ) ? sanitize_text_field( $attributes['referenceType'] ) : 'all';
			$heading_level  = isset( $attributes['headingLevel'] ) ? intval( $attributes['headingLevel'] ) : 2;
			$year_sort      = isset( $attributes['yearSortOrder'] ) ? sanitize_text_field( $attributes['yearSortOrder'] ) : 'desc';
			
			// Validate post type.
			if ( empty( $post_type ) || ! post_type_supports( $post_type, 'gatherpress_references' ) ) {
				return null;
			}
			
			// Get configuration.
			$config = $this->config_manager->get_config( $post_type );
			if ( ! $config ) {
				return null;
			}
			
			// Normalize heading levels.
			$heading_level = max( 1, min( 6, $heading_level ) );
			$secondary_heading_level = min( $heading_level + 1, 6 );
			
			// Validate sort order.
			if ( ! in_array( $year_sort, array( 'asc', 'desc' ), true ) ) {
				$year_sort = 'desc';
			}
			
			// Auto-detect reference term from archive.
			if ( $ref_term_id === 0 && is_tax( $config['ref_tax'] ) ) {
				$term = get_queried_object();
				if ( $term instanceof \WP_Term ) {
					$ref_term_id = $term->term_id;
				}
			}
			
			// Get type labels.
			$type_labels = $this->get_type_labels( $config['ref_types'] );
			
			return array(
				'post_type'               => $post_type,
				'ref_term_id'             => $ref_term_id,
				'year'                    => $year,
				'type'                    => $type,
				'heading_level'           => $heading_level,
				'secondary_heading_level' => $secondary_heading_level,
				'year_sort'               => $year_sort,
				'type_labels'             => $type_labels,
			);
		}

		/**
		 * Get type labels for taxonomies
		 *
		 * @since 0.1.0
		 * @param array<int, string> $taxonomies Taxonomy slugs.
		 * @return array<string, string> Type labels.
		 */
		private function get_type_labels( array $taxonomies ): array {
			$type_labels = array();
			
			foreach ( $taxonomies as $taxonomy_slug ) {
				$taxonomy = get_taxonomy( $taxonomy_slug );
				if ( $taxonomy ) {
					$type_labels[ $taxonomy_slug ] = $taxonomy->labels->name ?? $taxonomy->name;
				}
			}
			
			/**
			 * Filter the type labels displayed in headings.
			 *
			 * @since 0.1.0
			 * @param array<string, string> $labels Array of taxonomy slug => label pairs.
			 */
			return apply_filters( 'gatherpress_references_type_labels', $type_labels );
		}

		/**
		 * Get references data (from cache or query)
		 *
		 * @since 0.1.0
		 * @param string $post_type   Post type slug.
		 * @param int    $ref_term_id Reference term ID.
		 * @param int    $year        Year filter.
		 * @param string $type        Type filter.
		 * @return array<string, array<string, array<int, string>>> References data.
		 */
		private function get_references_data( string $post_type, int $ref_term_id, int $year, string $type ): array {
			// Try cache first.
			$cache_key  = $this->cache_manager->get_cache_key( $post_type, $ref_term_id, $year, $type );
			$references = $this->cache_manager->get( $cache_key );
			
			if ( false !== $references ) {
				return $references;
			}
			
			// Build and execute query.
			$args  = $this->query_builder->build_args( $post_type, $ref_term_id, $year, $type );
			$query = new \WP_Query( $args );
			
			// Organize results.
			$references = $this->data_organizer->organize_results( $post_type, $query, $type );
			
			// Cache if we have data.
			if ( ! empty( $references ) ) {
				$this->cache_manager->set( $cache_key, $references );
			}
			
			return $references;
		}

		/**
		 * Generate HTML output
		 *
		 * @since 0.1.0
		 * @param array<string, array<string, array<int, string>>> $references            References data.
		 * @param string                                           $type                  Type filter.
		 * @param array<string, string>                            $type_labels           Type labels.
		 * @param int                                              $heading_level         Primary heading level.
		 * @param int                                              $secondary_heading_level Secondary heading level.
		 * @param \WP_Block                                         $block                 Block instance.
		 * @return string HTML output.
		 */
		private function generate_html(
			array $references,
			string $type,
			array $type_labels,
			int $heading_level,
			int $secondary_heading_level,
			\WP_Block $block
		): string {
			$is_specific_type = ( $type !== 'all' );
			
			ob_start();
			?>
			<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() is escaped internally. ?>>
				<?php foreach ( $references as $ref_year => $types ) { ?>
					<h<?php echo esc_attr( (string) $heading_level ); ?> class="wp-block-heading references-year"><?php echo esc_html( $ref_year ); ?></h<?php echo esc_attr( (string) $heading_level ); ?>>
					
					<?php foreach ( $types as $ref_type => $items ) { ?>
						<?php if ( ( $type === $ref_type || ! $is_specific_type ) && ! empty( $items ) ) { ?>
							<?php if ( ! $is_specific_type ) { ?>
								<h<?php echo esc_attr( (string) $secondary_heading_level ); ?> class="wp-block-heading references-type"><?php echo esc_html( $type_labels[ $ref_type ] ); ?></h<?php echo esc_attr( (string) $secondary_heading_level ); ?>>
							<?php } ?>
							
							<ul class="wp-block-list references-list">
								<?php foreach ( $items as $item ) { ?>
									<li><?php echo esc_html( $item ); ?></li>
								<?php } ?>
							</ul>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</div>
			<?php
			return ob_get_clean();
		}
	}
}
// Initialize renderer and handle the render callback.
$renderer = Block_Renderer::get_instance();
return $renderer->render( $attributes, $content, $block );