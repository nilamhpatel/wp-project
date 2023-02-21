<?php
/**
 * Deletes the options and rewrite ruls on uninstall.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

// Delete the AdSanity options.
delete_option( 'adsanity-options' );
delete_option( 'adsanity-db-version' );

// Flush all rewrite rules.
flush_rewrite_rules();
