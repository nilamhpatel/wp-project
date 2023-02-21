<?php
/**
 * Divi custom modules.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

class AdSanityDiviCustomModules extends DiviExtension {

	public function __construct( $name = 'adsanity-divi-custom-modules', $args = [] ) {
		$this->plugin_dir      = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url  = plugin_dir_url( $this->plugin_dir );
		$this->builder_js_data = [];

		parent::__construct( $name, $args );

		add_action( 'wp_enqueue_scripts', [ __CLASS__, '_custom_scripts' ], 1000 );
	}

	public static function _custom_scripts() {

		// Ensure that jQuery is available
		wp_enqueue_script( 'jquery' );

		// Add rest url
		wp_localize_script( 'adsanity-divi-custom-modules-builder-bundle', 'ADSANITY_DIVI', [
			'rest_endpoint'  => trailingslashit( rest_url( 'wp/v2' ) ),
			'loadingMessage' => esc_js( __( 'Loading content &hellip;', 'adsanity' ) ),
			'errorMessage'   => esc_js( __( 'Error loading ad', 'adsanity' ) ),
		] );

	}

}

new AdSanityDiviCustomModules;
