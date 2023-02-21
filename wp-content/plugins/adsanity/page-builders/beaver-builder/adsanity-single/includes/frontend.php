<?php
/**
 * Beaver Builder single ad module frontend.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

$args = array(
	'post_id'     => $settings->ad,
	'align'       => $settings->align,
);

if ( $settings->max_width_enabled ) {
	$args['max_width'] = $settings->max_width;
}

adsanity_show_ad( $args );
