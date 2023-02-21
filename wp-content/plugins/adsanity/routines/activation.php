<?php
/**
 * Redirects the user to the AdSanity welcome page on activation.
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
 * Redirect the user to the welcome page.
 *
 * @param string $plugin The slug of the plugin being activated.
 * @return void
 */
function adsanity_activation_redirect( $plugin ) {

	if ( $plugin === ADSANITY_SLUG ) {

		$options = get_option( ADSANITY_ADMIN_OPTIONS );
		$options['update-permalinks'] = 1;
		$options['adsanity_ignore_roles'] = array(
			'administrator' => 'administrator',
			'editor' => 'editor',
			'author' => 'author',
			'contributor' => 'contributor',
		);
		update_option( ADSANITY_ADMIN_OPTIONS, $options );

		// Do not redirect on network activate or bulk activate.
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		exit(
			wp_safe_redirect(
				add_query_arg(
					array(
						'post_type' => 'ads',
						'page' => 'adsanity-about',
					),
					admin_url( 'edit.php' )
				)
			)
		);

	}
}
add_action( 'activated_plugin', 'adsanity_activation_redirect' );
