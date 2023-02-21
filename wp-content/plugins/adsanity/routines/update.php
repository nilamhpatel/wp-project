<?php
/**
 * Handles upgrades.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

$local_version = get_option( 'adsanity-db-version', false );
if ( false === $local_version ) {

	// New Install.
	update_option( 'adsanity-db-version', ADSANITY_VERSION );
	return;

} elseif ( version_compare( $local_version, ADSANITY_VERSION, '>=' ) ) {

	// This or Beta Version.
	return;

}
$options = get_option( ADSANITY_ADMIN_OPTIONS );

// VERSION 1.1 Upgrade Routines.
if ( version_compare( $local_version, '1.1', '<' ) ) {

	global $wpdb;

	// RESET number of ads shown in the ads list.
	$wpdb->query("
		DELETE FROM {$wpdb->usermeta}
		WHERE meta_key = 'edit_ads_per_page'
	");

	// DELETE data storage post and associated data.
	$data = get_posts( array( 'post_type' => 'adsanity-data' ) );
	if ( count( $data ) > 0 ) {

		foreach ( $data as $ad ) {

			wp_delete_post( $ad->ID, true );

		}

	}

	// ADD new default ad sizes.
	if ( isset( $options['sizes'] ) ) {

		$default_sizes = ADSANITY_DEFAULT_AD_SIZES;

		foreach ( $default_sizes as $k => $v ) {

			if ( isset( $options['sizes'][ $k ] ) && $options['sizes'][ $k ] == $v ) {
				continue;
			}

			$options['sizes'][ $k ] = $v;

		}
	}
}

// VERSION 1.2 Upgrade Routines.
if ( version_compare( $local_version, '1.2', '<' ) ) {

	$options['adsanity_ignore_roles'] = array(
		'administrator' => 'administrator',
		'editor' => 'editor',
		'author' => 'author',
		'contributor' => 'contributor',
	);

}

// VERSION 1.4 Upgrade Routines.
if ( version_compare( $local_version, '1.4', '<' ) ) {

	$options['adstxt'] = AdSanity_AdsTxt::check_for_google_adstxt();

}

// VERSION 1.7 Upgrade Routines
if ( version_compare( $local_version, '1.7', '<' ) ) {

	// Remove any stored notifications.
	delete_option( 'pj-apn-messages' );

	// Remove any scheduled checks for notifications.
	$timestamp = wp_next_scheduled( 'pj-apn-check' );
	if ( false !== $timestamp ) {

		wp_unschedule_event( $timestamp, 'pj-apn-check' );

	}

}

// Update relevant options.
update_option( ADSANITY_ADMIN_OPTIONS, $options );
update_option( 'adsanity-db-version', ADSANITY_VERSION );

// Redirect to Changelog page.
exit(
	wp_safe_redirect(
		add_query_arg(
			array(
				'post_type' => 'ads',
				'page' => 'adsanity-changelog',
			),
			admin_url( 'edit.php' )
		)
	)
);
