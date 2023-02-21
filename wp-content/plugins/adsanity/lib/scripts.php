<?php
/**
 * Loads scripts and styles for the front and back end of the site.
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
 * Admin JavaScript and Stylesheet registration
 *
 * Hooks into the admin_enqueue_scripts action to register scripts and styles that
 * are needed throughout the plugin back-end
 */
add_action( 'admin_enqueue_scripts', 'adsanity_admin_script_style_registration', 1 );
function adsanity_admin_script_style_registration() {

	global $wp_version;

	$default_deps = array( 'jquery' );

	/*
	 * REGISTER JAVASCRIPT FILES
	 * 01. jQuery UI
	 * 02. Clipboard JS
	 * 03. Ad List
	 * 04. Ad Create/Edit
	 * 05. Ad Groups
	 * 06. Widgets
	 * 07. Stats
	 * 08. Settings
	/*************************************************************************************************************/
	$scripts = array();

	// 1. JQUERY-UI
	$jquery_ui_datepicker = array( 'jquery-ui-datepicker' );

	// 2. CLIPBOARD JS
	$scripts['adsanity-clipboardjs'] = array(
		ADSANITY_JS . 'clipboard.js',
		false,
		'1.6.1',
		true
	);

	// 3. AD LIST
	$scripts['adsanity-list'] = array( // ads-list
		ADSANITY_JS . 'ads-list.js',
		array_merge( $default_deps, array( 'adsanity-clipboardjs' ) ),
		ADSANITY_VERSION,
		true
	);

	// 4. AD CREATE/EDIT
	$scripts['adsanity-post-new'] = array( // ads-new
		ADSANITY_JS . 'ads-new.js',
		array_merge( $default_deps, $jquery_ui_datepicker, array( 'adsanity-clipboardjs', 'underscore', 'wp-color-picker', ) ),
		ADSANITY_VERSION,
		true
	);
	$scripts['adsanity-post'] = array( // ads-edit
		ADSANITY_JS . 'ads-edit.js',
		array_merge( $default_deps, $jquery_ui_datepicker, array( 'adsanity-clipboardjs', 'underscore', 'wp-color-picker', ) ),
		ADSANITY_VERSION,
		true
	);

	// 5. AD GROUPS
	$scripts['adsanity-groups'] = array(
		ADSANITY_JS . 'ads-group.js',
		array_merge( $default_deps, array( 'adsanity-clipboardjs' ) ),
		ADSANITY_VERSION,
		true
	);

	// 6. WIDGETS
	$scripts['adsanity-single-widget-admin'] = array(
		ADSANITY_JS . 'single-widgets-admin.js',
		array_merge( $default_deps, array( 'suggest' ) ),
		ADSANITY_VERSION,
		true
	);
	$scripts['adsanity-group-widget-admin'] = array(
		ADSANITY_JS . 'group-widgets-admin.js',
		array_merge( $default_deps, array( 'suggest' ) ),
		ADSANITY_VERSION,
		true
	);
	$scripts['adsanity-random-widget-admin'] = array(
		ADSANITY_JS . 'random-widgets-admin.js',
		array_merge( $default_deps, array( 'suggest' ) ),
		ADSANITY_VERSION,
		true
	);

	// 7. STATS
	$scripts['adsanity-custom-stats'] = array(
		ADSANITY_JS . 'custom-stats.js',
		array_merge( $default_deps, $jquery_ui_datepicker ),
		ADSANITY_VERSION,
		true
	);

	$scripts['adsanity-group-stats'] = array(
		ADSANITY_JS . 'stats-dashboard-group-block.js',
		$default_deps,
		ADSANITY_VERSION,
		true
	);

	// 8. SETTINGS
	$scripts['adsanity-settings'] = array(
		ADSANITY_JS . 'settings.js',
		$default_deps,
		ADSANITY_VERSION,
		true
	);

	// Register all of our scripts for later use
	foreach ( $scripts as $slug => $script ) {
		wp_register_script( $slug, $script[0], $script[1], $script[2], $script[3] );
	}

	/*
	 * REGISTER CSS FILES
	 * 01. Global
	 * 02. jQuery UI
	 * 03. Widgets
	 * 04. Stats
	 * 05. Default Ad
	 * 06. Settings
	/*************************************************************************************************************/
	$styles = array();

	// 1. GLOBAL
	$styles['adsanity-admin-global'] = array(
		ADSANITY_CSS . 'admin-global.css',
		array( 'wp-color-picker', ),
		ADSANITY_VERSION,
		'screen'
	);

	// 2. JQUERY-UI
	$styles['adsanity-jqueryui-datepicker'] = array(
		ADSANITY_CSS . 'jquery-ui-custom.css',
		false,
		'1.8.16',
		'screen'
	);

	// 3. WIDGETS
	$styles['adsanity-single-widgets-admin'] = array(
		ADSANITY_CSS . 'single-widgets-admin.css',
		array( 'adsanity-admin-global' ),
		ADSANITY_VERSION,
		'screen'
	);

	$styles['adsanity-group-widgets-admin'] = array(
		ADSANITY_CSS . 'group-widgets-admin.css',
		array( 'adsanity-admin-global' ),
		ADSANITY_VERSION,
		'screen'
	);

	$styles['adsanity-random-widgets-admin'] = array(
		ADSANITY_CSS . 'random-widgets-admin.css',
		array( 'adsanity-admin-global' ),
		ADSANITY_VERSION,
		'screen'
	);

	// 4. STATS
	$styles['adsanity-stats'] = array(
		ADSANITY_CSS . 'stats.css',
		array( 'adsanity-jqueryui-datepicker', 'adsanity-admin-global' ),
		ADSANITY_VERSION,
		'screen'
	);

	// 5. DEFAULT AD CSS
	$styles['adsanity-default-css'] = array(
		ADSANITY_CSS . 'widget-default.css',
		false,
		ADSANITY_VERSION,
		'screen'
	);

	// 6. SETTINGS CSS
	$styles['adsanity-settings'] = array(
		ADSANITY_CSS . 'settings.css',
		false,
		ADSANITY_VERSION,
		'screen'
	);

	// 7. AD EDIT CSS
	$styles['adsanity-post'] = array(
		ADSANITY_CSS . 'ads-edit.css',
		false,
		ADSANITY_VERSION,
		'all'
	);

	// Register all of our styles for later use
	foreach ( $styles as $slug => $style ) {
		wp_register_style( $slug, $style[0], $style[1], $style[2], $style[3] );
	}
}


/**
 * Public JavaScript and Stylesheet registration
 *
 * Hooks into the wp_enqueue_scripts action to register scripts and styles that
 * are needed on the front end
 */
add_action( 'wp_enqueue_scripts', 'adsanity_public_script_style_registration', 1 );
function adsanity_public_script_style_registration() {

	/*
	 * REGISTER PUBLIC CSS FILES
	 * 01. Ad Display Defaults
	/*************************************************************************************************************/
	$styles = array();

	// 1. DEFAULT AD CSS
	$styles['adsanity-default-css'] = array(
		ADSANITY_CSS . 'widget-default.css',
		false,
		ADSANITY_VERSION,
		'screen'
	);

	// Register all of our styles for later use
	foreach ( $styles as $slug => $style ) {
		wp_register_style( $slug, $style[0], $style[1], $style[2], $style[3] );
	}

	if ( ! is_admin() ) {
		wp_enqueue_style( 'adsanity-default-css' );
	}

}
