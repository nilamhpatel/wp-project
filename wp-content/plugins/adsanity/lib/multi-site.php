<?php
/**
 * Multi-site specific functionality.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

add_filter( 'site_option_illegal_names', 'adsanity_illegal_multisite_names' );
add_filter( 'subdirectory_reserved_names', 'adsanity_illegal_multisite_names' );

/**
 * Adds the Ad permalink to the illegal names list for WordPress multi-site
 * @param  string $value      The value of the illegal names field
 * @param  string $option     The name of the option
 * @param  int    $network_id The network ID
 * @return string             The value plus 'ads'
 */
function adsanity_illegal_multisite_names( $value ) {

	if ( ! in_array( 'ads', $value ) ) {
		$value[] = 'ads';
	}

	return $value;

}

add_filter( 'domain_exists', 'adsanity_domain_exists', 10, 4 );

function adsanity_domain_exists( $result, $domain, $path, $site_id ) {

	if ( 'ads' == $path ) {
		return 1;
	}

	return $result;

}
