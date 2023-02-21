<?php
/**
 * Removes the ad permalink from the rewrite rules on deactivation.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

// Flush all rewrite rules.
flush_rewrite_rules();
