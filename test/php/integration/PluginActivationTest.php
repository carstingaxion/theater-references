<?php
/**
 * Integration tests for plugin activation and initialization.
 *
 * @package GatherPress_References
 */

namespace GatherPress\References\Tests\Integration;

use GatherPress\References\Plugin;
use WP_UnitTestCase;

/**
 * Class PluginActivationTest
 *
 * Tests plugin activation and core initialization within WordPress.
 *
 * @since 0.1.0
 */
class PluginActivationTest extends WP_UnitTestCase {

	/**
	 * Test that the plugin singleton can be instantiated.
	 */
	public function test_plugin_instance_exists() {
		$plugin = Plugin::get_instance();

		$this->assertInstanceOf( Plugin::class, $plugin );
	}

	/**
	 * Test that singleton always returns the same instance.
	 */
	public function test_plugin_singleton_returns_same_instance() {
		$instance1 = Plugin::get_instance();
		$instance2 = Plugin::get_instance();

		$this->assertSame( $instance1, $instance2 );
	}

	/**
	 * Test that cache manager is accessible.
	 */
	public function test_cache_manager_accessible() {
		$plugin = Plugin::get_instance();
		$cache  = $plugin->get_cache_manager();

		$this->assertInstanceOf( \GatherPress\References\Cache_Manager::class, $cache );
	}

	/**
	 * Test that query builder is accessible.
	 */
	public function test_query_builder_accessible() {
		$plugin  = Plugin::get_instance();
		$builder = $plugin->get_query_builder();

		$this->assertInstanceOf( \GatherPress\References\Query_Builder::class, $builder );
	}

	/**
	 * Test that data organizer is accessible.
	 */
	public function test_data_organizer_accessible() {
		$plugin    = Plugin::get_instance();
		$organizer = $plugin->get_data_organizer();

		$this->assertInstanceOf( \GatherPress\References\Data_Organizer::class, $organizer );
	}
}
