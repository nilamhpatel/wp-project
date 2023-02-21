<?php
/**
 * Elementor Single Ad widget.
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
 * AdSanity Random Ad Elementor widget.
 */
final class Single extends Widget_Base {

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return \__( 'AdSanity Single Ad', 'adsanity' );
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'adsanity-single';
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'elementor-adsanity elementor-adsanity-single';
	}

	private function get_default_ad() {
		// error_log(print_r($this->get_settings_for_display('ad'), true));
		// error_log(print_r($this->get_init_settings(), true));
	}

	/**
	 * Register widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->get_default_ad();

		$this->start_controls_section(
			'adsanity_single_section',
			[
				'label' => esc_html__( 'Options', 'adsanity' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'ad',
			[
				'classes'  => [ 'adsanity-elementor-ad-control' ],
				'label'    => esc_html__( 'Ad', 'adsanity' ),
				'type'     => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
			]
		);

		$this->register_adsanity_core_controls();
		$this->end_controls_section();

	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$ad = json_decode( $settings['ad'] );

		if ( ! isset( $ad->id ) ) {
			return;
		}

		$args = [
			'post_id' => intval( $ad->id ),
			'align'   => $settings['align'],
		];

		if ( $settings['max_width_enabled'] ) {
			$args['max_width'] = $settings['max_width'];
		}

		\adsanity_show_ad( $args );

	}

}
