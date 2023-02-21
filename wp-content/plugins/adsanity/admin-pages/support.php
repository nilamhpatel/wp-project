<?php
/**
 * Class file for the AdSanity Support Admin Page
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.4
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

/**
 * AdSanity_Support
 * This class adds add-on submenu for add-on advertisements
 */
class AdSanity_Support {

	/**
	 * Stub construct function
	 */
	function __construct() {}

	/**
	 * All of the actions and filter associated with this class
	 */
	public static function hooks() {

		add_action( 'admin_menu', array( __CLASS__, 'submenu_page' ), 20 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 10, 1 );

	}

	/**
	 * Create a submenu page for add-ons
	 */
	public static function submenu_page() {

		add_submenu_page(
			'edit.php?post_type=ads',
			'AdSanity: ' . __( 'Support', 'adsanity' ),
			__( 'Support', 'adsanity' ),
			'manage_options',
			'adsanity-support',
			array( __CLASS__, 'submenu_page_cb' )
		);

	}

	/**
	 * The view for About submenu page
	 */
	public static function submenu_page_cb() {

		require_once( ADSANITY_VIEWS . 'support.php' );

	}

	/**
	 * Enqueue the styles and scripts for this submenu page
	 *
	 * @param string $hook_suffix allows us to detect which hook this is firing on.
	 * @return void
	 */
	public static function enqueue_scripts( $hook_suffix ) {

		if ( 'ads_page_adsanity-support' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'adsanity-support-page',
			ADSANITY_CSS . 'support.css',
			array(),
			ADSANITY_VERSION,
			'all'
		);

	}

}
AdSanity_Support::hooks();
