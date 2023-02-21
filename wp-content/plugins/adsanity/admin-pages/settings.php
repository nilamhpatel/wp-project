<?php
/**
 * Class file for the AdSanity Settings Admin Page
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.3
 * @version 1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

/**
 * AdSanity_Settings
 * This class is a settings framework for the add-ons to hook into
 */
class AdSanity_Settings {

	/**
	 * Stub construct function
	 */
	function __construct() {}

	/**
	 * All of the actions and filter associated with this class
	 */
	public static function hooks() {

		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'settings_scripts_styles' ) );

	}

	/**
	 * Hooks in the settings page
	 *
	 * @return void
	 */
	public static function admin_menu() {

		$settings = add_submenu_page(
			'edit.php?post_type=ads',
			'AdSanity: ' . __( 'Settings', 'adsanity' ),
			__( 'Settings', 'adsanity' ),
			'manage_options',
			'adsanity-settings',
			array( __CLASS__, 'settings_page' )
		);

	}

	/**
	 * Renders the custom settings page
	 */
	public static function settings_page() {

		require_once ADSANITY_VIEWS . 'settings.php';

	}

	/**
	 * Enqueues the scripts and styles for the settings page
	 *
	 * @param string $hook_suffix the hook name for the current page.
	 * @return void
	 */
	public static function settings_scripts_styles( $hook_suffix = '' ) {

		// Only run this on the settings screen.
		if ( 'ads_page_adsanity-settings' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'adsanity-settings' );
		wp_enqueue_script( 'adsanity-settings' );
		wp_localize_script(
			'adsanity-settings',
			'ADSANITY_SETTINGS',
			[
				'reset_confirmation' => __( 'WARNING! Before taking this action, we recommend that you create a backup of your database. This will allow you to undo this action within minutes.', 'adsanity' ),
				'reset_url'          => wp_nonce_url(
					add_query_arg(
						[
							'post_type' => 'ads',
							'page'      => 'adsanity-settings',
							'tab'       => 'general',
							'action'    => 'reset',
						],
						admin_url( 'edit.php' )
					),
					'reset-stats'
				),
			]
		);

	}

	/**
	 * Sets up the settings fields via the Settings API
	 *
	 * @return void
	 */
	public static function admin_init() {

		// Check for stat Reset.
		if (
			isset( $_GET['_wpnonce'], $_GET['action'] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'reset-stats' ) &&
			'reset' === $_GET['action']
		) {
			adsanity_delete_all_stats();
		}

		register_setting(
			ADSANITY_ADMIN_OPTIONS,
			ADSANITY_ADMIN_OPTIONS,
			array( __CLASS__, 'settings_validation' )
		);

		add_settings_section(
			'adsanity_tracking_options',
			__( 'Tracking Options', 'adsanity' ),
			array( __CLASS__, 'adsanity_tracking_options' ),
			ADSANITY_ADMIN_OPTIONS
		);
			add_settings_field(
				'adsanity_ignore_roles',
				__( 'Roles to Exclude From Tracking', 'adsanity' ),
				array( __CLASS__, 'adsanity_ignore_roles' ),
				ADSANITY_ADMIN_OPTIONS,
				'adsanity_tracking_options'
			);

		add_settings_section(
			'adsanity_adstxt_options',
			__( 'Ads.txt', 'adsanity' ),
			array( __CLASS__, 'adsanity_adstxt_options' ),
			ADSANITY_ADMIN_OPTIONS
		);
			add_settings_field(
				'adstxt',
				__( 'Entries', 'adsanity' ),
				array( __CLASS__, 'adsanity_adstxt' ),
				ADSANITY_ADMIN_OPTIONS,
				'adsanity_adstxt_options'
			);

		// Hook for registering additional settings.
		do_action( 'adsanity_register_settings' );

	}

	/**
	 * Outputs the description for the tracking options fields
	 *
	 * @return void
	 */
	public static function adsanity_tracking_options() {

		printf(
			'<p>%s</p>',
			esc_html__( 'Settings pertaining to how views and clicks are tracked.', 'adsanity' )
		);

	}

	/**
	 * Sets up the ignore roles field
	 *
	 * @return void
	 */
	public static function adsanity_ignore_roles() {

		$options = wp_parse_args(
			get_option( ADSANITY_ADMIN_OPTIONS, array() ),
			array( 'adsanity_ignore_roles' => array() )
		);
		$roles = get_editable_roles();

		printf(
			'<select class="role-tracking" name="%s[adsanity_ignore_roles][]" multiple>',
			esc_attr( ADSANITY_ADMIN_OPTIONS )
		);

		foreach ( $roles as $role_name => $role_info ) {

			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $role_name ),
				selected( in_array( $role_name, $options['adsanity_ignore_roles'], true ) , true, false ),
				esc_html( $role_info['name'] )
			);

		}
		echo '</select>';

	}

	/**
	 * Outputs description for Ads.txt field
	 *
	 * @return void
	 */
	public static function adsanity_adstxt_options() {

		echo sprintf(
			'<p>%s <a href="%s" target="_blank">%s</a></p>',
			esc_html__( 'As part of a broader effort to eliminate the ability to profit from counterfeit inventory in the open digital advertising ecosystem, Ads.txt provides a mechanism to enable content owners to declare who is authorized to sell their inventory.', 'adsanity' ),
			esc_attr( esc_url( 'https://adsanityplugin.com/help/guide/settings/ads-txt/' ) ),
			esc_html__( 'Learn More' , 'adsanity' )
		);

	}

	/**
	 * Sets up the ads.txt settings field
	 *
	 * @return void
	 */
	public static function adsanity_adstxt() {

		$options = wp_parse_args(
			get_option( ADSANITY_ADMIN_OPTIONS, array() ),
			array(
				'adstxt' => '',
			)
		);
		echo sprintf(
			'<textarea class="widefat code" rows="25" name="%s[adstxt]" id="adsanity_adstxt_content">%s</textarea>',
			esc_attr( ADSANITY_ADMIN_OPTIONS ),
			esc_textarea( $options['adstxt'] )
		);

	}

	/**
	 * Handles validation for all core settings fields
	 *
	 * @param array $dirty The unsanitized settings values.
	 * @return array Sanitized array of settings values
	 */
	public static function settings_validation( $dirty = array() ) {

		if ( doing_action( 'save_post' ) ) {
			return $dirty;
		}

		$clean = array();
		$options = get_option( ADSANITY_ADMIN_OPTIONS );

		// Ignore user roles.
		if ( ! isset( $dirty['adsanity_ignore_roles'] ) ) {

			unset( $options['adsanity_ignore_roles'] );

		} else {

			$clean['adsanity_ignore_roles'] = array_values( $dirty['adsanity_ignore_roles'] );

		}

		// Allows for Ad Sizes to be changed via Add-ons.
		if ( isset( $dirty['sizes'] ) && is_array( $dirty['sizes'] ) ) {

			$clean['sizes'] = $dirty['sizes'];

		}

		// Default Ad.
		if ( isset( $dirty['adsanity_default_ad'] ) ) {

			$clean['adsanity_default_ad'] = $dirty['adsanity_default_ad'];

		}

		// Include Ads Automatically?
		if ( ! isset( $dirty['adsanity_show_in_content'] ) ) {
			$clean['adsanity_show_in_content'] = 0;
		} else {
			$clean['adsanity_show_in_content'] = 1;
		}

		if ( ! array_key_exists( 'adsanity_in_content_rules', $dirty ) ) {

			$clean['adsanity_in_content_rules'][0] = array(
				'post_types'       => array(),
				'position'         => '',
				'alignment'        => '',
				'ad_group'         => null,
				'position_dynamic' => '',
				'position_num'     => '',
				'position_element' => '',
				'type'             => null,
			);

		} else {

			// Loop over each rule for automatic inclusion
			foreach ( $dirty['adsanity_in_content_rules'] as $key => $rule ) {

				// Is there at least one post type selected?
				if ( empty( $rule['post_types'] ) ) {
					$clean['adsanity_in_content_rules'][ $key ] = array(
						'post_types'       => array(),
						'position'         => '',
						'alignment'        => '',
						'ad_group'         => null,
						'position_dynamic' => '',
						'position_num'     => '',
						'position_element' => '',
						'type'             => null,
					);
					break;
				}

				$clean['adsanity_in_content_rules'][ $key ]['post_types'] = array();

				foreach ( $rule['post_types'] as $post_type ) {

					$post_type = sanitize_text_field( $post_type );
					array_push( $clean['adsanity_in_content_rules'][ $key ]['post_types'], $post_type );

				}

				// Position
				$clean['adsanity_in_content_rules'][ $key ]['position'] = sanitize_text_field( $rule['position'] );

				// If position is dynamic, collect other options
				if ( 'dynamic' === $rule['position'] ) {

					$clean['adsanity_in_content_rules'][ $key ]['position_dynamic'] = sanitize_text_field( $rule['position_dynamic'] );
					$clean['adsanity_in_content_rules'][ $key ]['position_num'] = sanitize_text_field( $rule['position_num'] );
					$clean['adsanity_in_content_rules'][ $key ]['position_element'] = sanitize_text_field( $rule['position_element'] );

				} else {

					$clean['adsanity_in_content_rules'][ $key ]['position_dynamic'] = 'before';
					$clean['adsanity_in_content_rules'][ $key ]['position_num'] = 1;
					$clean['adsanity_in_content_rules'][ $key ]['position_element'] = 'paragraph';

				}

				// Alignment for automatic ad rule
				if ( array_key_exists( 'alignment', $rule ) ) {

					$clean['adsanity_in_content_rules'][ $key ]['alignment'] = sanitize_text_field( $rule['alignment'] );

				} else {

					$clean['adsanity_in_content_rules'][ $key ]['alignment'] = 'none';

				}

				// Ad Group for automatic ad rule
				if ( array_key_exists( 'ad_group', $rule ) ) {

					$clean['adsanity_in_content_rules'][ $key ]['ad_group'] = intval( $rule['ad_group'] );

				} else {

					$clean['adsanity_in_content_rules'][ $key ]['ad_group'] = null;

				}

				// Type
				if ( array_key_exists( 'type', $rule ) ) {
					$clean['adsanity_in_content_rules'][ $key ]['type'] = sanitize_text_field( $rule['type'] );
				} else {
					$clean['adsanity_in_content_rules'][ $key ]['type'] = null;
				}

				// Max Width.
				if (
					isset( $rule['max-width-enabled'], $rule['max-width'] ) &&
					'on' === $rule['max-width-enabled']
				) {
					$clean['adsanity_in_content_rules'][ $key ]['max_width'] = intval( $rule['max-width'] );
				}

			}

		}

		if ( isset( $dirty['adstxt'] ) ) {

			$clean['adstxt'] = AdSanity_AdsTxt::check_for_google_adstxt( $dirty['adstxt'] );

		}

		/**
		 * Allow additional settings to be saved.
		 *
		 * @param array $clean The cleaned options.
		 * @param array $dirty The dirty options.
		 */
		$clean = apply_filters( 'adsanity_save_admin_options', $clean, $dirty );

		$clean = array_merge( (array) $options, (array) $clean );
		return $clean;

	}

}
AdSanity_Settings::hooks();
