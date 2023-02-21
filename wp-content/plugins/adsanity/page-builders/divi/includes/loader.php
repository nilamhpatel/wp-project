<?php
/**
 * Divi modules.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

if ( ! class_exists( 'ET_Builder_Element' ) ) {
	return;
}

require_once plugin_dir_path( __FILE__ ) . 'modules/AdGroup/AdGroup.php';
require_once plugin_dir_path( __FILE__ ) . 'modules/AdGroupItem/AdGroupItem.php';
require_once plugin_dir_path( __FILE__ ) . 'modules/RandomAd/RandomAd.php';
require_once plugin_dir_path( __FILE__ ) . 'modules/SingleAd/SingleAd.php';
