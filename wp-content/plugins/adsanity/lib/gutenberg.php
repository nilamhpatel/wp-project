<?php
/**
 * Gutenberg/Block Editor related functionality.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

class AdSanity_Gutenberg {

	function __construct() {}

	/**
	 * Hooks into WordPress
	 *
	 * @return void
	 */
	public static function hooks() {

		// Actions
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'scripts' ) );
		add_action( 'init', array( __CLASS__, 'register_block_types' ) );

		// Filters
		add_filter( 'block_categories_all', array( __CLASS__, 'block_category' ), 10, 1 );

	}

	/**
	 * Enqueue the styles and scripts specific to admin-side Gutenberg blocks
	 *
	 * @return void
	 */
	public static function scripts() {

		wp_enqueue_script(
			'adsanity-blocks',
			ADSANITY_JS . 'blocks.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-url', 'wp-api-fetch', 'wp-element', 'wp-components', ),
			ADSANITY_VERSION
		);

		wp_enqueue_style(
			'adsanity-blocks',
			ADSANITY_CSS . 'blocks.css',
			array( 'adsanity-default-css', ),
			ADSANITY_VERSION
		);

		wp_enqueue_style(
			'adsanity-blocks-widget-default',
			ADSANITY_CSS . 'widget-default.css',
			array(),
			ADSANITY_VERSION
		);

		wp_localize_script(
			'adsanity-blocks',
			'ADSANITY_ADD_ONS_INFO',
			array(
				'conditional' => function_exists( 'run_adsanity_caa' ),
				'user_role'   => function_exists( 'run_adsanity_urav' ),
			)
		);

	}

	/**
	 * Register blocks to handle rendered via PHP
	 *
	 * @return void
	 */
	public static function register_block_types() {

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type( 'adsanity/single-ad', array(
			'attributes' => array(
				'post_id' => array(
					'type' => 'number',
				),
				'align' => array(
					'type' => 'string',
					'default' => 'alignnone',
				),
				'max_width' => array(
					'type' => 'number',
					'default' => 0,
				),
				// Send attribute of return => true so that the ad is not printed
				'return' => array(
					'type'    => 'boolean',
					'default' => true,
				),
			),
			'render_callback' => 'adsanity_show_ad',
		) );

		register_block_type( 'adsanity/random-ad', array(
			'attributes' => array(
				'group_ids' => array(
					'type' => 'array',
				),
				'num_ads' => array(
					'type'    => 'number',
					'default' => 1,
				),
				'num_columns' => array(
					'type'    => 'number',
					'default' => 1,
				),
				'align' => array(
					'type' => 'string',
					'default' => 'alignnone',
				),
				'max_width' => array(
					'type' => 'number',
					'default' => 0,
				),
				// Send attribute of return => true so that the ad is not printed
				'return' => array(
					'type'    => 'boolean',
					'default' => true,
				),
			),
			'render_callback' => 'adsanity_show_ad_group',
		) );

		register_block_type( 'adsanity/ad-group', array(
			'attributes' => array(
				'group_ids' => array(
					'type' => 'array',
				),
				'num_ads' => array(
					'type'    => 'number',
					'default' => 1,
				),
				'num_columns' => array(
					'type'    => 'number',
					'default' => 1,
				),
				'align' => array(
					'type' => 'string',
					'default' => 'alignnone',
				),
				'max_width' => array(
					'type' => 'number',
					'default' => 0,
				),
				// Send attribute of return => true so that the ad is not printed
				'return' => array(
					'type'    => 'boolean',
					'default' => true,
				),
			),
			'render_callback' => 'adsanity_show_ad_group',
		) );

	}

	/**
	 * Register a custom block category
	 *
	 * @return array
	 */
	public static function block_category( $categories ) {

		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'adsanity',
					'title' => __( 'AdSanity', 'adsanity' ),
				),
			)
		);

	}

}

AdSanity_Gutenberg::hooks();
