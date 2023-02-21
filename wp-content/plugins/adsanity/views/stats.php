<?php
/**
 * The stats main view file.
 *
 * @package WordPress
 * @subpackage AdSanity
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

	$is_dashboard_tab = ( ! isset( $_GET['tab'] ) || ( isset( $_GET['tab'] ) && 'dashboard' === $_GET['tab'] ) ) ? true : false; // phpcs:ignore WordPress.Security.NonceVerification
?>
<div class="wrap">
	<h1 class="wp-heading-inline">AdSanity: <?php esc_html_e( 'Reports', 'adsanity' ); ?></h1>
	<?php do_action( 'adsanity_after_reports_page_heading' ); ?>
	<h2 id="report-type-tabs" class="nav-tab-wrapper">
	<?php

	$tabs = array( // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		'dashboard' => __( 'Dashboard', 'adsanity' ),
		'custom'    => __( 'Custom Reports', 'adsanity' ),
	);

	// Allows tabs to be extended.
	$tabs = apply_filters( 'adsanity_reports_tabs', $tabs ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride

	wp_localize_script(
		'adsanity-post',
		'ADSANITY_REPORTS_TABS',
		$tabs
	);

	$active_tab = 'dashboard';

	// Booleans for the tabs.
	$is_dashboard = ( ! isset( $_GET['tab'] ) || ( isset( $_GET['tab'] ) && 'dashboard' === $_GET['tab'] ) ) ? true : false; // phpcs:ignore WordPress.Security.NonceVerification
	$is_custom    = ( isset( $_GET['tab'] ) && 'custom' === $_GET['tab'] ) ? true : false; // phpcs:ignore WordPress.Security.NonceVerification

	if ( $is_dashboard ) {
		$active_tab = 'dashboard';
	}

	if ( $is_custom ) {
		$active_tab = 'custom';
	}

	$active_tab = apply_filters( 'adsanity_reports_active_tab', $active_tab, $tabs );

	$tab_format = '<a href="%s" class="nav-tab%s">
		%s
	</a>';

	foreach ( $tabs as $tab_slug => $tab_name ) {
		printf(
			$tab_format,
			esc_attr(
				add_query_arg(
					array(
						'post_type' => 'ads',
						'page'      => 'adsanity-stats',
						'tab'       => $tab_slug,
					),
					admin_url( 'edit.php' )
				)
			),
			( $tab_slug === $active_tab ? ' nav-tab-active' : '' ),
			esc_html( $tab_name )
		);
	}

	?>
	</h2>

	<?php
	if ( $is_dashboard ) {

		require_once ADSANITY_VIEWS . 'stats-dashboard.php';

	} elseif ( $is_custom ) {

		require_once ADSANITY_VIEWS . 'stats-custom.php';

	} else {

		$template = apply_filters( 'adsanity_reports_view', '', $active_tab, $tabs );
		if ( file_exists( $template ) ) {

			require_once $template;

		}

	}
	?>

</div>
<div class="clear"></div>
