<?php
/**
 * Beaver Builder functionality.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

/**
 * Beaver Builder Modules for AdSanity
 */
class AdSanity_Beaver_Builder_Modules {

	function __construct() {}

	/**
	 * The hooks for this class
	 */
	public static function hooks() {

		add_action( 'init', array( __CLASS__, 'load_modules' ) );

	}

	/**
	 * Load the modules
	 */
	public static function load_modules() {

		if ( ! class_exists( 'FLBuilder' ) ) {
			return;
		}

		$path = plugin_dir_path( __FILE__ );
		require_once( $path . 'adsanity-single/adsanity-single.php' );
		require_once( $path . 'adsanity-group/adsanity-group.php' );
		require_once( $path . 'adsanity-random/adsanity-random.php' );

	}

}

AdSanity_Beaver_Builder_Modules::hooks();
