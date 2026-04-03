<?php
/**
 * Cache Manager class
 *
 * Handles all caching operations including cache key generation,
 * storage, retrieval, and invalidation.
 *
 * @package GatherPress_References
 */

namespace GatherPress\References;

defined( 'ABSPATH' ) || exit;

/**
 * Cache Manager
 *
 * Handles all caching operations including cache key generation,
 * storage, retrieval, and invalidation.
 *
 * @since 0.1.0
 */
class Cache_Manager {
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
	 * @since 0.1.0
	 */
	public function __construct() {
		$this->apply_filterable_props();
	}

	/**
	 * Apply filterable properties
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function apply_filterable_props(): void {
		/**
		 * Allows filtering the cache expiration time in seconds.
		 *
		 * Defaults to: 3600s = 1h
		 *
		 * @since 0.1.0
		 *
		 * @param int $cache_expiration Cache expiration time in seconds.
		 * @return int Filtered cache expiration time.
		 *
		 * @example
		 * Increase cache to 2 hours:
		 * ```php
		 * add_filter( 'gatherpress_references_cache_expiration', function( $expiration ) {
		 *     return 7200; // Set cache expiration to 2 hours
		 * } );
		 * ```
		 *
		 * @example
		 * Disable caching by setting expiration to 0:
		 * ```php
		 * add_filter( 'gatherpress_references_cache_expiration', '__return_zero' );
		 * ```
		 */
		$new_cache_expiration = apply_filters( 'gatherpress_references_cache_expiration', $this->cache_expiration );
		// @phpstan-ignore-next-line -- Ensure the filter returns an integer, otherwise fallback to default expiration.
		$this->cache_expiration = is_int( $new_cache_expiration ) ? $new_cache_expiration : $this->cache_expiration;
	}

	/**
	 * Generate cache key
	 *
	 * @since 0.1.0
	 * @param string $post_type   Post type slug.
	 * @param int    $ref_term_id Reference term ID.
	 * @param int    $year        Year filter.
	 * @param string $type        Reference type filter.
	 * @return string Cache key.
	 */
	public function get_cache_key( string $post_type, int $ref_term_id, int $year, string $type ): string {
		return $this->cache_prefix . md5( (string) wp_json_encode( array( $post_type, $ref_term_id, $year, $type ) ) );
	}

	/**
	 * Get cached data
	 *
	 * @since 0.1.0
	 * @param string $cache_key Cache key.
	 * @return array<string, array<string, array<int, string>>>|false Cached data or false.
	 */
	public function get( string $cache_key ) {
		$cached = get_transient( $cache_key );

		/**
		 * This is for sure an array of int or false.
		 *
		 * @var array<string, array<string, array<int, string>>>|false $cached
		 */
		if ( false !== $cached ) {
			return $cached;
		}

		return false;
	}

	/**
	 * Set cached data
	 *
	 * @since 0.1.0
	 * @param string                                           $cache_key Cache key.
	 * @param array<string, array<string, array<int, string>>> $data      Data to cache.
	 * @return void
	 */
	public function set( string $cache_key, array $data ): void {
		set_transient( $cache_key, $data, $this->cache_expiration );
	}

	/**
	 * Clear all caches
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function clear_all(): void {
		global $wpdb;

		if ( ! $wpdb instanceof \wpdb ) {
			return;
		}

		$transient_pattern = $wpdb->esc_like( '_transient_' . $this->cache_prefix ) . '%';
		$transients_names  = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->prepare(
				// @phpstan-ignore-next-line
				"SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
				$transient_pattern
			),
			ARRAY_A
		);

		// Reduce to simple array.
		$transients_names = wp_list_pluck( (array) $transients_names, 'option_name' );

		// Delete each via API (handles both object cache and DB).
		foreach ( $transients_names as $option_name ) {
			if ( ! is_string( $option_name ) || empty( $option_name ) ) {
				continue;
			}
			$transient_key = str_replace( '_transient_', '', $option_name );
			delete_transient( $transient_key );
		}
	}
}
