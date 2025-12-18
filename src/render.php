<?php
/**
 * GatherPress References Block - Frontend Renderer
 *
 * This file handles the server-side rendering of the GatherPress References block
 * for GatherPress events. It queries GatherPress events, organizes references
 * by year and type, and outputs structured HTML.
 *
 * @package GatherPress_References
 * @since 0.1.0
 */

if ( ! class_exists( 'GatherPress_References_Renderer' ) ) {
	/**
	 * GatherPress References Renderer
	 *
	 * Handles data retrieval, caching, and organization for block rendering.
	 * Optimized for performance with transient caching and efficient queries.
	 * Works with GatherPress events (gatherpress_event post type).
	 *
	 * @since 0.1.0
	 */
	class GatherPress_References_Renderer {
		/**
		 * Cache key prefix
		 *
		 * @var string
		 */
		private string $cache_prefix = 'gatherpress_refs_';

		/**
		 * Cache expiration in seconds (1 hour)
		 *
		 * @var int
		 */
		private int $cache_expiration = 3600;

		/**
		 * Constructor
		 *
		 * Applies filterable properties on instantiation.
		 *
		 * @since 0.1.0
		 */
		public function __construct() {
			$this->apply_filters();
		}

		/**
		 * Apply filterable properties
		 *
		 * Allows developers to modify class properties via filters.
		 *
		 * @since 0.1.0
		 */
		private function apply_filters(): void {
			/**
			 * Filter the cache expiration time in seconds.
			 *
			 * Allows modification of how long reference data is cached.
			 * Default is 3600 seconds (1 hour).
			 *
			 * @since 0.1.0
			 *
			 * @param int $cache_expiration Cache expiration time in seconds.
			 *
			 * @example
			 * // Increase cache to 2 hours
			 * add_filter( 'gatherpress_references_cache_expiration', function( $seconds ) {
			 *     return 7200;
			 * } );
			 *
			 * @example
			 * // Disable caching (use 0)
			 * add_filter( 'gatherpress_references_cache_expiration', '__return_zero' );
			 */
			$this->cache_expiration = apply_filters( 'gatherpress_references_cache_expiration', $this->cache_expiration );
		}

		/**
		 * Get references organized by year and type
		 *
		 * Retrieves all matching GatherPress events and organizes their taxonomy terms
		 * by year and reference type. Results are cached for performance.
		 *
		 * Example return structure:
		 * array(
		 *     '2024' => array(
		 *         '_gatherpress-client' => array( 'Royal Theater London', 'Vienna Burgtheater' ),
		 *         '_gatherpress-festival' => array( 'Edinburgh Festival' ),
		 *         '_gatherpress-award' => array( 'Best Director Award' )
		 *     ),
		 *     '2023' => array(...)
		 * )
		 *
		 * @since 0.1.0
		 * @param int    $production_id Optional. Filter by production term ID. Default 0 (all).
		 * @param string $year          Optional. Filter by specific year (e.g., '2024'). Default '' (all).
		 * @param string $type          Optional. Filter by reference type or 'all'. Default 'all'.
		 * @return array Nested array of references organized by year and type.
		 */
		public function get_references( int $production_id = 0, string $year = '', string $type = 'all' ): array {
			// Generate unique cache key based on parameters.
			$cache_key = $this->cache_prefix . md5( serialize( array( $production_id, $year, $type ) ) );
			
			// Try cache first.
			$cached = get_transient( $cache_key );
			if ( false !== $cached ) {
				return $cached;
			}

			// Build WP_Query arguments for GatherPress event posts.
			$args = array(
				'post_type'              => 'gatherpress_event',
				'posts_per_page'         => -1,
				'post_status'            => 'publish',
				'orderby'                => 'date',
				'order'                  => 'DESC', // Newest first.
				'fields'                 => 'ids', // Only get IDs for performance.
				'no_found_rows'          => true, // Skip pagination count.
				'update_post_meta_cache' => false, // Don't cache meta (we don't use it).
				'update_post_term_cache' => true, // Do cache terms (we need them).
			);

			/**
			 * Filter the base query arguments before filters are applied.
			 *
			 * Allows modification of the WP_Query arguments before production,
			 * year, and type filters are added. Useful for adding custom
			 * meta queries or other query modifications.
			 *
			 * @since 0.1.0
			 *
			 * @param array  $args          WP_Query arguments array.
			 * @param int    $production_id Production term ID filter.
			 * @param string $year          Year filter.
			 * @param string $type          Reference type filter.
			 *
			 * @example
			 * // Limit query to 50 posts
			 * add_filter( 'gatherpress_references_query_args', function( $args ) {
			 *     $args['posts_per_page'] = 50;
			 *     return $args;
			 * } );
			 *
			 * @example
			 * // Add custom meta query
			 * add_filter( 'gatherpress_references_query_args', function( $args ) {
			 *     $args['meta_query'] = array(
			 *         array(
			 *             'key'     => 'featured',
			 *             'value'   => '1',
			 *             'compare' => '='
			 *         )
			 *     );
			 *     return $args;
			 * } );
			 */
			$args = apply_filters( 'gatherpress_references_query_args', $args, $production_id, $year, $type );

			// Get taxonomies to query based on type filter.
			$taxonomies = $this->get_taxonomies_by_type( $type );
			
			// Build tax_query - START FRESH.
			$tax_query = array();
			
			// STEP 1: Add production filter if specified.
			if ( $production_id > 0 ) {
				$tax_query[] = array(
					'taxonomy' => 'gatherpress-productions',
					'field'    => 'term_id',
					'terms'    => $production_id,
				);
			}
			
			// STEP 2: Add type filter ONLY if not 'all'.
			if ( $type !== 'all' && ! empty( $taxonomies ) ) {
				
				// When filtering by type, we want posts that have ANY of these taxonomies.
				if ( count( $taxonomies ) === 1 ) {
					// Single taxonomy - simple EXISTS check.
					$tax_query[] = array(
						'taxonomy' => $taxonomies[0],
						'operator' => 'EXISTS',
					);
				} else {
					// Multiple taxonomies - use OR relation.
					$type_query = array( 'relation' => 'OR' );
					foreach ( $taxonomies as $taxonomy ) {
						$type_query[] = array(
							'taxonomy' => $taxonomy,
							'operator' => 'EXISTS',
						);
					}
					$tax_query[] = $type_query;
				}
			}
			
			// STEP 3: Apply tax_query to args ONLY if we have filters.
			if ( ! empty( $tax_query ) ) {
				
				// Only add 'relation' if we have MORE than one top-level filter.
				if ( count( $tax_query ) > 1 ) {
					$tax_query = array_merge( array( 'relation' => 'AND' ), $tax_query );
				}
				
				$args['tax_query'] = $tax_query;
			}

			// STEP 4: Add year filter if specified.
			if ( ! empty( $year ) ) {
				$args['date_query'] = array(
					array( 'year' => intval( $year ) ),
				);
			}

			// Execute query.
			$query      = new WP_Query( $args );
			$references = array();

			if ( ! empty( $query->posts ) ) {
				$post_ids = $query->posts;
				
				// Batch fetch post dates for efficiency.
				$post_dates = $this->get_post_dates( $post_ids );

				// Always get all reference taxonomies for display.
				$display_taxonomies = array( '_gatherpress-client', '_gatherpress-festival', '_gatherpress-award' );

				// Batch fetch all taxonomy terms.
				$post_terms = $this->get_post_terms( $post_ids, $display_taxonomies );

				// Organize data by year and type.
				foreach ( $post_ids as $post_id ) {
					if ( ! isset( $post_dates[ $post_id ] ) ) {
						continue;
					}

					// Extract year from post date.
					$post_year = $post_dates[ $post_id ]->year;
					$terms = isset( $post_terms[ $post_id ] ) ? $post_terms[ $post_id ] : array();

					// Initialize year structure if not exists.
					if ( ! isset( $references[ $post_year ] ) ) {
						$references[ $post_year ] = array(
							'_gatherpress-client' => array(),
							'_gatherpress-festival' => array(),
							'_gatherpress-award' => array(),
						);
					}

					// Add term names (deduplicated).
					foreach ( $display_taxonomies as $taxonomy ) {
						if ( isset( $terms[ $taxonomy ] ) ) {
							foreach ( $terms[ $taxonomy ] as $term ) {
								// Only add if not already present (deduplication).
								if ( ! in_array( $term->name, $references[ $post_year ][ $taxonomy ], true ) ) {
									$references[ $post_year ][ $taxonomy ][] = $term->name;
								}
							}
						}
					}
				}
			}

			/**
			 * Filter the final organized references data.
			 *
			 * Allows modification of the references structure before it's
			 * cached and returned. Useful for sorting, filtering, or
			 * reorganizing the data.
			 *
			 * @since 0.1.0
			 *
			 * @param array  $references    Nested array of year => type => references.
			 * @param int    $production_id Production term ID filter.
			 * @param string $year          Year filter.
			 * @param string $type          Reference type filter.
			 *
			 * @example
			 * // Sort references alphabetically within each type
			 * add_filter( 'gatherpress_references_data', function( $references ) {
			 *     foreach ( $references as $year => $types ) {
			 *         foreach ( $types as $type => $items ) {
			 *             sort( $references[ $year ][ $type ] );
			 *         }
			 *     }
			 *     return $references;
			 * } );
			 *
			 * @example
			 * // Filter out years older than 2020
			 * add_filter( 'gatherpress_references_data', function( $references ) {
			 *     return array_filter( $references, function( $year ) {
			 *         return intval( $year ) >= 2020;
			 *     }, ARRAY_FILTER_USE_KEY );
			 * } );
			 */
			$references = apply_filters( 'gatherpress_references_data', $references, $production_id, $year, $type );

			// Cache results
			set_transient( $cache_key, $references, $this->cache_expiration );
			return $references;
		}

		/**
		 * Get taxonomy slugs based on type filter
		 *
		 * Converts block attribute reference types to taxonomy slugs.
		 *
		 * @since 0.1.0
		 * @param string $type Reference type from block attributes.
		 * @return array Array of taxonomy slugs to query.
		 */
		private function get_taxonomies_by_type( string $type ): array {
			if ( $type === '_gatherpress-client' ) {
				return array( '_gatherpress-client' );
			} elseif ( $type === '_gatherpress-festival' ) {
				return array( '_gatherpress-festival' );
			} elseif ( $type === '_gatherpress-award' ) {
				return array( '_gatherpress-award' );
			}
			// 'all' - return empty array (no type filter)
			return array();
		}

		/**
		 * Batch fetch post dates
		 *
		 * Uses direct database query for efficiency when fetching many post dates.
		 *
		 * @since 0.1.0
		 * @param array $post_ids Array of post IDs.
		 * @return array Associative array of post_id => year data.
		 */
		private function get_post_dates( array $post_ids ): array {
			global $wpdb;
			
			if ( empty( $post_ids ) ) {
				return array();
			}
			
			// Sanitize IDs for safe SQL.
			$safe_ids = array_map( 'intval', $post_ids );
			$placeholders = implode( ',', array_fill( 0, count( $safe_ids ), '%d' ) );
			
			// Execute optimized query to get year from post_date.
			$results = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT ID, YEAR(post_date) as year FROM {$wpdb->posts} WHERE ID IN ({$placeholders}) ORDER BY post_date DESC",
					...$safe_ids
				),
				OBJECT_K // Use ID as array key.
			);
			
			return $results;
		}

		/**
		 * Batch fetch post terms
		 *
		 * Retrieves all terms for given posts and taxonomies.
		 * Organized by post_id and taxonomy for easy lookup.
		 *
		 * Example return:
		 * array(
		 *     123 => array(
		 *         '_gatherpress-client' => array( WP_Term, WP_Term ),
		 *         '_gatherpress-festival' => array( WP_Term )
		 *     )
		 * )
		 *
		 * @since 0.1.0
		 * @param array $post_ids   Array of post IDs.
		 * @param array $taxonomies Array of taxonomy slugs to fetch.
		 * @return array Nested array of post_id => taxonomy => terms.
		 */
		private function get_post_terms( array $post_ids, array $taxonomies ): array {
			if ( empty( $post_ids ) || empty( $taxonomies ) ) {
				return array();
			}

			$organized_terms = array();
			
			// Fetch terms for each post and taxonomy.
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
		 * Get human-readable type labels
		 *
		 * Maps taxonomy slugs to translatable display labels.
		 *
		 * @since 0.1.0
		 * @return array Associative array of taxonomy => label.
		 */
		public function get_type_labels(): array {
			$labels = array(
				'_gatherpress-client'    => __( 'Clients', 'gatherpress-references' ),
				'_gatherpress-festival' => __( 'Festivals', 'gatherpress-references' ),
				'_gatherpress-award'    => __( 'Awards', 'gatherpress-references' ),
			);

			/**
			 * Filter the type labels displayed in headings.
			 *
			 * Allows customization of the human-readable labels for each
			 * reference type. Useful when adding custom taxonomies or
			 * translating for specific locales.
			 *
			 * @since 0.1.0
			 *
			 * @param array $labels Array of taxonomy slug => label pairs.
			 *
			 * @example
			 * // Add custom taxonomy label
			 * add_filter( 'gatherpress_references_type_labels', function( $labels ) {
			 *     $labels['gatherpress-custom'] = __( 'Custom References', 'textdomain' );
			 *     return $labels;
			 * } );
			 *
			 * @example
			 * // Override existing label
			 * add_filter( 'gatherpress_references_type_labels', function( $labels ) {
			 *     $labels['_gatherpress-award'] = __( 'Prizes & Honours', 'textdomain' );
			 *     return $labels;
			 * } );
			 */
			return apply_filters( 'gatherpress_references_type_labels', $labels );
		}
	}
}

// Initialize renderer.
$renderer = new GatherPress_References_Renderer();

// Extract and sanitize block attributes.
$production_id = isset( $attributes['productionId'] ) ? intval( $attributes['productionId'] ) : 0;
$year = isset( $attributes['year'] ) ? sanitize_text_field( $attributes['year'] ) : '';
$type = isset( $attributes['referenceType'] ) ? sanitize_text_field( $attributes['referenceType'] ) : 'all';
$heading_level = isset( $attributes['headingLevel'] ) ? intval( $attributes['headingLevel'] ) : 2;

// Ensure heading level is within valid range (H1-H6).
$heading_level = max( 1, min( 6, $heading_level ) );

// Calculate secondary heading level (type headings are one level smaller).
$secondary_heading_level = min( $heading_level + 1, 6 );

// Map legacy attribute values to taxonomy slugs.
if ( $type === 'ref_client' ) {
	$type = '_gatherpress-client';
} elseif ( $type === 'ref_festival' ) {
	$type = '_gatherpress-festival';
} elseif ( $type === 'ref_award' ) {
	$type = '_gatherpress-award';
}

// Auto-detect production from current taxonomy term if viewing a production archive.
if ( $production_id === 0 && is_tax( 'gatherpress-productions' ) ) {
	$term = get_queried_object();
	$production_id = $term->term_id;
}

// Fetch organized reference data.
$references = $renderer->get_references( $production_id, $year, $type );
$type_labels = $renderer->get_type_labels();

// Determine if we're showing a specific type (affects heading display).
$is_specific_type = ( $type !== 'all' );

?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php if ( ! empty( $references ) ) : ?>
		<?php foreach ( $references as $ref_year => $types ) : ?>
			<h<?php echo esc_attr( $heading_level ); ?> class="references-year"><?php echo esc_html( $ref_year ); ?></h<?php echo esc_attr( $heading_level ); ?>>
			
			<?php foreach ( $types as $ref_type => $items ) : ?>
				<?php if ( ! empty( $items ) ) : ?>
					<?php if ( ! $is_specific_type ) : ?>
						<h<?php echo esc_attr( $secondary_heading_level ); ?> class="references-type"><?php echo esc_html( $type_labels[ $ref_type ] ); ?></h<?php echo esc_attr( $secondary_heading_level ); ?>>
					<?php endif; ?>
					
					<ul class="references-list">
						<?php foreach ( $items as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
	<?php else : ?>
		<p class="no-references"><?php esc_html_e( 'No references found matching the selected criteria.', 'gatherpress-references' ); ?></p>
	<?php endif; ?>
</div>