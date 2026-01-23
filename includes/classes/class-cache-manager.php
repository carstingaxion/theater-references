<?php

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
		$this->apply_filters();
	}

	/**
	 * Apply filterable properties
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function apply_filters(): void {
		$this->cache_expiration = apply_filters( 'gatherpress_references_cache_expiration', $this->cache_expiration );
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
		return $this->cache_prefix . md5( maybe_serialize( array( $post_type, $ref_term_id, $year, $type ) ) );
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
		
		if ( false !== $cached && is_array( $cached ) && ! empty( $cached ) ) {
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
		$timeout_pattern   = $wpdb->esc_like( '_transient_timeout_' . $this->cache_prefix ) . '%';

		$table = $wpdb->options;
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$table} WHERE option_name LIKE %s OR option_name LIKE %s",
				$transient_pattern,
				$timeout_pattern
			)
		);
	}
}