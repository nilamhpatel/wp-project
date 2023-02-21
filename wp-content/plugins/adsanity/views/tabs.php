<?php
/**
 * The tabs view when editing an ad.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}
?>
<div id="ad-source-tabs" class="nav-tab-wrapper">
	<?php

		$tabs = array(
			'internal' => array(
				'label'       => __( 'Ad Hosted On-Site', 'adsanity' ),
				'active_tabs' => array(
					'ad-size',
					'internal-ad-details',
					'postimagediv',
					'adsanity-notes',
				),
			),
			'external' => array(
				'label'       => __( 'External Ad Network', 'adsanity' ),
				'active_tabs' => array(
					'ad-size',
					'internal-ad-details',
					'ad-code',
					'adsanity-notes',
				),
			),
			'text' => array(
				'label'       => __( 'Text Ad', 'adsanity' ),
				'active_tabs' => array(
					'ad-size',
					'internal-ad-details',
					'ad-text',
					'adsanity-notes',
				),
			),
			'html5' => array(
				'label'       => 'HTML5',
				'active_tabs' => array(
					'ad-upload',
					'ad-size',
					'adsanity-notes',
				),
			),
		);

		// Do not include ad-chart
		$tabs = apply_filters( 'adsanity_ad_edit_tabs', $tabs );

		wp_localize_script(
			'adsanity-post',
			'ADSANITY_AD_TABS',
			$tabs
		);

		wp_localize_script(
			'adsanity-post-new',
			'ADSANITY_AD_TABS',
			$tabs
		);

		$active_tab = 'internal';

		// Booleans for the type of ad
		$is_internal = Adsanity\Meta_Data::get( 'post', $post->ID, '_thumbnail_id', true );
		$is_external = Adsanity\Meta_Data::get( 'post', $post->ID, '_code', true );
		$is_text     = Adsanity\Meta_Data::get( 'post', $post->ID, '_text', true );
		$is_html     = Adsanity\Meta_Data::get( 'post', $post->ID, 'ad_src', true );

		if ( $is_internal ) {
			$active_tab = 'internal';
		}

		if ( $is_external ) {
			$active_tab = 'external';
		}

		if ( $is_text ) {
			$active_tab = 'text';
		}

		if ( $is_html ) {
			$active_tab = 'html5';
		}

		$active_tab = apply_filters( 'adsanity_ad_edit_active_tab', $active_tab, $tabs, $post );

		if ( $is_external || $is_html ) {
			wp_localize_script(
				'adsanity-post',
				'ADSANITY_DO_NOT_CHART_CLICKS',
				array( true )
			);
		}

		$tab_format = '<a href="#%s" class="nav-tab%s">
			%s
		</a>';

		foreach ( $tabs as $slug => $tab ) {
			printf(
				$tab_format,
				esc_attr( $slug ),
				$slug === $active_tab ? ' nav-tab-active' : '',
				esc_html( $tab['label'] )
			);
		}

	?>
</div>
