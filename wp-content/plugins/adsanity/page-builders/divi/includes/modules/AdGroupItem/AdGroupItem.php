<?php
/**
 * Divi ad group item.
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
 * Basic Call To Action module (title, content, and button) with NO builder support
 * This module appears as placeholder box on Visual Builder
 *
 * @since 1.0.0
 */
class AdSanity_Divi_Ad_Group_Item extends ET_Builder_Module {

	public function init() {
		$this->name                        = esc_html__( 'Ad Group', 'adsanity' );
		$this->plural                      = esc_html__( 'Ad Groups', 'adsanity' );
		$this->slug                        = 'adsanity_divi_ad_group_item';
		$this->vb_support                  = 'on';
		$this->type                        = 'child';
		$this->has_advanced_fields         = false;
		$this->no_render                   = true;
		$this->advanced_setting_title_text = __( 'Ad Group', 'adsanity' );

		$this->settings_modal_toggles = [
			'general' => [
				'toggles' => [
					'main_content' => esc_html__( 'Ad Group', 'adsanity' ),
				],
			],
		];

	}

	private function get_groups() {

		// Display all ads that are not expired.
		$groups = get_terms( 'ad-group', [
			'orderby' => 'name',
			'order' => 'ASC',
		] );

		$options = [];
		foreach ( $groups as $group ) {
			$options[ $group->term_id ] = $group->name;
		}

		return $options;

	}

	public function get_fields() {
		$fields = [
			'adsanity_group'     => [
				'type'    => 'select',
				'label'   => __( 'Ad Group', 'adsanity' ),
				'options' => $this->get_groups(),
			],
		];
		return $fields;
	}

}

new AdSanity_Divi_Ad_Group_Item();
