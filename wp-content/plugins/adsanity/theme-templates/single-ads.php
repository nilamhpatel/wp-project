<?php
/**
 * Click processing file included on ad permalink requests.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

	/*
		WHEN AN AD HAS BEEN CLICKED
		INCREASE THE CLICK COUNT FOR TODAY
	*/
	if ( have_posts() ) {

		the_post();

		// Trigger custom code before we track the click
		do_action( 'adsanity_before_track_click', $post );

		// Track the click
		adsanity_click( $post->ID );

		// Trigger custom code after we track the click
		do_action( 'adsanity_before_redirect', $post );

		if ( isset( $_GET['r'] ) && ! empty( $_GET['r'] ) ) {

			// Redirect to a custom tracking URL if the parameter is present
			wp_redirect( esc_url_raw( $_GET['r'] ) );

		} else {

			$url = str_replace(
				array(
					'[link]',
					'[timestamp]',
				),
				array(
					get_permalink( $post->ID ),
					time(),
				),
				Adsanity\Meta_Data::get( 'post', $post->ID, '_url', true )
			);

			// Redirect to the Tracking URL specified for the ad
			wp_redirect( esc_url_raw( $url ) );

		}
		exit;

	} else {

		$title = apply_filters(
			'adsanity_click_not_found_title',
			sprintf( 'AdSanity %s', __( 'Error', 'adsanity' ) ),
			$post
		);
		$message = apply_filters(
			'adsanity_click_not_found_message',
			__( 'You have reached this URL in error.', 'adsanity' ),
			$post
		);

		// Show an error because the ad does not exist, probably because caches were not cleared
		wp_die( $message, $title );

	}
