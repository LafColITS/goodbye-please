<?php
/**
 * Class GoodbyePleaseTest
 *
 * @package Goodbye_Please
 */

/**
 * Test setting a default page.
 */
class TestGoodbyePlease extends WP_UnitTestCase {

	protected static $location_actual;
	protected static $location_expected;

	public function test_default_state() {
		$options = get_option( 'goodbye_please_settings' );
		$this->assertEmpty( $options['goodbye_please_page'] );
	}

	public function test_set_page() {
		// Create some posts.
		for ( $n = 0; $n < 5; $n++ ) {
			$post = array(
				'title' => "Test post {$n}",
				'type' => 'post',
				'post_status' => 'publish',
				'post_content' => "Test post {$n}",
			);
			wp_insert_post( $post );
		}

		$page = array(
			'title' => 'Goodbye Please',
			'type' => 'page',
			'post_status' => 'publish',
			'post_content' => 'Hello World',
		);
		$page_id = wp_insert_post( $page );

		$option = array( 'goodbye_please_page' => $page_id );
		add_option( 'goodbye_please_settings', $option );

		self::$location_expected = get_permalink( $page_id );
		$plugin = new Goodbye_Please();
		$plugin->goodbye_redirect();
		$this->assertEquals( self::$location_expected, self::$location_actual );
	}

	// Thanks to https://www.analysisandsolutions.com/blog/html/writing-phpunit-tests-for-wordpress-plugins-wp-redirect-and-continuing-after-php-errors.htm
	public static function wp_redirect( $location, $status ) {
		self::$location_actual = $location;
	}
}
