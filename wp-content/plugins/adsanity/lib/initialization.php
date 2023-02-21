<?php
/**
 * Initial plugin setup and configuration.
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
 * adsanity_initialization
 *
 * Makes sure AdSanity has baseline ad sizes
 *
 * @pkg		AdSanity
 */
class adsanity_initialization {

	function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	function admin_init() {

		$options = get_option( ADSANITY_ADMIN_OPTIONS, array() );
		if ( isset( $options['sizes'] ) ) {
			return true;
		}

		/*
		 * Standard Ad Sizes as pulled from
		 * http://www.iab.net/iab_products_and_industry_services/1421/1443/1452
		 */
		$options['sizes'] = ADSANITY_DEFAULT_AD_SIZES;

		update_option( ADSANITY_ADMIN_OPTIONS, $options );

	}

	/**
	 * Deprecated in 1.8.
	 *
	 * @return array
	 */
	public static function get_default_ad_sizes() {

		_doing_it_wrong(
			'get_default_ad_sizes',
			esc_html__( 'This method was removed from AdSanity in version 1.8. You can now use the constant `ADSANITY_DEFAULT_AD_SIZES` in place of this call.', 'adsanity' ),
			'1.8'
		);

		return ADSANITY_DEFAULT_AD_SIZES;

	}

}
new adsanity_initialization;
