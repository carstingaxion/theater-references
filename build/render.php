<?php
/**
 * GatherPress References Block - Frontend Renderer
 *
 * This file handles the server-side rendering of the GatherPress References block
 * for GatherPress events. It queries GatherPress events, organizes references
 * by year and type, and outputs structured HTML.
 *
 * @package GatherPressReferences
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
		 * Debug mode flag
		 * Set to true to enable debug output (requires WP_DEBUG)
		 *
		 * @var bool
		 */
		private bool $debug = true;

		/**
		 * Debug log storage
		 *
		 * @var array
		 */
		private array $debug_log = array();

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
		 * Check if debug mode is enabled
		 *
		 * Debug output only shows when both:
		 * 1. WP_DEBUG constant is true
		 * 2. $this->debug property is true
		 *
		 * @since 0.1.0
		 * @return bool True if debug mode is enabled
		 */
		private function is_debug_enabled(): bool {
			return defined( 'WP_DEBUG' ) && WP_DEBUG && $this->debug;
		}

		/**
		 * Add debug message
		 *
		 * @param string $message Debug message
		 * @param mixed  $data    Optional data to log
		 */
		private function debug( string $message, $data = null ): void {
			if ( ! $this->is_debug_enabled() ) {
				return;
			}

			$entry = array(
				'message' => $message,
				'time' => microtime( true ),
			);

			if ( $data !== null ) {
				$entry['data'] = $data;
			}

			$this->debug_log[] = $entry;
		}

		/**
		 * Get debug log as HTML
		 *
		 * Only outputs when WP_DEBUG is true.
		 *
		 * @return string HTML formatted debug log or empty string
		 */
		public function get_debug_output(): string {
			if ( ! $this->is_debug_enabled() || empty( $this->debug_log ) ) {
				return '';
			}

			$output = '<div style="background: #f0f0f0; border: 2px solid #333; padding: 20px; margin: 20px 0; font-family: monospace; font-size: 12px;">';
			$output .= '<h3 style="margin-top: 0;">🐛 Debug Log (WP_DEBUG enabled)</h3>';

			foreach ( $this->debug_log as $entry ) {
				$output .= '<div style="margin-bottom: 10px; padding: 10px; background: white; border-left: 3px solid #2271b1;">';
				$output .= '<strong>' . esc_html( $entry['message'] ) . '</strong>';

				if ( isset( $entry['data'] ) ) {
					$output .= '<pre style="margin: 5px 0 0 0; overflow-x: auto;">';
					$output .= esc_html( print_r( $entry['data'], true ) );
					$output .= '</pre>';
				}

				$output .= '</div>';
			}

			$output .= '</div>';
			return $output;
		}

		/**
		 * Get test matrix HTML
		 *
		 * Only outputs when WP_DEBUG is true.
		 *
		 * @since 0.1.0
		 * @param int    $production_id Current production ID filter
		 * @param string $type          Current type filter
		 * @param string $year          Current year filter
		 * @return string HTML formatted test matrix or empty string
		 */
		public function get_test_matrix( int $production_id, string $type, string $year ): string {
			if ( ! $this->is_debug_enabled() ) {
				return '';
			}

			ob_start();
			?>
			<div style="background: #fff; border: 2px solid #0073aa; padding: 20px; margin: 20px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;">
				<h3 style="margin-top: 0; color: #0073aa;">🧪 Test Matrix - Expected Behaviors (WP_DEBUG enabled)</h3>
				<p style="font-size: 13px; color: #666;">Test these combinations to verify filtering logic:</p>
				
				<table style="width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 12px;">
					<thead>
						<tr style="background: #f0f0f0;">
							<th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Test #</th>
							<th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Production</th>
							<th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Type</th>
							<th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Year</th>
							<th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Expected Result</th>
						</tr>
					</thead>
					<tbody>
						<tr style="background: <?php echo ( $production_id === 0 && $type === 'all' && empty( $year ) ) ? '#e7f7ff' : 'white'; ?>">
							<td style="padding: 8px; border: 1px solid #ddd;">1</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Any/All</td>
							<td style="padding: 8px; border: 1px solid #ddd;">All</td>
							<td style="padding: 8px; border: 1px solid #ddd;">All</td>
							<td style="padding: 8px; border: 1px solid #ddd;"><strong>Show all GatherPress events, all types</strong> (no filters)</td>
						</tr>
						<tr style="background: <?php echo ( $production_id > 0 && $type === 'all' && empty( $year ) ) ? '#e7f7ff' : 'white'; ?>">
							<td style="padding: 8px; border: 1px solid #ddd;">2</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Specific</td>
							<td style="padding: 8px; border: 1px solid #ddd;">All</td>
							<td style="padding: 8px; border: 1px solid #ddd;">All</td>
							<td style="padding: 8px; border: 1px solid #ddd;"><strong>Show events for production, all types</strong> (production filter only)</td>
						</tr>
						<tr style="background: <?php echo ( $production_id > 0 && $type !== 'all' && empty( $year ) ) ? '#e7f7ff' : 'white'; ?>">
							<td style="padding: 8px; border: 1px solid #ddd;">3</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Specific</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Specific</td>
							<td style="padding: 8px; border: 1px solid #ddd;">All</td>
							<td style="padding: 8px; border: 1px solid #ddd;"><strong>Show events for production with specific type</strong> (production AND type filter)</td>
						</tr>
						<tr style="background: <?php echo ( $production_id === 0 && $type !== 'all' && empty( $year ) ) ? '#e7f7ff' : 'white'; ?>">
							<td style="padding: 8px; border: 1px solid #ddd;">4</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Any/All</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Specific</td>
							<td style="padding: 8px; border: 1px solid #ddd;">All</td>
							<td style="padding: 8px; border: 1px solid #ddd;"><strong>Show all events with specific type</strong> (type filter only)</td>
						</tr>
						<tr style="background: <?php echo ( $production_id === 0 && $type === 'all' && ! empty( $year ) ) ? '#e7f7ff' : 'white'; ?>">
							<td style="padding: 8px; border: 1px solid #ddd;">5</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Any/All</td>
							<td style="padding: 8px; border: 1px solid #ddd;">All</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Specific</td>
							<td style="padding: 8px; border: 1px solid #ddd;"><strong>Show all events from year, all types</strong> (year filter only)</td>
						</tr>
						<tr style="background: <?php echo ( $production_id > 0 && $type === 'all' && ! empty( $year ) ) ? '#e7f7ff' : 'white'; ?>">
							<td style="padding: 8px; border: 1px solid #ddd;">6</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Specific</td>
							<td style="padding: 8px; border: 1px solid #ddd;">All</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Specific</td>
							<td style="padding: 8px; border: 1px solid #ddd;"><strong>Show production events from year, all types</strong> (production AND year filter)</td>
						</tr>
						<tr style="background: <?php echo ( $production_id > 0 && $type !== 'all' && ! empty( $year ) ) ? '#e7f7ff' : 'white'; ?>">
							<td style="padding: 8px; border: 1px solid #ddd;">7</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Specific</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Specific</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Specific</td>
							<td style="padding: 8px; border: 1px solid #ddd;"><strong>Show production events from year with specific type</strong> (all three filters)</td>
						</tr>
						<tr style="background: <?php echo ( $production_id === 0 && $type !== 'all' && ! empty( $year ) ) ? '#e7f7ff' : 'white'; ?>">
							<td style="padding: 8px; border: 1px solid #ddd;">8</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Any/All</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Specific</td>
							<td style="padding: 8px; border: 1px solid #ddd;">Specific</td>
							<td style="padding: 8px; border: 1px solid #ddd;"><strong>Show all events from year with specific type</strong> (type AND year filter)</td>
						</tr>
					</tbody>
				</table>
				
				<div style="margin-top: 15px; padding: 10px; background: #fff8e5; border-left: 3px solid #ffb900;">
					<strong>📌 Current Test:</strong><br>
					<code style="background: #f0f0f0; padding: 2px 6px; border-radius: 3px;">Production: <?php echo $production_id > 0 ? "ID {$production_id}" : 'Any/All'; ?></code>
					<code style="background: #f0f0f0; padding: 2px 6px; border-radius: 3px; margin-left: 5px;">Type: <?php echo esc_html( $type ); ?></code>
					<code style="background: #f0f0f0; padding: 2px 6px; border-radius: 3px; margin-left: 5px;">Year: <?php echo ! empty( $year ) ? esc_html( $year ) : 'All'; ?></code>
				</div>
			</div>
			<?php
			return ob_get_clean();
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
			$this->debug( 'Starting get_references for GatherPress events', array(
				'production_id' => $production_id,
				'year' => $year,
				'type' => $type,
			) );

			// Generate unique cache key based on parameters
			$cache_key = $this->cache_prefix . md5( serialize( array( $production_id, $year, $type ) ) );
			
			// Try cache first
			$cached = get_transient( $cache_key );
			if ( false !== $cached ) {
				$this->debug( 'Returning cached data' );
				return $cached;
			}

			// Build WP_Query arguments for GatherPress event posts
			$args = array(
				'post_type'              => 'gatherpress_event',
				'posts_per_page'         => -1,
				'post_status'            => 'publish',
				'orderby'                => 'date',
				'order'                  => 'DESC', // Newest first
				'fields'                 => 'ids', // Only get IDs for performance
				'no_found_rows'          => true, // Skip pagination count
				'update_post_meta_cache' => false, // Don't cache meta (we don't use it)
				'update_post_term_cache' => true, // Do cache terms (we need them)
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

			$this->debug( 'Query args after filter', $args );

			// Get taxonomies to query based on type filter
			$taxonomies = $this->get_taxonomies_by_type( $type );
			$this->debug( 'Taxonomies for type filter', array(
				'type' => $type,
				'taxonomies' => $taxonomies,
			) );
			
			// Build tax_query - START FRESH
			$tax_query = array();
			
			// STEP 1: Add production filter if specified
			if ( $production_id > 0 ) {
				$tax_query[] = array(
					'taxonomy' => 'gatherpress-productions',
					'field'    => 'term_id',
					'terms'    => $production_id,
				);
				$this->debug( 'Added production filter to tax_query', $tax_query );
			}
			
			// STEP 2: Add type filter ONLY if not 'all'
			if ( $type !== 'all' && ! empty( $taxonomies ) ) {
				$this->debug( 'Type is not all, adding type filter' );
				
				// When filtering by type, we want posts that have ANY of these taxonomies
				if ( count( $taxonomies ) === 1 ) {
					// Single taxonomy - simple EXISTS check
					$tax_query[] = array(
						'taxonomy' => $taxonomies[0],
						'operator' => 'EXISTS',
					);
					$this->debug( 'Added single taxonomy EXISTS', $tax_query );
				} else {
					// Multiple taxonomies - use OR relation
					$type_query = array( 'relation' => 'OR' );
					foreach ( $taxonomies as $taxonomy ) {
						$type_query[] = array(
							'taxonomy' => $taxonomy,
							'operator' => 'EXISTS',
						);
					}
					$tax_query[] = $type_query;
					$this->debug( 'Added multiple taxonomy OR', $tax_query );
				}
			}
			
			// STEP 3: Apply tax_query to args ONLY if we have filters
			if ( ! empty( $tax_query ) ) {
				$this->debug( 'Tax query is not empty, count: ' . count( $tax_query ) );
				
				// Only add 'relation' if we have MORE than one top-level filter
				if ( count( $tax_query ) > 1 ) {
					$this->debug( 'Multiple tax_query items, adding AND relation' );
					$tax_query = array_merge( array( 'relation' => 'AND' ), $tax_query );
				}
				
				$args['tax_query'] = $tax_query;
				$this->debug( 'Final tax_query applied to args', $tax_query );
			}

			// STEP 4: Add year filter if specified
			if ( ! empty( $year ) ) {
				$args['date_query'] = array(
					array( 'year' => intval( $year ) ),
				);
				$this->debug( 'Added year filter', $args['date_query'] );
			}

			$this->debug( 'Final query args before execution', $args );

			// Execute query
			$query = new WP_Query( $args );
			
			$this->debug( 'Query executed', array(
				'found_posts' => count( $query->posts ),
				'post_ids' => $query->posts,
				'request' => $query->request,
			) );

			$references = array();

			if ( ! empty( $query->posts ) ) {
				$post_ids = $query->posts;
				
				// Batch fetch post dates for efficiency
				$post_dates = $this->get_post_dates( $post_ids );
				$this->debug( 'Fetched post dates', array(
					'count' => count( $post_dates ),
					'dates' => $post_dates,
				) );
				
				// Always get all reference taxonomies for display
				$display_taxonomies = array( '_gatherpress-client', '_gatherpress-festival', '_gatherpress-award' );
				
				/**
				 * Filter the taxonomies displayed in the output.
				 *
				 * Allows adding or removing taxonomies from the final output.
				 * Useful when registering custom reference taxonomies.
				 *
				 * @since 0.1.0
				 *
				 * @param array $display_taxonomies Array of taxonomy slugs to display.
				 *
				 * @example
				 * // Add custom taxonomy
				 * add_filter( 'gatherpress_references_display_taxonomies', function( $taxonomies ) {
				 *     $taxonomies[] = 'gatherpress-custom';
				 *     return $taxonomies;
				 * } );
				 *
				 * @example
				 * // Show only clients
				 * add_filter( 'gatherpress_references_display_taxonomies', function( $taxonomies ) {
				 *     return array( '_gatherpress-client' );
				 * } );
				 */
				$display_taxonomies = apply_filters( 'gatherpress_references_display_taxonomies', $display_taxonomies );
				
				// Batch fetch all taxonomy terms
				$post_terms = $this->get_post_terms( $post_ids, $display_taxonomies );
				$this->debug( 'Fetched post terms', array(
					'post_count' => count( $post_terms ),
					'sample' => array_slice( $post_terms, 0, 2, true ),
				) );

				// Organize data by year and type
				foreach ( $post_ids as $post_id ) {
					if ( ! isset( $post_dates[ $post_id ] ) ) {
						$this->debug( "Skipping post {$post_id} - no date found" );
						continue;
					}

					// Extract year from post date
					$post_year = $post_dates[ $post_id ]->year;
					$terms = isset( $post_terms[ $post_id ] ) ? $post_terms[ $post_id ] : array();

					$this->debug( "Processing GatherPress event {$post_id}", array(
						'year' => $post_year,
						'has_clients' => isset( $terms['_gatherpress-client'] ),
						'has_festivals' => isset( $terms['_gatherpress-festival'] ),
						'has_awards' => isset( $terms['_gatherpress-award'] ),
					) );

					// Initialize year structure if not exists
					if ( ! isset( $references[ $post_year ] ) ) {
						$references[ $post_year ] = array(
							'_gatherpress-client' => array(),
							'_gatherpress-festival' => array(),
							'_gatherpress-award' => array(),
						);
					}

					// Add term names (deduplicated)
					foreach ( $display_taxonomies as $taxonomy ) {
						if ( isset( $terms[ $taxonomy ] ) ) {
							foreach ( $terms[ $taxonomy ] as $term ) {
								// Only add if not already present (deduplication)
								if ( ! in_array( $term->name, $references[ $post_year ][ $taxonomy ], true ) ) {
									$references[ $post_year ][ $taxonomy ][] = $term->name;
									$this->debug( "Added term: {$term->name} to {$taxonomy}" );
								}
							}
						}
					}
				}
			} else {
				$this->debug( 'No GatherPress events found by query' );
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

			$this->debug( 'Final references structure', $references );

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
			
			// Sanitize IDs for safe SQL
			$safe_ids = array_map( 'intval', $post_ids );
			$placeholders = implode( ',', array_fill( 0, count( $safe_ids ), '%d' ) );
			
			// Execute optimized query to get year from post_date
			$results = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT ID, YEAR(post_date) as year FROM {$wpdb->posts} WHERE ID IN ({$placeholders}) ORDER BY post_date DESC",
					...$safe_ids
				),
				OBJECT_K // Use ID as array key
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
			
			// Fetch terms for each post and taxonomy
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

// Initialize renderer
$renderer = new GatherPress_References_Renderer();

// Extract and sanitize block attributes
$production_id = isset( $attributes['productionId'] ) ? intval( $attributes['productionId'] ) : 0;
$year = isset( $attributes['year'] ) ? sanitize_text_field( $attributes['year'] ) : '';
$type = isset( $attributes['referenceType'] ) ? sanitize_text_field( $attributes['referenceType'] ) : 'all';
$heading_level = isset( $attributes['headingLevel'] ) ? intval( $attributes['headingLevel'] ) : 2;

// Ensure heading level is within valid range (H1-H6)
$heading_level = max( 1, min( 6, $heading_level ) );

// Calculate secondary heading level (type headings are one level smaller)
$secondary_heading_level = min( $heading_level + 1, 6 );

// Map legacy attribute values to taxonomy slugs
if ( $type === 'ref_client' ) {
	$type = '_gatherpress-client';
} elseif ( $type === 'ref_festival' ) {
	$type = '_gatherpress-festival';
} elseif ( $type === 'ref_award' ) {
	$type = '_gatherpress-award';
}

// Auto-detect production from current taxonomy term if viewing a production archive
if ( $production_id === 0 && is_tax( 'gatherpress-productions' ) ) {
	$term = get_queried_object();
	$production_id = $term->term_id;
}

// Fetch organized reference data
$references = $renderer->get_references( $production_id, $year, $type );
$type_labels = $renderer->get_type_labels();

// Determine if we're showing a specific type (affects heading display)
$is_specific_type = ( $type !== 'all' );

// Output debug information only if WP_DEBUG is true
echo $renderer->get_debug_output();
echo $renderer->get_test_matrix( $production_id, $type, $year );
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