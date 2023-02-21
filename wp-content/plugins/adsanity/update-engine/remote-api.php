<?php
/**
 * Handles plugin updates for the product.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

// Load our custom updater.
if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {

	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );

}

/**
 * Updates the AdSanity plugin from the custom update API.
 */
class AdsanityDeveloperUpdater {

	/**
	 * The path to the root AdSanity file.
	 *
	 * @var $file
	 */
	public static $file;

	const PRODUCT    = 'AdSanity Developer';
	const SECTION    = 'adsanity-licenses';
	const KEY        = 'adsanity_license_key';
	const STATUS     = 'adsanity_license_status';
	const NONCE      = 'adsanity_license_nonce';
	const ACTIVATE   = 'adsanity_license_activate';
	const DEACTIVATE = 'adsanity_license_deactivate';

	/**
	 * Hook into WordPress
	 *
	 * @return void
	 */
	public static function hooks() {

		self::$file = ADSANITY_ABS . 'adsanity.php';

		add_action( 'init', array( __CLASS__, 'plugin_updater' ), 0 );
		add_action( 'admin_init', array( __CLASS__, 'activate_license' ) );
		add_action( 'admin_init', array( __CLASS__, 'deactivate_license' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_option' ) );

	}

	/**
	 * Loads EDD Updater class and checks for updates
	 */
	public static function plugin_updater() {

		// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
		$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
		if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
			return;
		}

		// Retrieve our license key from the DB.
		$license_key = trim( get_option( self::KEY ) );

		// Setup the updater.
		$edd_updater = new EDD_SL_Plugin_Updater(
			ADSANITY_UPDATE_API,
			self::$file,
			array(
				'version'   => ADSANITY_VERSION, // current version number.
				'license'   => $license_key, // license key (used get_option above to retrieve from DB).
				'item_name' => self::PRODUCT, // name of this plugin.
				'author'    => 'Pixel Jar', // author of this plugin.
			)
		);
	}

	/**
	 * Creates our settings in the options table.
	 *
	 * @return void
	 */
	public static function register_option() {

		$license = get_option( self::KEY );
		$status = get_option( self::STATUS );
		$active = sprintf(
			'<span style="color:green;">%s</span>',
			__( 'active', 'adsanity' )
		);
		$inactive = sprintf(
			'<span style="color:red;">%s</span>',
			__( 'inactive', 'adsanity' )
		);

		register_setting(
			'adsanity-licenses',
			self::KEY,
			array( __CLASS__, 'sanitize_license' )
		);

		add_settings_section(
			self::SECTION,
			sprintf( '%s License', self::PRODUCT ),
			array( __CLASS__, 'license_description' ),
			'adsanity-licenses'
		);
		add_settings_field(
			'adsanity_license_key',
			sprintf(
				'%s %s',
				__( 'License', 'adsanity' ),
				( empty( $license ) ) ? '' : ( ( $status !== false && $status == 'valid' ) ? '(' . $active . ')' : '(' . $inactive . ')' )
			),
			array( __CLASS__, 'key_field' ),
			'adsanity-licenses',
			self::SECTION
		);

	}

	/**
	 * Outputs the license description
	 *
	 * @return void
	 */
	public static function license_description() {

		echo '<p>';
		echo sprintf( esc_html__( 'Enter your license for %s in the field below.', 'adsanity' ), self::PRODUCT );
		echo '</p>';

	}

	/**
	 * Outputs the license key field.
	 *
	 * @return void
	 */
	public static function key_field() {

		$license 	= get_option( self::KEY );
		$status 	= get_option( self::STATUS );

		echo sprintf(
			'<input id="adsanity_license_key" name="adsanity_license_key" type="text" class="regular-text" value="%s" />',
			esc_attr( $license )
		);

		if ( ! empty( $license ) ) {

			wp_nonce_field( self::NONCE, self::NONCE );

			if ( false !== $status && 'valid' == $status ) {

				echo sprintf(
					'<input type="submit" class="button-secondary" name="%s" value="%s" />',
					esc_attr( self::DEACTIVATE ),
					esc_attr__( 'Deactivate License', 'adsanity' )
				);

			} else {

				echo sprintf(
					'<input type="submit" class="button-secondary" name="%s" value="%s"/>',
					esc_attr( self::ACTIVATE ),
					esc_attr__( 'Activate License', 'adsanity' )
				);

			}

		}

	}

	/**
	 * Sanitize the license that we get.
	 *
	 * @param string $new The value from the user input.
	 * @return string
	 */
	public static function sanitize_license( $new ) {

		$old = get_option( self::KEY );
		if ( $old && $old != $new ) {

			// New license has been entered, so must reactivate.
			delete_option( self::STATUS );

		}

		return $new;

	}

	/**
	 * Activates a license key.
	 */
	public static function activate_license() {

		// Listen for our activate button to be clicked.
		if ( isset( $_POST[ self::ACTIVATE ] ) ) {

			// Run a quick security check.
		 	if ( ! check_admin_referer( self::NONCE, self::NONCE ) ) {

				// Get out if we didn't click the Activate button.
				return;

		 	}

			// Retrieve the license from the database.
			$license = trim( get_option( self::KEY ) );

			// Data to send in our API request.
			$api_params = array(
				'edd_action' => 'activate_license',
				'license' => $license,
				'item_name' => urlencode( self::PRODUCT ), // The name of our product in EDD.
				'url' => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post(
				ADSANITY_UPDATE_API,
				array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params )
			);

			// Make sure the response came back okay.
			if ( is_wp_error( $response ) ) {

				return false;

			}

			// Decode the license data.
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			update_option( self::STATUS, $license_data->license );

		}

	}

	/**
	 * This will descrease the site count
	 */
	public static function deactivate_license() {

		// Listen for our activate button to be clicked.
		if ( isset( $_POST[ self::DEACTIVATE ] ) ) {

			// Run a quick security check.
		 	if ( ! check_admin_referer( self::NONCE, self::NONCE ) ) {

				return; // Get out if we didn't click the Activate button.

		 	}

			// Retrieve the license from the database.
			$license = trim( get_option( self::KEY ) );

			// Data to send in our API request.
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license' 	 => $license,
				'item_name'  => urlencode( self::PRODUCT ), // The name of our product in EDD.
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post(
				ADSANITY_UPDATE_API,
				array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params )
			);

			// Make sure the response came back okay.
			if ( is_wp_error( $response ) ) {

				return false;

			}

			// Decode the license data.
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( 'deactivated' == $license_data->license ) {

				delete_option( self::STATUS );

			}

		}

	}

}

AdsanityDeveloperUpdater::hooks();
