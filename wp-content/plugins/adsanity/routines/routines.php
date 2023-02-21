<?php
/**
 * Handles migration needs between versions.
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
 * This class handles the various hooks related to plugin activation/deactivation.
 */
class AdSanityRoutines {

	/**
	 * Hooks into WordPress to run update routines.
	 *
	 * @return void
	 */
	public static function hooks() {

		add_action( 'admin_init', array( __CLASS__, 'update' ), 100 );

	}

	/**
	 * Kicks off the activation routine.
	 *
	 * @return void
	 */
	public static function activation() {
		require_once( ADSANITY_ABS . 'routines/activation.php' );
	}

	/**
	 * Kicks off the deactivation routine.
	 *
	 * @return void
	 */
	public static function deactivation() {
		require_once( ADSANITY_ABS . 'routines/deactivation.php' );
	}

	/**
	 * Kicks off the uninstall routine.
	 *
	 * @return void
	 */
	public static function uninstall() {
		require_once( ADSANITY_ABS . 'routines/uninstall.php' );
	}

	/**
	 * Kicks off the update routine.
	 *
	 * @return void
	 */
	public static function update() {
		require_once( ADSANITY_ABS . 'routines/update.php' );
	}

}

AdSanityRoutines::hooks();
