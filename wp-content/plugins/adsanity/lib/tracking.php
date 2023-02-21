<?php
/**
 * Tracking related functionality.
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
 * Checks to see if the view should be tracked based on rules, then stores the value
 * @param  int $post_id the ID of the ad that's being viewed
 */
function adsanity_view( $post_id ) {

	if ( ADSANITY_TRACK_THIS ) {

		$meta_key = adsanity_get_meta_key( 'views' );

		if ( Adsanity\Meta_Data::get( 'post', $post_id, $meta_key, true ) ) {

			Adsanity\Meta_Data::update( 'post', $post_id, $meta_key, '++' );

		} else {

			Adsanity\Meta_Data::update( 'post', $post_id, $meta_key, '1' );

		}

	}
}

/**
 * Checks to see if the click should be tracked based on rules, then stores the value
 * @param  int $post_id the ID of the ad that's being viewed
 */
function adsanity_click( $post_id ) {

	if ( ADSANITY_TRACK_THIS ) {

		$meta_key = adsanity_get_meta_key( 'clicks' );

		if ( Adsanity\Meta_Data::get( 'post', $post_id, $meta_key, true ) ) {

			Adsanity\Meta_Data::update( 'post', $post_id, $meta_key, '++' );

		} else {

			Adsanity\Meta_Data::update( 'post', $post_id, $meta_key, '1' );

		}

	}

}

// Check to see if we should track views/clicks for this request
function adsanity_should_we_track_this() {
	global $adsanity_tzstring;

	$adsanity_tzstring = adsanity_get_timezone_string();

	if ( ! $adsanity_track_this = apply_filters( 'adsanity_track_this', true ) ) {
		define( 'ADSANITY_TRACK_THIS', false );
		return false;
	}

	// Manual override
	if ( defined( 'ADSANITY_TRACK_THIS' ) ) {
		return ADSANITY_TRACK_THIS;
	}

	$options = get_option( ADSANITY_ADMIN_OPTIONS );
	if ( $options && is_array( $options ) && ! empty( $options['adsanity_ignore_roles'] ) ) {
		$roles = $options['adsanity_ignore_roles'];
		if ( is_array( $roles ) ) {
			// For each role check if user can
			foreach( $roles as $role ) {
				if ( current_user_can( $role ) ) {
					define( 'ADSANITY_TRACK_THIS', false );
					return false;
				}
			}
		} else {
			// Rare case of a single excluded role getting cast to a string
			if ( current_user_can( $roles ) ) {
				define( 'ADSANITY_TRACK_THIS', false );
				return false;
			}
		}
	}

	if ( AdSanityUtility::is_robot() ) {
		define( 'ADSANITY_TRACK_THIS', false );
		return false;
	}

	define( 'ADSANITY_TRACK_THIS', true );
	return true;

}
add_action( 'init', 'adsanity_should_we_track_this' );

/**
 * Utility function to calculate the timezone and return the appropriate Timezone string
 * @return string the converted timezone string
 */
function adsanity_get_timezone_string() {

	$gmt_offset = get_option( 'gmt_offset' );
	$tzstring = get_option( 'timezone_string' );

	// Remove old Etc/UTC/GMT mappings. Fallback to gmt_offset.
	if ( preg_match( '/(UTC[+-]|Etc|GMT)/i', $tzstring ) ) {
		$tzstring = '';
	}

	// Create a UTC+- zone if no timezone string exists
	if ( empty( $tzstring ) ) {
		$tzstring = timezone_name_from_abbr( null, $gmt_offset, true );
		if ( $tzstring === false ) {
			$tzstring = timezone_name_from_abbr( null, $gmt_offset, false );
		}
	}

	return $tzstring;

}


/**
 * Get the meta key for the current day
 * @param  string $click_or_view  possible values are 'views' and 'clicks'
 * @return string                the full meta key
 */
function adsanity_get_meta_key( $click_or_view = 'views' ) {

	// (DEFAULT) timestamp for midnight UTC today
	$meta_key = sprintf(
		'_%s-%s',
		$click_or_view,
		adsanity_get_timestamp()
	);

	return $meta_key;
}

function adsanity_get_timestamp() {

	global $adsanity_tzstring;

	// (DEFAULT) timestamp for midnight UTC today.
	$time = mktime( 0, 0, 0, date( 'n' ), date( 'j' ), date( 'Y' ) );

	// Check for wp location.
	if ( $adsanity_tzstring ) {

		// Set local timezone.
		date_default_timezone_set( $adsanity_tzstring );
		$local_day = date( 'j' );
		$local_month = date( 'n' );
		$local_year = date( 'Y' );

		// Reset back to UTC.
		date_default_timezone_set( 'UTC' );

		// (OVERRIDE) timestamp for midnight UTC today based on the local day.
		$time = mktime( 0, 0, 0, $local_month, $local_day, $local_year );

	}

	return $time;

}

/**
 * Deletes all view and click data in AdSanity.
 *
 * @return void
 */
function adsanity_delete_all_stats() {

	global $wpdb;

	$deleted_views = $wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_views-%'" );
	if ( false === $deleted_views ) {
		add_action( 'admin_notices', 'adsanity_no_deleted_views' );
	}

	$deleted_clicks = $wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_clicks-%'" );
	if ( false === $deleted_clicks ) {
		add_action( 'admin_notices', 'adsanity_no_deleted_clicks' );
	}

	if ( false !== $deleted_views && false !== $deleted_clicks ) {
		add_action( 'admin_notices', 'adsanity_all_stats_reset' );
	}

}

/**
 * Error message that displays when view data cannot be deleted.
 *
 * @return void
 */
function adsanity_no_deleted_views() {

	$class   = 'notice notice-error';
	$message = __( 'There was an error deleting resetting all ad views.', 'adsanity' );

	printf(
		'<div class="%1$s"><p>%2$s</p></div>',
		esc_attr( $class ),
		esc_html( $message )
	);
}

/**
 * Error message that displays when click data cannot be deleted.
 *
 * @return void
 */
function adsanity_no_deleted_clicks() {

	$class   = 'notice notice-error';
	$message = __( 'There was an error deleting resetting all ad clicks.', 'adsanity' );

	printf(
		'<div class="%1$s"><p>%2$s</p></div>',
		esc_attr( $class ),
		esc_html( $message )
	);
}

/**
 * Success message that displays when all data is deleted.
 *
 * @return void
 */
function adsanity_all_stats_reset() {

	$class   = 'notice notice-success';
	$message = __( 'All statistic data has been removed.', 'adsanity' );

	printf(
		'<div class="%1$s"><p>%2$s</p></div>',
		esc_attr( $class ),
		esc_html( $message )
	);
}
