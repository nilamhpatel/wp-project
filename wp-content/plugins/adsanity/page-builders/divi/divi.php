<?php
/**
 * Divi modules.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

if ( ! function_exists( 'adsanity_divi_initialize_extension' ) ) {

	function adsanity_divi_initialize_extension() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/AdSanityDiviCustomModules.php';
	}

	add_action( 'divi_extensions_init', 'adsanity_divi_initialize_extension' );

}
