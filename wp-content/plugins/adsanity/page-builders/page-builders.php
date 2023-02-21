<?php
/**
 * EModules for page builders.
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
 * Beaver Builder
 */
require_once plugin_dir_path( __FILE__ ) . 'beaver-builder/beaver-builder.php';

/**
 * Divi
 */
require_once plugin_dir_path( __FILE__ ) . 'divi/divi.php';

/**
 * Elementor.
 */
require_once plugin_dir_path( __FILE__ ) . 'elementor/class-elementor.php';
