<?php
/**
 * Integration tests for Query_Builder class.
 *
 * @package GatherPress_References
 */

namespace GatherPress\References\Tests\Integration;

use GatherPress\References\Query_Builder;
use GatherPress\References\Config_Manager;
use WP_UnitTestCase;

/**
 * Class QueryBuilderTest
 *
 * Tests the Query_Builder class within the WordPress environment.
 *
 * @since 0.1.0
 */
class QueryBuilderTest extends WP_UnitTestCase {

	/**
	 * Query builder instance.
	 *
	 * @var Query_Builder
	 */
	private Query_Builder $query_builder;

	/**
	 * Set up the test.
	 */
	public function set_up() {
		parent::set_up();
		$this->query_builder = new Query_Builder( new Config_Manager() );
	}

	/**
	 * Clean up after each test.
	 */
	public function tear_down() {
		remove_all_filters( 'gatherpress_references_query_args' );
		parent::tear_down();
	}

	/**
	 * Test that build_args returns empty array for unsupported post type.
	 */
	public function test_build_args_returns_empty_for_unsupported_type() {
		$result = $this->query_builder->build_args( 'nonexistent_type', 0, 0, 'all' );

		$this->assertEmpty( $result );
	}

	/**
	 * Test build_args returns proper structure for supported post type.
	 */
	public function test_build_args_returns_proper_structure() {
		if ( ! post_type_exists( 'gatherpress_event' ) ) {
			$this->markTestSkipped( 'GatherPress event post type not registered.' );
		}

		$result = $this->query_builder->build_args( 'gatherpress_event', 0, 0, 'all' );

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'post_type', $result );
		$this->assertArrayHasKey( 'posts_per_page', $result );
		$this->assertArrayHasKey( 'post_status', $result );
		$this->assertEquals( 'gatherpress_event', $result['post_type'] );
		$this->assertEquals( 'publish', $result['post_status'] );
		$this->assertEquals( 'ids', $result['fields'] );
	}

	/**
	 * Test that year filter adds a date_query.
	 */
	public function test_build_args_adds_date_query_for_year() {
		if ( ! post_type_exists( 'gatherpress_event' ) ) {
			$this->markTestSkipped( 'GatherPress event post type not registered.' );
		}

		$result = $this->query_builder->build_args( 'gatherpress_event', 0, 2024, 'all' );

		$this->assertArrayHasKey( 'date_query', $result );
		$this->assertEquals( 2024, $result['date_query'][0]['year'] );
	}

	/**
	 * Test that ref_term_id adds a tax_query.
	 */
	public function test_build_args_adds_tax_query_for_term() {
		if ( ! post_type_exists( 'gatherpress_event' ) ) {
			$this->markTestSkipped( 'GatherPress event post type not registered.' );
		}

		$result = $this->query_builder->build_args( 'gatherpress_event', 42, 0, 'all' );

		$this->assertArrayHasKey( 'tax_query', $result );
	}

	/**
	 * Test that the query args filter is applied.
	 */
	public function test_query_args_filter_is_applied() {
		if ( ! post_type_exists( 'gatherpress_event' ) ) {
			$this->markTestSkipped( 'GatherPress event post type not registered.' );
		}

		add_filter(
			'gatherpress_references_query_args',
			function ( $args ) {
				$args['posts_per_page'] = 50;
				return $args;
			} 
		);

		$result = $this->query_builder->build_args( 'gatherpress_event', 0, 0, 'all' );

		$this->assertEquals( 50, $result['posts_per_page'] );

		remove_all_filters( 'gatherpress_references_query_args' );
	}

	/**
	 * Test that GatherPress event query includes past events query param.
	 */
	public function test_gatherpress_event_includes_past_query() {
		if ( ! post_type_exists( 'gatherpress_event' ) ) {
			$this->markTestSkipped( 'GatherPress event post type not registered.' );
		}

		$result = $this->query_builder->build_args( 'gatherpress_event', 0, 0, 'all' );

		$this->assertArrayHasKey( 'gatherpress_event_query', $result );
		$this->assertEquals( 'past', $result['gatherpress_event_query'] );
	}
}
