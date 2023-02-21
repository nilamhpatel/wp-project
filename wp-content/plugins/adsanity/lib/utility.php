<?php
/**
 * Random utilities for dealing with AdSanity.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

if ( ! class_exists( 'DeviceDetector' ) ) {
	require_once ADSANITY_ABS . 'vendor/autoload.php';
}
use DeviceDetector\Parser\Bot as BotParser;

/**
 * AdsanityUtility
 *
 * Contains useful utility functions
 *
 * @package AdSanity
 * @since 1.6
 */
class AdSanityUtility {

	/**
	 * Removes WP Core meta data from a given array of meta keys and values
	 *
	 * @param array $meta An associative array of meta keys and values.
	 */
	public static function remove_wp_meta( &$meta = array() ) {

		$wp_meta_keys = array( '_edit_last', '_edit_lock' );
		foreach ( $meta as $meta_key => $meta_value ) {

			if ( in_array( $meta_key, $wp_meta_keys, true ) ) {

				unset( $meta[ $meta_key ] );

			}

		}

	}

	/**
	 * Loads in matomo DeviceDetector library to identify bots.
	 *
	 * @return boolean
	 */
	public static function is_robot() {

		// Grab the user agent.
		$ua_string = trim( urldecode( strtolower( $_SERVER['HTTP_USER_AGENT'] ) ) );

		// No User Agent String == Bot.
		if ( empty( $ua_string ) ) {
			return true;
		}

		// Self Identified Bots == Bot.
		if ( preg_match( '#(bot|spider|crawl|wordpress)#', $ua_string ) ) {
			return true;
		}

		$bot_parser = new BotParser();
		$bot_parser->setUserAgent( $ua_string );
		$bot_parser->discardDetails();
		$result = $bot_parser->parse();

		if ( ! is_null( $result ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Creates unique hash of 10 characters
	 */
	public static function hash( $string ) {

		return substr( md5( $string ), 4, 10 );

	}

}
