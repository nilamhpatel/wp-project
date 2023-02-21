<?php
/**
 * Class file for the AdSanity Statistics Admin Page
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
 * AdSanity_Stats
 * This class handles all of the custom stats functionality
 */
class AdSanity_Stats {

	/**
	 * Stub construct function
	 */
	public function __construct() {}

	/**
	 * Kicks off all the hooks required to make this class run
	 */
	public static function hooks() {

		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'stats_styles' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'stats_scripts' ) );
		add_action( 'admin_init', array( __CLASS__, 'maybe_export_stats' ) );
		add_action( 'admin_init', array( __CLASS__, 'add_capability' ) );

	}

	/**
	 * Creates a "Stats" submenu under the main AdSanity menu to allow users to create custom
	 * statistical reports. Also kicks off enqueueing of admin scripts and styles.
	 */
	public static function admin_menu() {

		$stats = add_submenu_page(
			'edit.php?post_type=ads',
			'AdSanity: ' . __( 'Reports', 'adsanity' ),
			__( 'Reports', 'adsanity' ),
			'adsanity_view_ad_stats',
			'adsanity-stats',
			array( __CLASS__, 'stats_page' )
		);

	}

	/**
	 * Generates a csv download report for the selected statistics
	 */
	public static function maybe_export_stats() {

		if (
			isset( $_POST['_adsanity_export_nonce'] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_adsanity_export_nonce'] ) ), 'adsanity-stat-export' )
		) {

			AdSanity_Export::custom_stats_export();

		}

	}

	/**
	 * Renders the custom stats page
	 */
	public static function stats_page() {

		require_once ADSANITY_VIEWS . 'stats.php';

	}

	/**
	 * Optionally enqueues the custom stat css and javascript if the request is coming from the
	 * custom stats page.
	 *
	 * @param string $hook_suffix the hook name for the current page.
	 * @return void
	 */
	public static function stats_styles( $hook_suffix = '' ) {

		if ( 'ads_page_adsanity-stats' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'adsanity-stats' );
	}

	/**
	 * Enqueue Custom Stats Javascripts.
	 *
	 * @param string $hook_suffix the hook name for the current page.
	 * @return void
	 */
	public static function stats_scripts( $hook_suffix = '' ) {

		if ( 'ads_page_adsanity-stats' !== $hook_suffix ) {
			return;
		}

		if ( isset( $_GET['tab'] ) && 'custom' === sanitize_text_field( wp_unslash( $_GET['tab'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			global $wp_locale;
			wp_enqueue_script( 'adsanity-custom-stats' );
			wp_localize_script(
				'adsanity-custom-stats',
				'adsanity',
				array(
					'adsanity_eol' => ADSANITY_EOL,
					'months'       => $wp_locale->month,
					'months_01'    => $wp_locale->month['01'],
					'months_02'    => $wp_locale->month['02'],
					'months_03'    => $wp_locale->month['03'],
					'months_04'    => $wp_locale->month['04'],
					'months_05'    => $wp_locale->month['05'],
					'months_06'    => $wp_locale->month['06'],
					'months_07'    => $wp_locale->month['07'],
					'months_08'    => $wp_locale->month['08'],
					'months_09'    => $wp_locale->month['09'],
					'months_10'    => $wp_locale->month['10'],
					'months_11'    => $wp_locale->month['11'],
					'months_12'    => $wp_locale->month['12'],
				)
			);
		} else {
			wp_enqueue_script( 'adsanity-group-stats' );
		}
	}

	/**
	 * Add adsanity_view_ad_stats capability to administrator
	 *
	 * @return void
	 */
	public static function add_capability() {

		$administrator = get_role( 'administrator' );
		$administrator->add_cap( 'adsanity_view_ad_stats' );

	}
}
AdSanity_Stats::hooks();
