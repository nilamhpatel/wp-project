<?php
/**
 * Divi single ad module.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

class AdSanity_Divi_Single_Ad extends ET_Builder_Module {

	public function init() {
		$this->name                   = esc_html__( 'AdSanity - Single Ad', 'adsanity' );
		$this->slug                   = 'adsanity_divi_single_ad';
		$this->vb_support             = 'on';
		$this->has_advanced_fields    = false;
		$this->settings_modal_toggles = [];
		$this->advanced_fields        = [];
		$this->custom_css_fields      = [];
		$this->icon_path              = plugin_dir_path( __FILE__ ) . 'icon.svg';
	}

	private function get_ads() {

		$now = time();

		// Display all ads that are not expired.
		$ads = get_posts( [
			'post_type'     => 'ads',
			'nopaging'      => true,
			'no_found_rows' => true,
			'meta_query'    => [ // phpcs:ignore WordPress.DB.SlowDBQuery
				[
					'key'     => '_end_date',
					'value'   => $now,
					'type'    => 'numeric',
					'compare' => '>=',
				],
			],
		] );

		$options = [];
		foreach ( $ads as $ad ) {
			$options[ $ad->ID ] = $ad->post_title;
		}

		return $options;

	}

	public function get_fields() {

		$fields = [
			'adsanity_ad'     => [
				'type'    => 'select',
				'label'   => __( 'Ad', 'adsanity' ),
				'options' => $this->get_ads(),
			],
			'adsanity_align' => [
				'type'    => 'select',
				'label'   => __( 'Align', 'adsanity' ),
				'default' => 'alignnone',
				'options' => [
					'alignnone'   => __( 'None', 'adsanity' ),
					'alignleft'   => __( 'Left', 'adsanity' ),
					'aligncenter' => __( 'Center', 'adsanity' ),
					'alignright'  => __( 'Right', 'adsanity' ),
				],
			],
			'adsanity_max_width_enabled' => [
				'type'    => 'select',
				'label'   => __( 'Max Width Enabled?', 'adsanity' ),
				'default' => 'off',
				'options' => [
					'off' => __( 'No', 'adsanity' ),
					'on'  => __( 'Yes', 'adsanity' ),
				],
			],
			'adsanity_max_width' => [
				'type'    => 'text',
				'label'   => __( 'Max Width (px)', 'adsanity' ),
				'show_if' => [
					'adsanity_max_width_enabled' => 'on',
				],
			],
		];


		return $fields;
	}

	public function get_settings_modal_toggles() {
		return [];
	}

	public function render( $unprocessed_props, $content = null, $render_slug ) {

		if ( ! isset( $unprocessed_props['adsanity_ad'] ) ) {
			return;
		}

		$args = [
			'post_id' => intval( $unprocessed_props['adsanity_ad'] ),
			'align'   => isset( $unprocessed_props['adsanity_align'] ) ? $unprocessed_props['adsanity_align'] : 'alignnone',
		];

		if (
			isset( $unprocessed_props['adsanity_max_width_enabled'] ) &&
			isset( $unprocessed_props['adsanity_max_width'] ) &&
			'on' === $unprocessed_props['adsanity_max_width_enabled']
		) {
			$args['max_width'] = intval( $unprocessed_props['adsanity_max_width'] );
		}

		ob_start();
		adsanity_show_ad( $args );
		return ob_get_clean();

	}

}

new AdSanity_Divi_Single_Ad();
