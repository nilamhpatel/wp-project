<?php
/**
 * Beaver Builder Single Ad Module.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

class AdSanity_Beaver_Builder_Single extends FLBuilderModule {

	public function __construct() {
		parent::__construct( array(
			'name'        => __( 'AdSanity Single Ad', 'adsanity' ),
			'description' => __( 'Place a single ad', 'adsanity' ),
			'category'    => __( 'AdSanity', 'adsanity' ),
			'dir'         => plugin_dir_path( __FILE__ ),
			'url'         => plugin_dir_url( __FILE__ ),
		) );
	}

}

FLBuilder::register_module( 'AdSanity_Beaver_Builder_Single', array(
	'adsanity-single-tab' => array(
		'title'    => __( 'Settings', 'adsanity' ),
		'sections' => array(
			'adsanity-single-section' => array(
				'title' => __( 'Ad Settings', 'adsanity' ),
				'fields' => array(
					'ad'     => array(
						'type'        => 'suggest',
						'placeholder' => __( 'Ad', 'adsanity' ),
						'label'       => __( 'Ad', 'adsanity' ),
						'action'      => 'fl_as_posts',
						'data'        => 'ads',
						'limit'       => 1,
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
