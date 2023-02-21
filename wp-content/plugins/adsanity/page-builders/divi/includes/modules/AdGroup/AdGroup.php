<?php
/**
 * Divi ad group module.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

class AdSanity_Divi_Ad_Group extends ET_Builder_Module {

	public function init() {
		$this->name                   = esc_html__( 'AdSanity - Ad Group', 'adsanity' );
		$this->slug                   = 'adsanity_divi_ad_group';
		$this->vb_support             = 'on';
		$this->has_advanced_fields    = false;
		$this->settings_modal_toggles = [];
		$this->advanced_fields        = [];
		$this->custom_css_fields      = [];
		$this->child_slug             = 'adsanity_divi_ad_group_item';
		$this->icon_path              = plugin_dir_path( __FILE__ ) . 'icon.svg';
	}

	public function get_fields() {

		$fields = [
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
			'adsanity_num_ads' => [
				'type'    => 'text',
				'label'   => __( 'Number of Ads', 'adsanity' ),
				'default' => '1',
			],
			'adsanity_num_cols' => [
				'type'    => 'text',
				'label'   => __( 'Number of Columns', 'adsanity' ),
				'default' => '1',
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

		if ( null === $content ) {
			return;
		}

		$ad_groups = [];

		// We need to separate the shortcodes
		$exploded = explode( '/][', $content );
		foreach ( $exploded as $key => $value ) {
			if ( 0 === $key ) {
				$exploded[ $key ] = $value . '/]';
			} elseif ( count( $exploded ) - 1 === $key ) {
				$exploded[ $key ] = '[' . $value;
			} else {
				$exploded[ $key ] = '[' . $value . '/]';
			}

			// Parse the shortcode
			$parsed = shortcode_parse_atts( $value );

			// Retrieve the ad group id
			if ( ! isset( $parsed['adsanity_group'] ) ) {
				continue;
			}
			$ad_group = $parsed['adsanity_group'];

			// Replace quotation marks
			$ad_group = str_replace( '&#8221;', '', $ad_group );
			$ad_group = str_replace( '&#8243;', '', $ad_group );
			$ad_groups[] = intval( $ad_group );
		}

		if ( ! count( $ad_groups ) ) {
			return;
		}

		$args = [
			'group_ids' => $ad_groups,
			'align'     => isset( $unprocessed_props['adsanity_align'] ) ? $unprocessed_props['adsanity_align'] : 'alignnone',
		];

		if ( ! isset( $unprocessed_props['adsanity_num_ads'] ) || intval( $unprocessed_props['adsanity_num_ads'] ) < 1 ) {
			$args['num_ads'] = 1;
		} else {
			$args['num_ads'] = intval( $unprocessed_props['adsanity_num_ads'] );
		}

		if ( ! isset( $unprocessed_props['adsanity_num_cols'] ) || intval( $unprocessed_props['adsanity_num_cols'] ) < 1 ) {
			$args['num_columns'] = 1;
		} else {
			$args['num_columns'] = intval( $unprocessed_props['adsanity_num_cols'] );
		}

		if (
			isset( $unprocessed_props['adsanity_max_width_enabled'] ) &&
			isset( $unprocessed_props['adsanity_max_width'] ) &&
			'on' === $unprocessed_props['adsanity_max_width_enabled']
		) {
			$args['max_width'] = intval( $unprocessed_props['adsanity_max_width'] );
		}

		ob_start();
		adsanity_show_ad_group( $args );
		return ob_get_clean();

	}

	public function add_new_child_text() {
		return esc_html__( 'Add New Ad Group', 'adsanity' );
	}

}

new AdSanity_Divi_Ad_Group();
