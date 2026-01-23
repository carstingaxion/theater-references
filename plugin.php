<?php
/**
 * Plugin Name:       GatherPress References
 * Description:       Display references such as clients, festivals and awards in a structured, chronological format.
 * Version:           0.1.0
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Requires Plugins:  gatherpress
 * Author:            caba & WordPress Telex
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gatherpress-references
 *
 * @package GatherPress_References
 */

namespace GatherPress\References;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register post type support for GatherPress References
 *
 * This function defines the configuration for which taxonomies should be
 * registered and associated with the gatherpress_event post type.
 *
 * Example configuration for theater productions:
 * - ref_tax: The main reference taxonomy (e.g., 'gatherpress-production' for theater productions)
 * - ref_types: Array of reference type taxonomies (e.g., clients, festivals, awards)
 *
 * The configuration is stored in post type support, making it easy to
 * query and extend by other plugins or themes.
 *
 * @since 0.1.0
 * @return void
 */
function register_post_type_support(): void {
	$config = array(
		'ref_tax'   => 'gatherpress-production',
		'ref_types' => array( '_gatherpress-client', '_gatherpress-festival', '_gatherpress-award' ),
	);
	
	add_post_type_support( 'gatherpress_event', 'gatherpress_references', $config );
}
add_action( 'registered_post_type_gatherpress_event', __NAMESPACE__ . '\register_post_type_support', 9 );

/**
 * GatherPress References Plugin
 *
 * Core singleton class that manages the GatherPress References block functionality.
 * Handles taxonomy management for post types with 'gatherpress_references' support 
 * and caching.
 *
 * Architecture:
 * - Singleton pattern ensures single instance throughout request lifecycle
 * - Cache management with automatic invalidation on content changes
 * - Custom taxonomy registration for post types with 'gatherpress_references' support
 *
 * @since 0.1.0
 */
class Plugin {
	/**
	 * Singleton instance
	 *
	 * @var Plugin|null
	 */
	private static ?Plugin $instance = null;

	/**
	 * Cache key prefix for transients
	 *
	 * Used to namespace all cache keys to avoid conflicts with other plugins.
	 *
	 * @var string
	 */
	private string $cache_prefix = 'gatherpress_refs_';

	/**
	 * Cache expiration time in seconds
	 *
	 * Default: 1 hour (3600 seconds)
	 * Automatically cleared on content/term changes.
	 * Can be modified via 'gatherpress_references_cache_expiration' filter.
	 *
	 * @var int
	 */
	private int $cache_expiration = 3600;

	/**
	 * Private constructor to enforce singleton pattern
	 *
	 * @since 0.1.0
	 */
	private function __construct() {
		$this->init_hooks();
		$this->apply_filters();
	}

	/**
	 * Get singleton instance
	 *
	 * Creates instance on first call, returns existing instance on subsequent calls.
	 *
	 * @since 0.1.0
	 * @return Plugin The singleton instance.
	 */
	public static function get_instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initialize WordPress hooks
	 *
	 * Registers all necessary WordPress actions and filters:
	 * - Taxonomy registration on 'init'
	 * - Block registration on 'init'
	 * - Cache invalidation on content changes
	 * - Admin menu for demo data generator
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function init_hooks(): void {
		// Core registration hooks.
		add_action( 'registered_post_type_gatherpress_event', array( $this, 'register_taxonomies' ) );
		add_action( 'registered_post_type_gatherpress_event', array( $this, 'register_block' ) );

		// Cache invalidation hooks - post status changes.
		add_action( 'transition_post_status', array( $this, 'clear_cache_on_status_change' ), 10, 3 );
		
		// Cache invalidation hooks - term changes.
		add_action( 'create_term', array( $this, 'clear_cache_on_term_change' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'clear_cache_on_term_change' ), 10, 3 );
		add_action( 'delete_term', array( $this, 'clear_cache_on_term_change' ), 10, 3 );
		
		// Cache invalidation hooks - term relationships.
		add_action( 'set_object_terms', array( $this, 'clear_cache_on_term_relationship' ), 10, 3 );
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
	 * Get all configurations from post types with 'gatherpress_references' support
	 *
	 * Retrieves configurations from all post types that have added support for
	 * 'gatherpress_references' via add_post_type_support().
	 *
	 * @since 0.1.0
	 * @return array<string, array{ref_tax: string, ref_types: array<int, string>}> Array of post_type => config.
	 */
	public function get_all_configs(): array {
		$post_types = get_post_types_by_support( 'gatherpress_references' );
		$configs    = array();
		if ( empty( $post_types ) ) {
			return $configs;
		}
		foreach ( $post_types as $post_type ) {
			$support = get_all_post_type_supports( $post_type );
			
			if ( isset( $support['gatherpress_references'] ) && is_array( $support['gatherpress_references'] ) ) {
				$config = $support['gatherpress_references'][0];
				
				// Validate config structure.
				if ( isset( $config['ref_tax'] ) && isset( $config['ref_types'] ) && is_array( $config['ref_types'] ) ) {
					$configs[ $post_type ] = array(
						'ref_tax'   => $config['ref_tax'],
						'ref_types' => $config['ref_types'],
					);
				}
			}
		}
		
		return $configs;
	}

	/**
	 * Get aggregated taxonomy list from all configs
	 *
	 * Collects all unique taxonomies (both ref_tax and ref_types) from all
	 * post types with 'gatherpress_references' support.
	 *
	 * @since 0.1.0
	 * @return array<int, string> Array of unique taxonomy slugs.
	 */
	public function get_all_taxonomies(): array {
		$configs    = $this->get_all_configs();
		$taxonomies = array();
		
		foreach ( $configs as $config ) {
			if ( ! empty( $config['ref_tax'] ) ) {
				$taxonomies[] = $config['ref_tax'];
			}
			if ( ! empty( $config['ref_types'] ) && is_array( $config['ref_types'] ) ) {
				$taxonomies = array_merge( $taxonomies, $config['ref_types'] );
			}
		}
		
		return array_unique( $taxonomies );
	}

	/**
	 * Check if block should be registered
	 *
	 * Block is only registered if:
	 * 1. At least one post type has 'gatherpress_references' support
	 * 2. At least one config has a valid ref_tax
	 * 3. At least one config has non-empty ref_types
	 *
	 * @since 0.1.0
	 * @return bool True if block should be registered.
	 */
	private function should_register_block(): bool {
		$configs = $this->get_all_configs();
		
		if ( empty( $configs ) ) {
			return false;
		}
		
		// Check if at least one config has both ref_tax and non-empty ref_types.
		foreach ( $configs as $config ) {
			if ( ! empty( $config['ref_tax'] ) && 
				! empty( $config['ref_types'] ) && 
				is_array( $config['ref_types'] ) ) {
				return true;
			}
		}
		
		return false;
	}

	/**
	 * Register all taxonomies based on configurations
	 *
	 * Iterates through all post types with 'gatherpress_references' support
	 * and registers their configured taxonomies if they don't already exist.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function register_taxonomies(): void {
		$configs = $this->get_all_configs();
		if ( empty( $configs ) ) {
			return;
		}
		
		foreach ( $configs as $post_type => $config ) {
			// Only register our production taxonomy if it is still present in the post type support config.
			if ( ! empty( $config['ref_tax'] ) && 
				$config['ref_tax'] === 'gatherpress-production' && 
				! taxonomy_exists( $config['ref_tax'] ) ) {
				$this->register_reference_taxonomy( $post_type );
			}
			
			// Register reference type taxonomies if configured and not already registered.
			if ( ! empty( $config['ref_types'] ) && is_array( $config['ref_types'] ) ) {
				foreach ( $config['ref_types'] as $ref_type ) {
					if ( taxonomy_exists( $ref_type ) ) {
						continue;
					}
					
					// Determine which registration function to use based on taxonomy slug.
					if ( $ref_type === '_gatherpress-client' ) {
						$this->register_clients_taxonomy( $post_type );
					} elseif ( $ref_type === '_gatherpress-festival' ) {
						$this->register_festivals_taxonomy( $post_type );
					} elseif ( $ref_type === '_gatherpress-award' ) {
						$this->register_awards_taxonomy( $post_type );
					}
				}
			}
		}
	}

	/**
	 * Register the reference taxonomy (productions in the theater use case)
	 *
	 * Hierarchical taxonomy (like categories) for organizing references.
	 * Allows events to be grouped by reference term and enables filtering.
	 * In the default theater setup, this is used for theater productions.
	 *
	 * Non-public but queryable - no frontend archives or permalinks.
	 *
	 * @since 0.1.0
	 * @param string $post_type     Post type to associate with.
	 * @return void
	 */
	private function register_reference_taxonomy( string $post_type ): void {
		$labels = array(
			'name'              => __( 'Productions', 'gatherpress-references' ),
			'singular_name'     => __( 'Production', 'gatherpress-references' ),
			'search_items'      => __( 'Search Productions', 'gatherpress-references' ),
			'all_items'         => __( 'All Productions', 'gatherpress-references' ),
			'parent_item'       => __( 'Parent Production', 'gatherpress-references' ),
			'parent_item_colon' => __( 'Parent Production:', 'gatherpress-references' ),
			'edit_item'         => __( 'Edit Production', 'gatherpress-references' ),
			'update_item'       => __( 'Update Production', 'gatherpress-references' ),
			'add_new_item'      => __( 'Add New Production', 'gatherpress-references' ),
			'new_item_name'     => __( 'New Production Name', 'gatherpress-references' ),
			'menu_name'         => __( 'Productions', 'gatherpress-references' ),
		);

		$args = array(
			'labels'             => $labels,
			'hierarchical'       => true,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'query_var'          => true,
			'rewrite'            => false,
			'show_in_rest'       => true,
		);

		register_taxonomy( 'gatherpress-production', array( $post_type ), $args );
	}

	/**
	 * Register the clients taxonomy
	 *
	 * Flat taxonomy (like tags) for clients.
	 * Non-hierarchical for quick tagging of client relationships.
	 *
	 * Non-public but queryable - no frontend archives or permalinks.
	 *
	 * @since 0.1.0
	 * @param string $post_type Post type to associate with.
	 * @return void
	 */
	private function register_clients_taxonomy( string $post_type ): void {
		$labels = array(
			'name'          => __( 'Clients', 'gatherpress-references' ),
			'singular_name' => __( 'Client', 'gatherpress-references' ),
			'search_items'  => __( 'Search Clients', 'gatherpress-references' ),
			'all_items'     => __( 'All Clients', 'gatherpress-references' ),
			'edit_item'     => __( 'Edit Client', 'gatherpress-references' ),
			'update_item'   => __( 'Update Client', 'gatherpress-references' ),
			'add_new_item'  => __( 'Add New Client', 'gatherpress-references' ),
			'new_item_name' => __( 'New Client Name', 'gatherpress-references' ),
			'menu_name'     => __( 'Clients', 'gatherpress-references' ),
		);

		$args = array(
			'labels'             => $labels,
			'hierarchical'       => false,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'query_var'          => true,
			'rewrite'            => false,
			'show_in_rest'       => true,
		);

		register_taxonomy( '_gatherpress-client', array( $post_type ), $args );
	}

	/**
	 * Register the festivals taxonomy
	 *
	 * Flat taxonomy for festival participations.
	 * Allows tracking of festival appearances.
	 *
	 * Non-public but queryable - no frontend archives or permalinks.
	 *
	 * @since 0.1.0
	 * @param string $post_type Post type to associate with.
	 * @return void
	 */
	private function register_festivals_taxonomy( string $post_type ): void {
		$labels = array(
			'name'          => __( 'Festivals', 'gatherpress-references' ),
			'singular_name' => __( 'Festival', 'gatherpress-references' ),
			'search_items'  => __( 'Search Festivals', 'gatherpress-references' ),
			'all_items'     => __( 'All Festivals', 'gatherpress-references' ),
			'edit_item'     => __( 'Edit Festival', 'gatherpress-references' ),
			'update_item'   => __( 'Update Festival', 'gatherpress-references' ),
			'add_new_item'  => __( 'Add New Festival', 'gatherpress-references' ),
			'new_item_name' => __( 'New Festival Name', 'gatherpress-references' ),
			'menu_name'     => __( 'Festivals', 'gatherpress-references' ),
		);

		$args = array(
			'labels'             => $labels,
			'hierarchical'       => false,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'query_var'          => true,
			'rewrite'            => false,
			'show_in_rest'       => true,
		);

		register_taxonomy( '_gatherpress-festival', array( $post_type ), $args );
	}

	/**
	 * Register the awards taxonomy
	 *
	 * Flat taxonomy for awards received.
	 * Enables tracking and display of achievements.
	 *
	 * Non-public but queryable - no frontend archives or permalinks.
	 *
	 * @since 0.1.0
	 * @param string $post_type Post type to associate with.
	 * @return void
	 */
	private function register_awards_taxonomy( string $post_type ): void {
		$labels = array(
			'name'          => __( 'Awards', 'gatherpress-references' ),
			'singular_name' => __( 'Award', 'gatherpress-references' ),
			'search_items'  => __( 'Search Awards', 'gatherpress-references' ),
			'all_items'     => __( 'All Awards', 'gatherpress-references' ),
			'edit_item'     => __( 'Edit Award', 'gatherpress-references' ),
			'update_item'   => __( 'Update Award', 'gatherpress-references' ),
			'add_new_item'  => __( 'Add New Award', 'gatherpress-references' ),
			'new_item_name' => __( 'New Award Name', 'gatherpress-references' ),
			'menu_name'     => __( 'Awards', 'gatherpress-references' ),
		);

		$args = array(
			'labels'             => $labels,
			'hierarchical'       => false,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'query_var'          => true,
			'rewrite'            => false,
			'show_in_rest'       => true,
		);

		register_taxonomy( '_gatherpress-award', array( $post_type ), $args );
	}

	/**
	 * Register the GatherPress References block
	 *
	 * Only registers the block if:
	 * 1. At least one post type has 'gatherpress_references' support
	 * 2. At least one config has a valid ref_tax
	 * 3. At least one config has non-empty ref_types
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function register_block(): void {
		if ( ! $this->should_register_block() ) {
			return;
		}
		
		register_block_type( __DIR__ . '/build/' );
	}

	/**
	 * Clear cache when event post status changes to or from 'publish'.
	 *
	 * Hooked to 'transition_post_status' action. This is more precise than
	 * 'save_post' as it only triggers when the post status actually changes.
	 * Clears all reference caches whenever a supported post type is published
	 * or unpublished.
	 *
	 * Examples of status transitions that trigger cache clearing:
	 * - draft → publish (new event published)
	 * - publish → draft (event unpublished)
	 * - publish → trash (event deleted)
	 * - trash → publish (event restored)
	 * - pending → publish (event approved)
	 *
	 * @since 0.1.0
	 *
	 * @param string   $new_status New post status.
	 * @param string   $old_status Old post status.
	 * @param \WP_Post $post       Post object.
	 * @return void
	 */
	public function clear_cache_on_status_change( string $new_status, string $old_status, $post ): void {
		if ( ! is_object( $post ) || ! isset( $post->post_type ) ) {
			return;
		}
		
		if ( ! post_type_supports( $post->post_type, 'gatherpress_references' ) ) {
			return;
		}
		
		// Only clear cache if status is changing to or from 'publish'.
		// This catches: publish→anything or anything→publish.
		if ( 'publish' === $new_status || 'publish' === $old_status ) {
			// Only clear if status actually changed.
			if ( $new_status !== $old_status ) {
				$this->clear_all_caches();
			}
		}
	}

	/**
	 * Clear cache when taxonomy terms are modified.
	 *
	 * Hooked to term creation, editing, and deletion actions. Clears all reference
	 * caches when terms in any supported taxonomy are changed.
	 *
	 * This function respects the configuration - if a taxonomy is not in any
	 * post type's configuration, changes to its terms won't trigger cache clearing.
	 *
	 * @since 0.1.0
	 *
	 * @param int    $term_id  Term ID.
	 * @param int    $tt_id    Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 * @return void
	 */
	public function clear_cache_on_term_change( int $term_id, int $tt_id, string $taxonomy ): void {
		$all_taxonomies = $this->get_all_taxonomies();
		
		if ( in_array( $taxonomy, $all_taxonomies, true ) ) {
			$this->clear_all_caches();
		}
	}

	/**
	 * Clear cache when term relationships change.
	 *
	 * Hooked to 'set_object_terms' action. Clears all reference caches when
	 * taxonomy terms are assigned to or removed from posts of supported post types.
	 *
	 * @since 0.1.0
	 *
	 * @param int             $object_id Object ID (post ID).
	 * @param array<int, int> $terms     An array of term IDs.
	 * @param array<int, int> $tt_ids    An array of term taxonomy IDs.
	 * @return void
	 */
	public function clear_cache_on_term_relationship( int $object_id, array $terms, array $tt_ids ): void {
		// Only proceed if terms were assigned to a supported post.
		if ( $this->is_supported_post( $object_id ) ) {
			$this->clear_all_caches();
		}
	}

	/**
	 * Check if a specific post is supported for references.
	 *
	 * A post is considered supported if its post type supports
	 * 'gatherpress_references' and its status is 'publish'.
	 *
	 * @since 0.1.0
	 *
	 * @param int $post_id Post ID to check.
	 * @return bool True if supported, false otherwise.
	 */
	private function is_supported_post( int $post_id ): bool {
		$post = get_post( $post_id );
		
		if ( ! $post ) {
			return false;
		}
		
		return post_type_supports( $post->post_type, 'gatherpress_references' ) 
			&& $post->post_status === 'publish';
	}

	/**
	 * Clear all cached references
	 *
	 * Removes all transients with the "gatherpress_refs_" cache prefix from the database.
	 * Uses direct database queries for efficiency.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function clear_all_caches(): void {
		global $wpdb;

		if ( ! $wpdb instanceof \wpdb ) {
			return;
		}

		// Prepare the LIKE patterns for deletion.
		$transient_pattern = $wpdb->esc_like( '_transient_' . $this->cache_prefix ) . '%';
		$timeout_pattern   = $wpdb->esc_like( '_transient_timeout_' . $this->cache_prefix ) . '%';

		// Prepare SQL statement.
		$table = $wpdb->options;
		$wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->prepare(
				"DELETE FROM {$table} WHERE option_name LIKE %s OR option_name LIKE %s",
				$transient_pattern,
				$timeout_pattern
			)
		);
	}
}


/**
 * Plugin activation hook
 *
 * Performs setup tasks when the plugin is activated.
 *
 * @since 0.1.0
 * @return void
 */
function gatherpress_references_activate(): void {
	// Wait for instructions ....
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\gatherpress_references_activate' );

/**
 * Plugin deactivation hook
 *
 * Performs cleanup tasks when the plugin is deactivated:
 * - Clears all cached reference data
 *
 * Note: Does NOT delete taxonomies, terms, or post associations.
 * Use the uninstall hook for complete data removal.
 *
 * @since 0.1.0
 * @return void
 */
function gatherpress_references_deactivate(): void {
	Plugin::get_instance()->clear_all_caches();
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\gatherpress_references_deactivate' );

/**
 * Plugin uninstall hook
 *
 * Completely removes all plugin data when WordPress uninstalls the plugin.
 * This function is registered with register_uninstall_hook() and is called
 * only when a user explicitly uninstalls the plugin from the WordPress admin.
 *
 * Removes:
 * - All taxonomy terms and term relationships
 * - All cached transient data
 * - Term meta for demo data markers
 *
 * Note: Does NOT delete:
 * - GatherPress events (they remain but lose term associations)
 * - Taxonomy registrations (these are removed when plugin files are deleted)
 *
 * @since 0.1.0
 * @global \wpdb $wpdb WordPress database abstraction object.
 * @return void
 */
function gatherpress_references_uninstall(): void {
	global $wpdb;

	if ( ! $wpdb instanceof \wpdb ) {
		return;
	}

	// Get all post types with 'gatherpress_references' support.
	$post_types = get_post_types_by_support( 'gatherpress_references' );
	$taxonomies = array();
	
	foreach ( $post_types as $post_type ) {
		$config = get_all_post_type_supports( $post_type );
		
		if ( isset( $config['gatherpress_references'] ) && is_array( $config['gatherpress_references'] ) ) {
			$ref_config = $config['gatherpress_references'][0];
			
			if ( ! empty( $ref_config['ref_tax'] ) ) {
				$taxonomies[] = $ref_config['ref_tax'];
			}
			if ( ! empty( $ref_config['ref_types'] ) && is_array( $ref_config['ref_types'] ) ) {
				$taxonomies = array_merge( $taxonomies, $ref_config['ref_types'] );
			}
		}
	}
	
	$taxonomies = array_unique( $taxonomies );
	// 1. Remove all terms and term relationships.
	foreach ( $taxonomies as $taxonomy ) {
		// Get all terms for this taxonomy.
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'fields'     => 'ids',
			)
		);

		// Delete term and all its relationships.
		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term_id ) {
				wp_delete_term( $term_id, $taxonomy );
			}
		}

		// Clean up term taxonomy table (belt and suspenders approach).
		$wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->prepare(
				"DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy = %s",
				$taxonomy
			)
		);
	}

	// 2. Clear all cached transients.
	Plugin::get_instance()->clear_all_caches();
}
register_uninstall_hook( __FILE__, __NAMESPACE__ . '\gatherpress_references_uninstall' );


// Initialize the singleton instance.
Plugin::get_instance();