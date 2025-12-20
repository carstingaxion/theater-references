<?php
/**
 * GatherPress References Block - Frontend Renderer
 *
 * This file handles the server-side rendering of the GatherPress References block
 * for GatherPress events. It queries GatherPress events using GatherPress's custom
 * gatherpress_events table, organizes references by year and type, and outputs structured HTML.
 *
 * @package GatherPress_References
 * @since 0.1.0
 */

namespace GatherPress\References;

use WP_Query;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\GatherPress\References\Renderer' ) ) {
	/**
	 * GatherPress References Renderer
	 *
	 * Handles data retrieval, caching, and organization for block rendering.
	 * Optimized for performance with transient caching and efficient queries.
	 * Works with GatherPress events (gatherpress_event post type).
	 *
	 * @since 0.1.0
	 */
	class Renderer {
		/**
		 * Singleton instance
		 *
		 * @var Renderer|null
		 */
		private static ?Renderer $instance = null;
		
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
		 * Get singleton instance
		 *
		 * Creates instance on first call, returns existing instance on subsequent calls.
		 *
		 * @since 0.1.0
		 * @return Renderer The singleton instance.
		 */
		public static function get_instance(): Renderer {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Apply filterable properties
		 *
		 * Allows developers to modify class properties via filters.
		 *
		 * @since 0.1.0
		 * @return void
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
		 * Generate cache key based on parameters
		 *
		 * Creates a unique, deterministic cache key from the given parameters.
		 * Uses MD5 hash of serialized parameters for consistency.
		 *
		 * @since 0.1.0
		 * @param int $production_id Production term ID.
		 * @param int $year          Year filter.
		 * @param string $type          Reference type filter.
		 * @return string Cache key for transient storage.
		 */
		private function get_cache_key( int $production_id, int $year, string $type ): string {
			return $this->cache_prefix . md5( maybe_serialize( array( $production_id, $year, $type ) ) );
		}

		/**
		 * Get references organized by year and type
		 *
		 * Retrieves all matching GatherPress events and organizes their taxonomy terms
		 * by year and reference type. Results are cached for performance.
		 * Year sorting is applied after retrieval based on the sort_order parameter.
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
		 * @param int    $year          Optional. Filter by specific year (e.g., 2024). Default 0 (all).
		 * @param string $type          Optional. Filter by reference type or 'all'. Default 'all'.
		 * @param string $sort_order    Optional. Year sort order: 'asc' or 'desc'. Default 'desc'.
		 * @return array<string, array<string, array<int, string>>> Nested array of references organized by year and type.
		 */
		public function get_references( int $production_id = 0, int $year = 0, string $type = 'all', string $sort_order = 'desc' ): array {
			// Try to get cached data first.
			$cached = $this->get_cached_references( $production_id, $year, $type );
			if ( false !== $cached ) {
				// Sort the cached data based on requested order.
				return $this->sort_years( $cached, $sort_order );
			}

			// Build and execute query.
			$args  = $this->build_query_args( $production_id, $year, $type );
			$query = new \WP_Query( $args );
			
			// Organize results.
			$references = $this->organize_query_results( $query );

			// Cache and return only if we have actual data.
			if ( ! empty( $references ) ) {
				$this->cache_references( $references, $production_id, $year, $type );
			}
			
			// Sort and return.
			return $this->sort_years( $references, $sort_order );
		}

		/**
		 * Get cached references if available.
		 *
		 * @since 0.1.0
		 * @param int    $production_id Production term ID.
		 * @param int    $year          Year filter.
		 * @param string $type          Reference type filter.
		 * @return array<string, array<string, array<int, string>>>|false Cached data or false if not found.
		 */
		private function get_cached_references( int $production_id, int $year, string $type ) {
			$cache_key = $this->get_cache_key( $production_id, $year, $type );
			$cached    = get_transient( $cache_key );
			
			if ( false !== $cached && is_array( $cached ) && ! empty( $cached ) ) {
				/**
				 * Type safe hint for phpstan and static analysis.
				 *
				 * @var array<string, array<string, array<int, string>>> $cached
				 */
				return $cached;
			}
			
			return false;
		}

		/**
		 * Cache references data.
		 *
		 * @since 0.1.0
		 * @param array<string, array<string, array<int, string>>> $references    References data to cache.
		 * @param int                                              $production_id Production term ID.
		 * @param int                                              $year          Year filter.
		 * @param string                                           $type          Reference type filter.
		 * @return void
		 */
		private function cache_references( array $references, int $production_id, int $year, string $type ): void {
			$cache_key = $this->get_cache_key( $production_id, $year, $type );
			set_transient( $cache_key, $references, $this->cache_expiration );
		}

		/**
		 * Build WP_Query arguments
		 *
		 * Constructs the base query arguments and applies all filters.
		 *
		 * @since 0.1.0
		 * @param int    $production_id Production term ID.
		 * @param int    $year          Year filter.
		 * @param string $type          Reference type filter.
		 * @return array<string, mixed> WP_Query arguments.
		 */
		private function build_query_args( int $production_id, int $year, string $type ): array {
			// Start with base arguments.
			$args = $this->get_base_query_args();

			// Apply taxonomy filters.
			$tax_query = $this->build_tax_query( $production_id, $type );
			if ( ! empty( $tax_query ) ) {
				$args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			}

			// Apply year filter if specified.
			if ( $year > 0 ) {
				$args['date_query'] = array(
					array( 'year' => $year ),
				);
			}

			/**
			 * Filter the query arguments before execution.
			 *
			 * Allows modification of the WP_Query arguments before production,
			 * year, and type filters are added. Useful for adding custom
			 * meta queries or other query modifications.
			 *
			 * @example Limit query to 50 posts
			 * add_filter( 'gatherpress_references_query_args', function( $args ) {
			 *     $args['posts_per_page'] = 50;
			 *     return $args;
			 * } );
			 *
			 * @example Add custom meta query
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
			 *
			 * @since 0.1.0
			 *
			 * @param array<string, mixed> $args          WP_Query arguments array.
			 * @param int                  $production_id Production term ID filter.
			 * @param int                  $year          Year filter.
			 * @param string               $type          Reference type filter.
			 */
			return apply_filters( 'gatherpress_references_query_args', $args, $production_id, $year, $type );
		}

		/**
		 * Get base query arguments
		 *
		 * Returns the default WP_Query arguments for GatherPress events.
		 * Uses GatherPress's 'past' query parameter to ensure we only get
		 * past events based on the gatherpress_events table.
		 *
		 * @since 0.1.0
		 * @return array<string, mixed> Base query arguments.
		 */
		private function get_base_query_args(): array {
			return array(
				'post_type'              => 'gatherpress_event',
				'gatherpress_event_query'=> 'past', // Only past events (uses custom table).
				'posts_per_page'         => 9999,   // Large number to get all, but avoid -1.
				'post_status'            => 'publish',
				'orderby'                => 'date',
				'order'                  => 'DESC', // Newest first.
				'fields'                 => 'ids',  // Only get IDs for performance.
				'no_found_rows'          => true,   // Skip pagination count.
				'update_post_meta_cache' => false,  // Don't cache meta (we don't use it).
				'update_post_term_cache' => true,   // Do cache terms (we need them).
			);
		}

		/**
		 * Build taxonomy query
		 *
		 * Constructs the tax_query array based on production and type filters.
		 *
		 * @since 0.1.0
		 * @param int    $production_id Production term ID.
		 * @param string $type          Reference type filter.
		 * @return array<string|int, mixed> Tax query array.
		 */
		private function build_tax_query( int $production_id, string $type ): array {
			$tax_query = array();

			// Add production filter if specified.
			if ( $production_id > 0 ) {
				$tax_query[] = array(
					'taxonomy' => 'gatherpress-productions',
					'field'    => 'term_id',
					'terms'    => $production_id,
				);
			}

			// Add type filter if not 'all'.
			if ( $type !== 'all' ) {
				$type_query = $this->build_type_query( $type );
				if ( ! empty( $type_query ) ) {
					$tax_query[] = $type_query;
				}
			}

			// Add relation if we have multiple filters.
			if ( count( $tax_query ) > 1 ) {
				$tax_query = array_merge( array( 'relation' => 'AND' ), $tax_query );
			}

			return $tax_query;
		}

		/**
		 * Build type-specific query
		 *
		 * Creates the taxonomy query for a specific reference type.
		 *
		 * @since 0.1.0
		 * @param string $type Reference type filter.
		 * @return array<string|int, mixed> Type query array.
		 */
		private function build_type_query( string $type ): array {
			$taxonomies = $this->get_taxonomies_by_type( $type );
			
			if ( empty( $taxonomies ) ) {
				return array();
			}

			// Single taxonomy - simple EXISTS check.
			if ( count( $taxonomies ) === 1 ) {
				return array(
					'taxonomy' => $taxonomies[0],
					'operator' => 'EXISTS',
				);
			}

			// Multiple taxonomies - use OR relation.
			$type_query = array( 'relation' => 'OR' );
			foreach ( $taxonomies as $taxonomy ) {
				$type_query[] = array(
					'taxonomy' => $taxonomy,
					'operator' => 'EXISTS',
				);
			}

			return $type_query;
		}

		/**
		 * Organize query results
		 *
		 * Processes WP_Query results and organizes them by year and type.
		 *
		 * @since 0.1.0
		 * @param \WP_Query $query Query object with results.
		 * @return array<string, array<string, array<int, string>>> Organized references.
		 */
		private function organize_query_results( \WP_Query $query ): array {
			if ( empty( $query->posts ) ) {
				return array();
			}

			/**
			 * Type safe hint for phpstan and static analysis.
			 *
			 * This is for sure an array of integers as we set 'fields' => 'ids' in the query args.
			 *
			 * @var array<int, int> $post_ids
			 */
			$post_ids = $query->posts;

			// Batch fetch data.
			$post_dates         = $this->get_post_dates( $post_ids );
			$display_taxonomies = array( '_gatherpress-client', '_gatherpress-festival', '_gatherpress-award' );
			$post_terms         = $this->get_post_terms( $post_ids, $display_taxonomies );

			// Organize by year and type.
			$references = $this->group_terms_by_year( $post_ids, $post_dates, $post_terms, $display_taxonomies );

			return $references;
		}

		/**
		 * Sort years in the references array
		 *
		 * Sorts the year keys while preserving child arrays (taxonomy data).
		 *
		 * @since 0.1.0
		 * @param array<string, array<string, array<int, string>>> $references  Reference data to sort.
		 * @param string                                           $sort_order  Sort order: 'asc' or 'desc'.
		 * @return array<string, array<string, array<int, string>>> Sorted reference data.
		 */
		private function sort_years( array $references, string $sort_order ): array {
			if ( empty( $references ) ) {
				return $references;
			}

			// Get year keys and sort them.
			$years = array_keys( $references );

			if ( $sort_order === 'asc' ) {
				// Sort oldest first.
				sort( $years, SORT_NUMERIC );
			} else {
				// Sort newest first (default).
				rsort( $years, SORT_NUMERIC );
			}

			// Rebuild array with sorted years.
			$sorted = array();
			foreach ( $years as $year ) {
				$sorted[ (string) $year ] = $references[ (string) $year ];
			}

			return $sorted;
		}

		/**
		 * Group terms by year
		 *
		 * Organizes post terms into a nested structure by year and taxonomy.
		 * Filters out empty arrays to ensure clean data structure.
		 *
		 * @since 0.1.0
		 * @param array<int, int>                              $post_ids           Array of post IDs.
		 * @param array<int, object{post_id: string, year: string}> $post_dates         Post date data from GatherPress table.
		 * @param array<int, ?array<string, array<\WP_Term>>>  $post_terms         Post terms organized by taxonomy.
		 * @param array<int, string>                           $display_taxonomies Taxonomies to display.
		 * @return array<string, array<string, array<int, string>>> Organized references.
		 */
		private function group_terms_by_year( array $post_ids, array $post_dates, array $post_terms, array $display_taxonomies ): array {
			$references = array();

			foreach ( $post_ids as $post_id ) {
				if ( ! isset( $post_dates[ $post_id ] ) ) {
					continue;
				}

				$post_year = $post_dates[ $post_id ]->year;
				$terms     = isset( $post_terms[ $post_id ] ) ? $post_terms[ $post_id ] : array();

				// Initialize year structure if not exists.
				if ( ! isset( $references[ $post_year ] ) ) {
					$references[ $post_year ] = $this->initialize_year_structure();
				}

				// Add terms to year structure.
				$this->add_terms_to_year( $references[ $post_year ], $terms, $display_taxonomies );
			}

			// Sort term names alphabetically within each taxonomy.
			$references = $this->sort_term_names( $references );

			// Clean up empty arrays.
			$references = $this->remove_empty_arrays( $references );

			return $references;
		}

		/**
		 * Sort term names alphabetically within each taxonomy
		 *
		 * Ensures all term names are displayed in alphabetical order
		 * for better readability and consistency.
		 *
		 * @since 0.1.0
		 * @param array<string, array<string, array<int, string>>> $references Reference data to sort.
		 * @return array<string, array<string, array<int, string>>> Sorted reference data.
		 */
		private function sort_term_names( array $references ): array {
			foreach ( $references as $year => $year_data ) {
				foreach ( $year_data as $taxonomy => $items ) {
					// Sort terms alphabetically (case-insensitive).
					natcasesort( $references[ $year ][ $taxonomy ] );
					// Re-index array to ensure sequential keys.
					$references[ $year ][ $taxonomy ] = array_values( $references[ $year ][ $taxonomy ] );
				}
			}
			return $references;
		}

		/**
		 * Remove empty arrays from references structure
		 *
		 * Filters out:
		 * 1. Empty taxonomy arrays within a year
		 * 2. Years that have no taxonomy data after cleanup
		 *
		 * @since 0.1.0
		 * @param array<string, array<string, array<int, string>>> $references Reference data to clean.
		 * @return array<string, array<string, array<int, string>>> Cleaned reference data.
		 */
		private function remove_empty_arrays( array $references ): array {
			foreach ( $references as $year => $year_data ) {
				// Remove empty taxonomy arrays.
				foreach ( $year_data as $taxonomy => $items ) {
					if ( empty( $items ) ) {
						unset( $references[ $year ][ $taxonomy ] );
					}
				}

				// If all taxonomies are empty for this year, remove the year.
				if ( empty( $references[ $year ] ) ) {
					unset( $references[ $year ] );
				}
			}

			return $references;
		}

		/**
		 * Initialize year structure
		 *
		 * Creates the default structure for a year's references.
		 *
		 * @since 0.1.0
		 * @return array<string, array<int, string>> Empty reference structure.
		 */
		private function initialize_year_structure(): array {
			return array(
				'_gatherpress-client'   => array(),
				'_gatherpress-festival' => array(),
				'_gatherpress-award'    => array(),
			);
		}

		/**
		 * Add terms to year structure
		 *
		 * Adds taxonomy terms to a year's reference structure with deduplication.
		 *
		 * @since 0.1.0
		 * @param array<string, array<int, string>> $year_data          Year structure to add to (passed by reference).
		 * @param ?array<string, array<\WP_Term>>   $terms              Post terms organized by taxonomy.
		 * @param array<int, string>                $display_taxonomies Taxonomies to process.
		 * @return void
		 */
		private function add_terms_to_year( array &$year_data, ?array $terms, array $display_taxonomies ): void {
			if ( empty( $terms ) ) {
				return;
			}

			foreach ( $display_taxonomies as $taxonomy ) {
				if ( ! isset( $terms[ $taxonomy ] ) ) {
					continue;
				}

				foreach ( $terms[ $taxonomy ] as $term ) {
					// Deduplicate - only add if not already present.
					if ( ! in_array( $term->name, $year_data[ $taxonomy ], true ) ) {
						$year_data[ $taxonomy ][] = $term->name;
					}
				}
			}
		}

		/**
		 * Get taxonomy slugs based on type filter
		 *
		 * Converts block attribute reference types to taxonomy slugs.
		 *
		 * @since 0.1.0
		 * @param string $type Reference type from block attributes.
		 * @return array<int, string> Array of taxonomy slugs to query.
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
		 * Batch fetch post dates from GatherPress events table
		 *
		 * This method efficiently retrieves event dates for multiple posts using a single
		 * database query against GatherPress's custom gatherpress_events table.
		 *
		 * GatherPress stores event metadata in a dedicated table with indexed columns,
		 * which is more efficient than querying post meta or post dates. The datetime_start_gmt
		 * column contains the actual event start datetime in GMT.
		 *
		 * This method is called internally by `get_references()` to batch-fetch years
		 * for all matching events in a single query, avoiding N+1 query problems.
		 * The returned data structure allows O(1) lookup of any post's year by post ID.
		 *
		 * Example return:
		 * array(
		 *    123 => (object) { post_id: '123', year: '2024' },
		 *    456 => (object) { post_id: '456', year: '2023' },
		 * )
		 *
		 * @since 0.1.0
		 *
		 * @param array<int, int> $post_ids Array of post IDs to fetch dates for.
		 * @return array<int, object{post_id: string, year: string}> Associative array of post_id => year data.
		 */
		private function get_post_dates( array $post_ids ): array {
			/**
			 * Batch fetch post dates
			 *
			 * @var \wpdb  $wpdb WordPress database abstraction object.
			 * */
			global $wpdb;
			
			if ( empty( $post_ids ) ) {
				return array();
			}
			
			// Sanitize IDs for safe SQL.
			$safe_ids     = array_map( 'intval', $post_ids );
			$placeholders = implode( ',', array_fill( 0, count( $safe_ids ), '%d' ) );
			
			// Use GatherPress's custom events table.
			$table = $wpdb->prefix . 'gatherpress_events';
			
			// Execute optimized query to get year from datetime_start_gmt column.
			/** @var literal-string $sql */
			$sql = "SELECT post_id, YEAR(datetime_start_gmt) AS year
					FROM {$table}
					WHERE post_id IN ({$placeholders})
					ORDER BY datetime_start_gmt DESC";

			$results = $wpdb->get_results(
				$wpdb->prepare( $sql, ...$safe_ids ),
				OBJECT_K
			);

			if ( null === $results || ! is_array( $results ) ) {
				return array();
			}

			/**
			 * Type cast for phpstan.
			 *
			 * @var array<int, object{post_id: string, year: string}> $results
			 */
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
		 * @param array<int, int>    $post_ids   Array of post IDs.
		 * @param array<int, string> $taxonomies Array of taxonomy slugs to fetch.
		 * @return array<int, ?array<string, array<\WP_Term>>> Nested array of post_id => taxonomy => terms.
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
		 * @return array<string, string> Associative array of taxonomy => label.
		 */
		public function get_type_labels(): array {
			$labels = array(
				'_gatherpress-client'   => __( 'Clients', 'gatherpress-references' ),
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
			 * @param array<string, string> $labels Array of taxonomy slug => label pairs.
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

// Initialize the singleton instance.
$renderer = Renderer::get_instance();

/**
 * Extract and sanitize block attributes.
 *
 * @var array{
 *   productionId?: int,
 *   year?: int,
 *   referenceType?: string,
 *   headingLevel?: int,
 *   yearSortOrder?: string
 * } $attributes
 */
$production_id  = isset( $attributes['productionId'] ) ? intval( $attributes['productionId'] ) : 0;
$year           = isset( $attributes['year'] ) ? intval( $attributes['year'] ) : 0;
$type           = isset( $attributes['referenceType'] ) ? sanitize_text_field( $attributes['referenceType'] ) : 'all';
$heading_level  = isset( $attributes['headingLevel'] ) ? intval( $attributes['headingLevel'] ) : 2;
$year_sort      = isset( $attributes['yearSortOrder'] ) ? sanitize_text_field( $attributes['yearSortOrder'] ) : 'desc';

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

// Validate sort order.
if ( ! in_array( $year_sort, array( 'asc', 'desc' ), true ) ) {
	$year_sort = 'desc';
}

// Auto-detect production from current taxonomy term if viewing a production archive.
if ( $production_id === 0 && is_tax( 'gatherpress-productions' ) ) {
	$term = get_queried_object();
	if ( $term instanceof \WP_Term ) {
		$production_id = $term->term_id;
	}
}

// Fetch organized reference data with year sorting applied after retrieval.
$references  = $renderer->get_references( $production_id, $year, $type, $year_sort );
$type_labels = $renderer->get_type_labels();

// Determine if we're showing a specific type (affects heading display).
$is_specific_type = ( $type !== 'all' );

?>
<?php if ( ! empty( $references ) ) { ?>

<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() is escaped internally. ?>>
	<?php foreach ( $references as $ref_year => $types ) { ?>
		<h<?php echo esc_attr( (string) $heading_level ); ?> class="wp-block-heading references-year"><?php echo esc_html( $ref_year ); ?></h<?php echo esc_attr( (string) $heading_level ); ?>>
		
		<?php foreach ( $types as $ref_type => $items ) { ?>

			<?php
			// Entry point for further investigation on not needed types queried (!) and rendered.
			if ( ( $type === $ref_type || ! $is_specific_type ) && ! empty( $items ) ) {
				?>
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
<?php } ?>