<?php
/**
 * Unit tests for Config_Manager class.
 *
 * @package GatherPress_References
 */

// namespace GatherPress\References\Tests\Unit;

use GatherPress\References\Config_Manager;
// use WP_UnitTestCase;

/**
 * Class ConfigManagerTest
 *
 * Tests the Config_Manager class.
 *
 * @since 0.1.0
 */
class ConfigManagerTest extends WP_UnitTestCase {

	/**
	 * Config manager instance.
	 *
	 * @var Config_Manager
	 */
	private Config_Manager $config_manager;

	/**
	 * Set up the test.
	 */
	public function set_up() {
		parent::set_up();
		$this->config_manager = new Config_Manager();
	}

	/**
	 * Test that get_config returns null for unsupported post type.
	 */
	public function test_get_config_returns_null_for_unsupported_post_type() {
		$result = $this->config_manager->get_config( 'nonexistent_type' );

		$this->assertNull( $result );
	}

	/**
	 * Test that get_all_configs returns an array.
	 */
	public function test_get_all_configs_returns_array() {
		$result = $this->config_manager->get_all_configs();

		$this->assertIsArray( $result );
	}

	/**
	 * Test that get_all_taxonomies returns an array.
	 */
	public function test_get_all_taxonomies_returns_array() {
		$result = $this->config_manager->get_all_taxonomies();

		$this->assertIsArray( $result );
	}

	/**
	 * Test that should_register_block returns boolean.
	 */
	public function test_should_register_block_returns_boolean() {
		$result = $this->config_manager->should_register_block();

		$this->assertIsBool( $result );
	}

	/**
	 * Test get_config returns null for post type without references support.
	 */
	public function test_get_config_returns_null_for_post_without_support() {
		$result = $this->config_manager->get_config( 'post' );

		$this->assertNull( $result );
	}

	/**
	 * Test get_config returns valid structure for supported post type.
	 */
	public function test_get_config_returns_valid_structure_for_supported_type() {
		if ( ! post_type_exists( 'gatherpress_event' ) ) {
			$this->markTestSkipped( 'GatherPress event post type not registered.' );
		}

		$result = $this->config_manager->get_config( 'gatherpress_event' );

		$this->assertNotNull( $result );
		$this->assertArrayHasKey( 'ref_tax', $result );
		$this->assertArrayHasKey( 'ref_types', $result );
		$this->assertIsString( $result['ref_tax'] );
		$this->assertIsArray( $result['ref_types'] );
	}

	/**
	 * Test get_all_taxonomies returns unique values.
	 */
	public function test_get_all_taxonomies_returns_unique_values() {
		$result = $this->config_manager->get_all_taxonomies();

		$this->assertEquals( count( $result ), count( array_unique( $result ) ) );
	}
}
