<?php
/**
 * Beaver Builder random ad frontend.
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
	'group_ids'   => explode( ',', $settings->group ),
	'num_ads'     => 1,
	'num_columns' => 1,
	'align'       => $settings->align,
);

if ( $settings->max_width_enabled ) {
	$args['max_width'] = $settings->max_width;
}

adsanity_show_ad_group( $args );
