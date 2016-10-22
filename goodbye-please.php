<?php
/**
 * Plugin Name: Goodbye Please
 * Version: 1.0.0
 * Description: This plugin redirects all public-facing page requests to an existing page.
 * Author: Charles Fulton
 * Author URI: https://www.lafayette.edu
 * Plugin URI:
 * Text Domain: goodbye-please
 * Domain Path: /languages
 * @package goodbye_please
 */

class Goodbye_Please {

	// Derived from http://wordpress.stackexchange.com/questions/76802/redirect-entire-website-to-a-single-page
	function goodbye_redirect() {
		$options = get_option( 'goodbye_please_settings' );
		if ( ! empty( $options['goodbye_please_page'] ) && ! is_page( $options['goodbye_please_page'] ) ) {
			wp_redirect( get_permalink( $options['goodbye_please_page'] ) );
			exit;
		}
	}

	function goodbye_please_add_admin_menu() {
		add_menu_page( 'Goodbye Please', 'Goodbye Please', 'manage_options', 'goodbye-please', array( 'Goodbye_Please', 'goodbye_please_settings_page' ) );

	}

	function goodbye_please_settings_init() {
		register_setting( 'goodbye_please_settings_group', 'goodbye_please_settings', array( 'Goodbye_Please', 'sanitize' ) );
		add_settings_section(
			'goodbye_please_settings_section',
			__( 'Plugin settings', 'goodbye-please' ),
			array( 'Goodbye_Please', 'goodbye_please_settings_section_callback' ),
			'goodbye_please_settings_group'
		);

		add_settings_field(
			'goodbye_please_page',
			__( 'Goodbye Page', 'goodbye-please' ),
			array( 'Goodbye_Please', 'goodbye_please_page_render' ),
			'goodbye_please_settings_group',
			'goodbye_please_settings_section'
		);
	}

	function sanitize( $input ) {
		$new_input = array();
		if ( isset( $input['goodbye_please_page'] ) ) {
			$new_input['goodbye_please_page'] = absint( $input['goodbye_please_page'] );
		}
		return $new_input;
	}

	function goodbye_please_settings_section_callback() {
		echo __( 'Select a page to display to all visitors. All requests will be redirected to this page.', 'goodbye-please' );
	}

	function goodbye_please_page_render() {
		$options = get_option( 'goodbye_please_settings' );
		wp_dropdown_pages(
			array(
				'show_option_none' => __( 'No page selected...', 'goodbye-please' ),
				'selected' => $options['goodbye_please_page'],
				'name' => 'goodbye_please_settings[goodbye_please_page]',
			)
		);
	}

	function goodbye_please_settings_page() {
		?>
		<form action='options.php' method='post'>
		<?php
			settings_fields( 'goodbye_please_settings_group' );
			do_settings_sections( 'goodbye_please_settings_group' );
			submit_button();
		?>
		</form>
		<?php
	}
}

if ( ! is_admin() ) {
	add_action( 'template_redirect', array( 'Goodbye_Please', 'goodbye_redirect' ) );
}

if ( is_admin() ) {
	add_action( 'admin_menu', array( 'Goodbye_Please', 'goodbye_please_add_admin_menu' ) );
	add_action( 'admin_init', array( 'Goodbye_Please', 'goodbye_please_settings_init' ) );
}
