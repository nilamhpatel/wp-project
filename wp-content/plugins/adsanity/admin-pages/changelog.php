<?php
/**
 * Class file for the AdSanity Changelog Admin Page
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.3
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

/**
 * AdSanity_Changelog
 * This class adds a changelog submenu so users can see what's new
 */
class AdSanity_Changelog {

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
			'AdSanity: ' . __( 'Changelog', 'adsanity' ),
			__( 'Changelog', 'adsanity' ),
			'manage_options',
			'adsanity-changelog',
			array( __CLASS__, 'submenu_page_cb' )
		);

	}

	/**
	 * Enqueue the styles and scripts for this submenu page
	 *
	 * @param string $hook_suffix allows us to detect which hook this is firing on.
	 * @return void
	 */
	public static function enqueue_scripts( $hook_suffix ) {

		if ( 'ads_page_adsanity-changelog' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'adsanity-changelog-page',
			ADSANITY_CSS . 'changelog.css',
			array(),
			ADSANITY_VERSION,
			'all'
		);

	}

	/**
	 * The view for About submenu page
	 */
	public static function submenu_page_cb() {

		require_once( ADSANITY_VIEWS . 'changelog.php' );

	}

}
AdSanity_Changelog::hooks();
