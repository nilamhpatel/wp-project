<?php
/**
 * Elementor hookups
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

namespace adsanity\elementor;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

/**
 * Handles custom Elementor widgets for AdSanity.
 */
class Elementor {

	/**
	 * Hook into WordPress. Hooks are Elementor-specific and will only run if base is active.
	 *
	 * @return void
	 */
	public static function hooks() {

		\add_action( 'elementor/widgets/register', [ __CLASS__, 'register_widgets' ], 10, 1 );
		\add_action( 'elementor/elements/categories_registered', [ __CLASS__, 'categories' ], 10, 1 );
		\add_action( 'elementor/editor/after_enqueue_scripts', [ __CLASS__, 'scripts' ] );

	}

	/**
	 * Register core Elementor widgets.
	 *
	 * @param  object $widgets_manager Widgets manager object.
	 * @return void
	 */
	public static function register_widgets( $widgets_manager ) {

		// Include the widget base first.
		require_once plugin_dir_path( __FILE__ ) . 'class-widget-base.php';

		// Single ad widget.
		require_once plugin_dir_path( __FILE__ ) . 'class-single.php';
		$single = new Single();
		$single->add_style_depends( 'adsanity-elementor' );
		$widgets_manager->register( $single );

		// Group ad widget.
		require_once plugin_dir_path( __FILE__ ) . 'class-group.php';
		$widgets_manager->register( new Group() );

		// Random ad widget.
		require_once plugin_dir_path( __FILE__ ) . 'class-random.php';
		$widgets_manager->register( new Random() );

		\do_action( 'adsanity_elementor_register_widgets', $widgets_manager );

	}

	/**
	 * Add custom widget category for AdSanity.
	 *
	 * @param  object $elements_manager Elements manager object.
	 * @return void
	 */
	public static function categories( $elements_manager ) {

		$elements_manager->add_category(
			'adsanity',
			[
				'title' => esc_html__( 'AdSanity', 'plugin-name' ),
				'icon' => 'fa fa-plug',
			]
		);

	}

	/**
	 * Register scripts.
	 *
	 * @return void
	 */
	public static function scripts() {

		\wp_enqueue_script(
			'adsanity-elementor',
			plugin_dir_url( __FILE__ ) . 'elementor.js',
			[ 'jquery' ],
			ADSANITY_VERSION,
			true
		);

		\wp_localize_script(
			'adsanity-elementor',
			'ADSANITY_ELEMENTOR',
			[
				'css_urls' => apply_filters(
					'adsanity_elementor_editor_css',
					[
						plugin_dir_url( __FILE__ ) . 'elementor.css',
					]
				),
				'rest_url' => rest_url( '/wp/v2/' ),
			]
		);

	}

}

Elementor::hooks();
