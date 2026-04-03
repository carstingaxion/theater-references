<?php
/**
 * Integration tests for Block_Renderer class.
 *
 * @package GatherPress_References
 */

namespace GatherPress\References\Tests\Integration;

use GatherPress\References\Block_Renderer;
use WP_UnitTestCase;

/**
 * Class BlockRendererTest
 *
 * Tests the Block_Renderer class within the WordPress environment.
 *
 * @since 0.1.0
 */
class BlockRendererTest extends WP_UnitTestCase {

	/**
	 * Set up the test.
	 */
	public function set_up() {
		parent::set_up();

		// Ensure the render.php is loaded so Block_Renderer is available.
		$render_file = dirname( __DIR__, 3 ) . '/src/render.php';
		if ( file_exists( $render_file ) && ! class_exists( Block_Renderer::class ) ) {
			require_once $render_file; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
		}
	}

	/**
	 * Test that Block_Renderer can be instantiated.
	 */
	public function test_renderer_instance_exists() {
		if ( ! class_exists( Block_Renderer::class ) ) {
			$this->markTestSkipped( 'Block_Renderer class not available (render.php may not be loaded).' );
		}

		$renderer = Block_Renderer::get_instance();
		$this->assertInstanceOf( Block_Renderer::class, $renderer );
	}

	/**
	 * Test that singleton returns same instance.
	 */
	public function test_renderer_singleton() {
		if ( ! class_exists( Block_Renderer::class ) ) {
			$this->markTestSkipped( 'Block_Renderer class not available.' );
		}

		$instance1 = Block_Renderer::get_instance();
		$instance2 = Block_Renderer::get_instance();

		$this->assertSame( $instance1, $instance2 );
	}

	/**
	 * Test that render returns empty string with invalid post type.
	 */
	public function test_render_returns_empty_for_invalid_post_type() {
		if ( ! class_exists( Block_Renderer::class ) ) {
			$this->markTestSkipped( 'Block_Renderer class not available.' );
		}

		$renderer = Block_Renderer::get_instance();
		$result   = $renderer->render(
			array(
				'postType' => 'nonexistent_post_type',
			)
		);

		$this->assertEmpty( $result );
	}

	/**
	 * Test that render returns empty string with empty attributes.
	 */
	public function test_render_returns_empty_for_empty_attributes() {
		if ( ! class_exists( Block_Renderer::class ) ) {
			$this->markTestSkipped( 'Block_Renderer class not available.' );
		}

		$renderer = Block_Renderer::get_instance();
		$result   = $renderer->render( array() );

		$this->assertEmpty( $result );
	}

	/**
	 * Test that render returns empty string when no data matches.
	 */
	public function test_render_returns_empty_when_no_matching_data() {
		if ( ! class_exists( Block_Renderer::class ) ) {
			$this->markTestSkipped( 'Block_Renderer class not available.' );
		}

		if ( ! post_type_exists( 'gatherpress_event' ) ) {
			$this->markTestSkipped( 'GatherPress event post type not registered.' );
		}

		$renderer = Block_Renderer::get_instance();
		$result   = $renderer->render(
			array(
				'postType'      => 'gatherpress_event',
				'refTermId'     => 99999,
				'year'          => 1900,
				'referenceType' => 'all',
			)
		);

		$this->assertEmpty( $result );
	}
}
