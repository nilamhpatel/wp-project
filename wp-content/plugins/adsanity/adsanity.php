<?php
/**
 * Plugin Name: AdSanity
 * Description: Powerfully simple banner advertising management.
 * Plugin URI: https://www.adsanityplugin.com
 * Author: Pixel Jar
 * Author URI: https://www.pixeljar.com
 * Version: 1.9
 * License: GPL2
 * Text Domain: adsanity
 * Domain Path: /lang
 *
 * @package WordPress
 * @subpackage AdSanity
 *
 * Copyright (C) Feb 26, 2017  Pixel Jar  info@pixeljar.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

// Set up path constants.
define( 'ADSANITY', 'adsanity' );
define( 'ADSANITY_VERSION', '1.9' );
define( 'ADSANITY_URL', plugin_dir_url( __FILE__ ) );
define( 'ADSANITY_ABS', plugin_dir_path( __FILE__ ) );
define( 'ADSANITY_SLUG', plugin_basename( __FILE__ ) );
define( 'ADSANITY_REL', basename( dirname( __FILE__ ) ) );
define( 'ADSANITY_CPT', ADSANITY_ABS . 'custom-post-types/' );
define( 'ADSANITY_THEME', ADSANITY_ABS . 'theme-templates/' );
define( 'ADSANITY_WIDGETS', ADSANITY_ABS . 'widgets/' );
define( 'ADSANITY_LANG', dirname( plugin_basename( __FILE__ ) ) . '/lang' );
define( 'ADSANITY_VIEWS', ADSANITY_ABS . 'views/' );
define( 'ADSANITY_LIB', ADSANITY_ABS . 'lib/' );
define( 'ADSANITY_CSS', ADSANITY_URL . 'dist/css/' );
define( 'ADSANITY_JS', ADSANITY_URL . 'dist/js/' );
define( 'ADSANITY_ADMIN_OPTIONS', 'adsanity-options' );
define( 'ADSANITY_UPDATE_API', 'https://adsanityplugin.com/d05257d3' );
define( 'ADSANITY_MEMBER_LOGIN', 'https://adsanityplugin.com/wp-login.php?redirect_to=https://adsanityplugin.com/account/' );
define( 'ADSANITY_EOL', '2082672000' ); // Dec 31, 2035.
define(
	'ADSANITY_DEFAULT_AD_SIZES',
	[
		'1x1'     => '1x1 - Responsive',
		'1x2'     => '1x2 - Responsive',
		'1x3'     => '1x3 - Responsive',
		'1x4'     => '1x4 - Responsive',
		'2x1'     => '2x1 - Responsive',
		'3x1'     => '3x1 - Responsive',
		'4x1'     => '4x1 - Responsive',
		'6x1'     => '6x1 - Responsive',
		'8x1'     => '8x1 - Responsive',
		'10x1'    => '10x1 - Responsive',
		'88x31'   => '88x31 - Micro Bar',
		'120x60'  => '120x60 - Button 2',
		'120x90'  => '120x90 - Button 1',
		'120x240' => '120x240 - Vertical Banner',
		'120x600' => '120x600 - Skyscraper',
		'125x125' => '125x125 - Square Button',
		'160x600' => '160x600 - Wide Skyscraper',
		'180x150' => '180x150 - Rectangle',
		'200x90'  => '200x90',
		'200x200' => '200x200',
		'234x60'  => '234x60 - Half Banner',
		'240x400' => '240x400 - Vertical Rectangle',
		'250x250' => '250x250 - Square Pop-Up',
		'300x100' => '300x100 - 3:1 Rectangle',
		'300x250' => '300x250 - Medium Rectangle',
		'300x600' => '300x600 - Half Page Ad',
		'336x280' => '336x280 - Large Rectangle',
		'468x15'  => '468x15',
		'468x60'  => '468x60 - Full Banner',
		'720x300' => '720x300 - Pop-Under',
		'728x90'  => '728x90 - Leaderboard',
	]
);

// Internationalization.
load_plugin_textdomain( ADSANITY, false, ADSANITY_LANG );

// Utility Classes.
require_once ADSANITY_LIB . 'class-meta-data.php';

// Ads.txt Request checker.
require_once ADSANITY_LIB . 'adstxt.php';

// Scripts and styles.
require_once ADSANITY_LIB . 'scripts.php';

// Admin only stuff.
if ( is_admin() ) {

	// Update.
	require_once ADSANITY_ABS . 'routines/routines.php';
	register_activation_hook( __FILE__, array( 'AdSanityRoutines', 'activation' ) );
	register_deactivation_hook( __FILE__, array( 'AdSanityRoutines', 'deactivation' ) );
	register_uninstall_hook( __FILE__, array( 'AdSanityRoutines', 'uninstall' ) );

	// Initialization.
	require_once ADSANITY_LIB . 'initialization.php';

	// Function libraries.
	require_once ADSANITY_LIB . 'query.php';
	require_once ADSANITY_LIB . 'ajax.php';
	require_once ADSANITY_LIB . 'class-adsanity-export.php';

	// Subpages.
	require_once ADSANITY_ABS . 'admin-pages/about.php';
	require_once ADSANITY_ABS . 'admin-pages/add-ons.php';
	require_once ADSANITY_ABS . 'admin-pages/stats.php';
	require_once ADSANITY_ABS . 'admin-pages/settings.php';
	require_once ADSANITY_ABS . 'admin-pages/support.php';
	require_once ADSANITY_ABS . 'admin-pages/changelog.php';

	// Update Checks.
	require_once ADSANITY_ABS . 'update-engine/remote-api.php';
}

// Multi-site sub-folder specific.
if ( is_multisite() && ! is_subdomain_install() ) {
	require_once ADSANITY_LIB . 'multi-site.php';
}

// HTML5 Ads.
require_once ADSANITY_LIB . 'html5.php';

// Utilities.
require_once ADSANITY_LIB . 'utility.php';

// Tracking functions.
require_once ADSANITY_LIB . 'tracking.php';
require_once ADSANITY_LIB . 'template-tags.php';
require_once ADSANITY_LIB . 'shortcodes.php';

// Custom post types.
require_once ADSANITY_CPT . 'class-adsanity-ads-cpt.php';

// Page builders.
require_once ADSANITY_ABS . 'page-builders/page-builders.php';

// Widgets.
require_once ADSANITY_WIDGETS . 'single-ad.php';
require_once ADSANITY_WIDGETS . 'group-of-ads.php';
require_once ADSANITY_WIDGETS . 'random-ad.php';

// Additional libraries.
require_once ADSANITY_LIB . 'automatic-inclusion.php';

// Gutenberg.
require_once ADSANITY_LIB . 'gutenberg.php';

// REST API.
require_once ADSANITY_LIB . 'rest-api.php';
