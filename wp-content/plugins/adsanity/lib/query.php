<?php
/**
 * Helper functionality for querying ads.
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
 * AdSanityQuery
 *
 * Contains all helper methods for query AdSanity data.
 *
 * @pkg		AdSanity
 * @since	1.6
 */
class AdSanityQuery {

	/**
	 * Retrieves all ad posts
	 *
	 * @uses AdSanityQuery::get_ads returns ads based on parameters
	 * @return  array|wp_error  returns an array of WP_Post objects on success and a wp_error object
	 * on failure
	 */
	public static function get_all_ads() {
		$args = apply_filters( 'adsanity_get_all_ads_args', array(
			'nopaging' => true,
			'no_found_rows' => true,
		)  );
		return self::get_ads( $args );
	}

	/**
	 * Retrieves all ad posts
	 *
	 * @return  array|wp_error  returns an array of WP_Post objects on success and a wp_error object
	 * on failure
	 */
	public static function get_ads( $args = array() ) {
		$defaults = array(
			'post_type' => 'ads',
		);
		$ad_args = wp_parse_args( $args, $defaults );

		$ad_args = apply_filters( 'adsanity_get_ads_args', $ad_args );

		$ads = get_posts( $ad_args );
		if ( $ads ) {

			// Attach all post meta to the WP_Post object under 'meta'
			if ( isset( $ad_args['include_meta'] ) && $ad_args['include_meta'] ) {
				self::attach_meta( $ads );
			}

			return $ads;

		} else {
			return new WP_Error( 'adsanity_query_no_ads', __( 'No ads found.', 'adsanity' ) );
		}
	}

	/**
	 * Attaches all meta assigned to an ad to the ad object.
	 *
	 * @param  array  $ads  An array of WP_Post objects
	 */
	public static function attach_meta( &$ads = array() ) {
		foreach( $ads as $ad ) {
			$meta = Adsanity\Meta_Data::get( 'post', $ad->ID );
			AdSanityUtility::remove_wp_meta( $meta );
			$ad->meta = apply_filters( 'adsanity_attach_meta', $meta );
		}
	}
}
