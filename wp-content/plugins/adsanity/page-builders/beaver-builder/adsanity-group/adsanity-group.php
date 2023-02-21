<?php
/**
 * Beaver Builder ad group module.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

class AdSanity_Beaver_Builder_Group extends FLBuilderModule {

	public function __construct() {
		parent::__construct( array(
			'name'        => __( 'AdSanity Ad Group', 'adsanity' ),
			'description' => __( 'Place an ad group', 'adsanity' ),
			'category'    => __( 'AdSanity', 'adsanity' ),
			'dir'         => plugin_dir_path( __FILE__ ),
			'url'         => plugin_dir_url( __FILE__ ),
		) );
	}

}

FLBuilder::register_module( 'AdSanity_Beaver_Builder_Group', array(
	'adsanity-group-tab' => array(
		'title'    => __( 'Settings', 'adsanity' ),
		'sections' => array(
			'adsanity-group-section' => array(
				'title' => __( 'Ad Settings', 'adsanity' ),
				'fields' => array(
					'group' => array(
						'type'        => 'suggest',
						'placeholder' => __( 'Ad Group', 'adsanity' ),
						'label'       => __( 'Group', 'adsanity' ),
						'action'      => 'fl_as_terms',
						'data'        => 'ad-group',
					),
					'align' => array(
						'type'    => 'select',
						'label'   => __( 'Align', 'adsanity' ),
						'default' => 'alignnone',
						'options' => array(
							'alignnone'   => __( 'None', 'adsanity' ),
							'alignleft'   => __( 'Left', 'adsanity' ),
							'aligncenter' => __( 'Center', 'adsanity' ),
							'alignright'  => __( 'Right', 'adsanity' ),
						),
					),
					'num_ads' => array(
						'type'    => 'text',
						'label'   => __( 'Number of Ads', 'adsanity' ),
						'default' => '1',
					),
					'num_cols' => array(
						'type'    => 'text',
						'label'   => __( 'Number of Columns', 'adsanity' ),
						'default' => '1',
					),
					'max_width_enabled' => array(
						'type'    => 'select',
						'label'   => __( 'Max Width Enabled?', 'adsanity' ),
						'default' => false,
						'options' => array(
							false => __( 'No', 'adsanity' ),
							true  => __( 'Yes', 'adsanity' ),
						),
						'toggle' => array(
							true => array(
								'fields' => array( 'max-width' ),
							),
						),
					),
					'max_width' => array(
						'type'  => 'text',
						'label' => __( 'Max Width (px)', 'adsanity' ),
					),
				),
			),
		),
	),
) );
