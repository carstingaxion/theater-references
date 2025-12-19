<?php
/**
 * Plugin Name:       GatherPress References
 * Description:       Display production references including clients, festivals, and awards in a structured, chronological format.
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
 * GatherPress References Plugin
 *
 * Core singleton class that manages the GatherPress References block functionality.
 * Handles taxonomy management for GatherPress events, caching, and demo data generation.
 *
 * Architecture:
 * - Singleton pattern ensures single instance throughout request lifecycle
 * - Cache management with automatic invalidation on content changes
 * - Custom taxonomy registration for GatherPress events
 * - Demo data generator for development and testing
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
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'register_block' ) );

		// Cache invalidation hooks.
		add_action( 'save_post', array( $this, 'clear_cache_on_post_save' ) );
		add_action( 'delete_post', array( $this, 'clear_cache_on_post_delete' ) );
		add_action( 'edited_term', array( $this, 'clear_cache_on_term_change' ), 10, 3 );
		add_action( 'delete_term', array( $this, 'clear_cache_on_term_change' ), 10, 3 );

		// Admin interface hooks.
		add_action( 'admin_menu', array( $this, 'add_demo_data_menu' ) );
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
	 * Register all taxonomies
	 *
	 * Orchestrates registration of all custom taxonomies for GatherPress events:
	 * - gatherpress-productions: Hierarchical taxonomy for productions
	 * - _gatherpress-client: Flat taxonomy for clients
	 * - _gatherpress-festival: Flat taxonomy for festival participations
	 * - _gatherpress-award: Flat taxonomy for awards received
	 *
	 * All taxonomies are non-public but queryable - they don't have frontend
	 * archives or permalinks, but can be used in WP_Query.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function register_taxonomies(): void {
		$this->register_productions_taxonomy();
		$this->register_clients_taxonomy();
		$this->register_festivals_taxonomy();
		$this->register_awards_taxonomy();
	}

	/**
	 * Register the 'gatherpress-productions' taxonomy
	 *
	 * Hierarchical taxonomy (like categories) for theater productions.
	 * Allows GatherPress events to be grouped by production and enables filtering.
	 *
	 * Non-public but queryable - no frontend archives or permalinks.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function register_productions_taxonomy(): void {
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

		// Register for GatherPress event post type.
		register_taxonomy( 'gatherpress-productions', array( 'gatherpress_event' ), $args );
	}

	/**
	 * Register the '_gatherpress-client' taxonomy
	 *
	 * Flat taxonomy (like tags) for clients.
	 * Non-hierarchical for quick tagging of client relationships.
	 *
	 * Non-public but queryable - no frontend archives or permalinks.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function register_clients_taxonomy(): void {
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

		register_taxonomy( '_gatherpress-client', array( 'gatherpress_event' ), $args );
	}

	/**
	 * Register the '_gatherpress-festival' taxonomy
	 *
	 * Flat taxonomy for festival participations.
	 * Allows tracking of festival appearances across GatherPress events.
	 *
	 * Non-public but queryable - no frontend archives or permalinks.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function register_festivals_taxonomy(): void {
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

		register_taxonomy( '_gatherpress-festival', array( 'gatherpress_event' ), $args );
	}

	/**
	 * Register the '_gatherpress-award' taxonomy
	 *
	 * Flat taxonomy for awards received.
	 * Enables tracking and display of achievements.
	 *
	 * Non-public but queryable - no frontend archives or permalinks.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function register_awards_taxonomy(): void {
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

		register_taxonomy( '_gatherpress-award', array( 'gatherpress_event' ), $args );
	}

	/**
	 * Register the GatherPress References block
	 *
	 * Registers the block type from the compiled build directory.
	 * Uses block.json for metadata (requires WordPress 5.8+).
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function register_block(): void {
		register_block_type( __DIR__ . '/build/' );
	}

	/**
	 * Clear cache when a GatherPress event is saved
	 *
	 * Hooked to 'save_post' action to invalidate cache when event content changes.
	 *
	 * @since 0.1.0
	 * @param int $post_id The post ID being saved.
	 * @return void
	 */
	public function clear_cache_on_post_save( int $post_id ): void {
		// Only clear cache for GatherPress events.
		if ( get_post_type( $post_id ) === 'gatherpress_event' ) {
			$this->clear_all_caches();
		}
	}

	/**
	 * Clear cache when a GatherPress event is deleted
	 *
	 * Hooked to 'delete_post' action to invalidate cache when event is removed.
	 *
	 * @since 0.1.0
	 * @param int $post_id The post ID being deleted.
	 * @return void
	 */
	public function clear_cache_on_post_delete( int $post_id ): void {
		if ( get_post_type( $post_id ) === 'gatherpress_event' ) {
			$this->clear_all_caches();
		}
	}

	/**
	 * Clear cache when a taxonomy term is changed or deleted
	 *
	 * Hooked to 'edited_term' and 'delete_term' actions.
	 * Only clears cache for our custom taxonomies.
	 *
	 * @since 0.1.0
	 * @param int    $term_id  The term ID.
	 * @param int    $tt_id    The term taxonomy ID.
	 * @param string $taxonomy The taxonomy slug.
	 * @return void
	 */
	public function clear_cache_on_term_change( int $term_id, int $tt_id, string $taxonomy ): void {
		// List of taxonomies that require cache invalidation.
		$ref_taxonomies = array( 'gatherpress-productions', '_gatherpress-client', '_gatherpress-festival', '_gatherpress-award' );
		
		if ( in_array( $taxonomy, $ref_taxonomies, true ) ) {
			$this->clear_all_caches();
		}
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

	/**
	 * Add demo data submenu page
	 *
	 * Creates an admin page under GatherPress Events menu for generating test data.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function add_demo_data_menu(): void {
		add_submenu_page(
			'edit.php?post_type=gatherpress_event',
			__( 'Generate Demo Data', 'gatherpress-references' ),
			__( 'Demo Data', 'gatherpress-references' ),
			'manage_options',
			'gatherpress-references-demo-data',
			array( $this, 'render_demo_data_page' )
		);
	}

	/**
	 * Render demo data admin page
	 *
	 * Displays interface for generating and deleting demo content.
	 * Includes nonce verification for security.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function render_demo_data_page(): void {
		// Security check.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Handle demo data generation.
		if ( isset( $_POST['generate_demo_data'] ) && check_admin_referer( 'gatherpress_references_demo_data' ) ) {
			$this->generate_demo_data();
			echo '<div class="notice notice-success"><p>' . esc_html__( 'Demo data generated successfully!', 'gatherpress-references' ) . '</p></div>';
		}

		// Handle demo data deletion.
		if ( isset( $_POST['delete_demo_data'] ) && check_admin_referer( 'gatherpress_references_demo_data' ) ) {
			$this->delete_demo_data();
			echo '<div class="notice notice-success"><p>' . esc_html__( 'Demo data deleted successfully!', 'gatherpress-references' ) . '</p></div>';
		}

		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'References Demo Data', 'gatherpress-references' ); ?></h1>
			<p><?php esc_html_e( 'Generate sample GatherPress events with productions and references for development and testing.', 'gatherpress-references' ); ?></p>
			
			<div style="background: #fff; padding: 20px; margin: 20px 0; border: 1px solid #ccc;">
				<h2><?php esc_html_e( 'Generate Demo Data', 'gatherpress-references' ); ?></h2>
				<p><?php esc_html_e( 'This will create:', 'gatherpress-references' ); ?></p>
				<ul style="list-style: disc; margin-left: 20px;">
					<li><?php esc_html_e( '5  productions', 'gatherpress-references' ); ?></li>
					<li><?php esc_html_e( '20 GatherPress event posts', 'gatherpress-references' ); ?></li>
					<li><?php esc_html_e( '8 client terms', 'gatherpress-references' ); ?></li>
					<li><?php esc_html_e( '6 festival terms', 'gatherpress-references' ); ?></li>
					<li><?php esc_html_e( '6 award terms', 'gatherpress-references' ); ?></li>
				</ul>
				<form method="post" style="margin-top: 20px;">
					<?php wp_nonce_field( 'gatherpress_references_demo_data' ); ?>
					<button type="submit" name="generate_demo_data" class="button button-primary">
						<?php esc_html_e( 'Generate Demo Data', 'gatherpress-references' ); ?>
					</button>
				</form>
			</div>

			<div style="background: #fff; padding: 20px; margin: 20px 0; border: 1px solid #ccc;">
				<h2><?php esc_html_e( 'Delete Demo Data', 'gatherpress-references' ); ?></h2>
				<p><?php esc_html_e( 'This will remove all demo GatherPress events and terms created by this tool.', 'gatherpress-references' ); ?></p>
				<form method="post" style="margin-top: 20px;">
					<?php wp_nonce_field( 'gatherpress_references_demo_data' ); ?>
					<button type="submit" name="delete_demo_data" class="button button-secondary" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete all demo data?', 'gatherpress-references' ); ?>')">
						<?php esc_html_e( 'Delete Demo Data', 'gatherpress-references' ); ?>
					</button>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Generate demo data
	 *
	 * Creates sample GatherPress events and taxonomy terms for testing:
	 * - 5 production terms
	 * - 8 client terms
	 * - 6 festival terms
	 * - 6 award terms
	 * - 20 GatherPress event posts with random term assignments
	 *
	 * All demo items are marked with '_demo_data' meta for easy cleanup.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function generate_demo_data(): void {
		// Sample production names.
		$productions = array( 'Hamlet', 'Romeo and Juliet', 'A Midsummer Night\'s Dream', 'Macbeth', 'The Tempest' );
		
		// Sample client names from major theater cities.
		$clients = array(
			'Royal Theater London',
			'Berlin Staatstheater',
			'Paris National Opera',
			'Vienna Burgtheater',
			'Moscow Art Theatre',
			'Sydney Opera House',
			'New York Broadway Theater',
			'Madrid Teatro Real',
		);
		
		// Sample festival names from renowned international festivals.
		$festivals = array(
			'Edinburgh International Festival',
			'Avignon Festival',
			'Salzburg Festival',
			'Venice Biennale Teatro',
			'Festival d\'Automne à Paris',
			'Berlin Theatertreffen',
		);
		
		// Sample award names.
		$awards = array(
			'Best Director Award',
			'Outstanding Production',
			'Best Ensemble Performance',
			'Critics\' Choice Award',
			'Theatre Excellence Prize',
			'Innovation in Theatre Award',
		);

		// Create production terms and store IDs.
		$production_ids = array();
		foreach ( $productions as $production ) {
			$term = wp_insert_term( $production, 'gatherpress-productions' );
			if ( ! is_wp_error( $term ) ) {
				$production_ids[] = $term['term_id'];
				// Mark as demo data for cleanup.
				update_term_meta( $term['term_id'], '_demo_data', '1' );
			}
		}

		// Create client terms.
		$client_ids = array();
		foreach ( $clients as $client ) {
			$term = wp_insert_term( $client, '_gatherpress-client' );
			if ( ! is_wp_error( $term ) ) {
				$client_ids[] = $term['term_id'];
				update_term_meta( $term['term_id'], '_demo_data', '1' );
			}
		}

		// Create festival terms.
		$festival_ids = array();
		foreach ( $festivals as $festival ) {
			$term = wp_insert_term( $festival, '_gatherpress-festival' );
			if ( ! is_wp_error( $term ) ) {
				$festival_ids[] = $term['term_id'];
				update_term_meta( $term['term_id'], '_demo_data', '1' );
			}
		}

		// Create award terms.
		$award_ids = array();
		foreach ( $awards as $award ) {
			$term = wp_insert_term( $award, '_gatherpress-award' );
			if ( ! is_wp_error( $term ) ) {
				$award_ids[] = $term['term_id'];
				update_term_meta( $term['term_id'], '_demo_data', '1' );
			}
		}

		// Generate 20 GatherPress event posts with realistic data.
		for ( $i = 0; $i < 20; $i++ ) {
			// Generate random date between 2018-2024.
			$year       = wp_rand( 2018, 2024 );
			$month      = wp_rand( 1, 12 );
			$day        = wp_rand( 1, 28 );
			$date       = sprintf( '%04d-%02d-%02d', $year, $month, $day );
			$production = $productions[ array_rand( $productions ) ];

			$event_data = array(
				'post_title'   => $production . ' - Event ' . ( $i + 1 ),
				'post_content' => 'Demo GatherPress event for ' . $production . '.',
				'post_status'  => 'publish',
				'post_type'    => 'gatherpress_event',
				'post_date'    => $date . ' 19:00:00',
			);

			$post_id = wp_insert_post( $event_data, true );

			if ( ! is_wp_error( $post_id ) ) {
				// Mark as demo data.
				update_post_meta( $post_id, '_demo_data', '1' );

				// Assign production term by term_id, not as array.
				if ( ! empty( $production_ids ) ) {
					$selected_production = $production_ids[ array_rand( $production_ids ) ];
					wp_set_object_terms( $post_id, $selected_production, 'gatherpress-productions', false );
				}

				// Use randomization to create varied reference patterns.
				$rand = wp_rand( 1, 100 );

				// 60% chance of having client references (1-2 clients).
				if ( $rand < 60 && ! empty( $client_ids ) ) {
					// Randomly select 1 or 2 clients.
					$num_clients      = wp_rand( 1, 2 );
					$selected_clients = array();
					for ( $v = 0; $v < $num_clients; $v++ ) {
						$selected_clients[] = $client_ids[ array_rand( $client_ids ) ];
					}
					// Remove duplicates.
					$selected_clients = array_unique( $selected_clients );
					wp_set_object_terms( $post_id, $selected_clients, '_gatherpress-client', false );
				}

				// 40% chance of festival participation (30-70 range).
				if ( $rand > 30 && $rand < 70 && ! empty( $festival_ids ) ) {
					$selected_festival = $festival_ids[ array_rand( $festival_ids ) ];
					wp_set_object_terms( $post_id, $selected_festival, '_gatherpress-festival', false );
				}

				// 40% chance of award (60-100 range).
				if ( $rand > 60 && ! empty( $award_ids ) ) {
					$selected_award = $award_ids[ array_rand( $award_ids ) ];
					wp_set_object_terms( $post_id, $selected_award, '_gatherpress-award', false );
				}
			}
		}

		// Clear cache after generating demo data.
		$this->clear_all_caches();
	}

	/**
	 * Delete demo data
	 *
	 * Removes all GatherPress events and terms marked with '_demo_data' meta.
	 * Uses permanent deletion (bypass trash).
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function delete_demo_data(): void {
		// Find all demo GatherPress event posts.
		$demo_events = get_posts(
			array(
				'post_type'      => 'gatherpress_event',
				'posts_per_page' => -1,
				'meta_key'       => '_demo_data', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);

		// Permanently delete demo events.
		foreach ( $demo_events as $event ) {
			wp_delete_post( $event->ID, true );
		}

		// Delete demo terms from all taxonomies.
		$taxonomies = array( 'gatherpress-productions', '_gatherpress-client', '_gatherpress-festival', '_gatherpress-award' );
		foreach ( $taxonomies as $taxonomy ) {
			$demo_terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
					'fields'     => 'ids', // The return type of get_terms() varies depending on the value passed to $args['fields']. See WP_Term_Query::get_terms() for details.
					'meta_key'   => '_demo_data', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
					'meta_value' => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				)
			);

			if ( ! is_wp_error( $demo_terms ) ) {
				foreach ( $demo_terms as $term_id ) {
					wp_delete_term( $term_id, $taxonomy );
				}
			}
		}

		// Clear cache after cleanup.
		$this->clear_all_caches();
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
 * Use the uninstall.php file for complete data removal.
 *
 * @since 0.1.0
 * @return void
 */
function gatherpress_references_deactivate(): void {
	// Clear all cached reference data.
	Plugin::get_instance()->clear_all_caches();
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\gatherpress_references_deactivate' );


// Initialize the singleton instance.
Plugin::get_instance();