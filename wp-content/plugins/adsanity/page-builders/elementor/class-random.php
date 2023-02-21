<?php
/**
 * Elementor Random Ad Widget
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
final class Random extends Widget_Base {

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
		return \__( 'AdSanity Random Ad', 'adsanity' );
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
		return 'adsanity-random';
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
		return 'elementor-adsanity elementor-adsanity-random';
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

		$this->start_controls_section(
			'adsanity_random_section',
			[
				'label' => esc_html__( 'Options', 'adsanity' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'group',
			[
				'classes'  => [ 'adsanity-elementor-ad-group-control' ],
				'label'    => esc_html__( 'Ad Group', 'adsanity' ),
				'type'     => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
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

		if ( ! is_array( $settings['group'] ) || empty( $settings['group'] ) ) {
			return;
		}

		$group_ids = [];
		foreach ( $settings['group'] as $group ) {
			$group = json_decode( $group );
			if ( ! isset( $group->id ) ) {
				continue;
			}
			$group_ids[] = intval( $group->id );
		}

		if ( empty( $group_ids ) ) {
			return;
		}

		$args = array(
			'group_ids'   => $group_ids,
			'num_ads'     => 1,
			'num_columns' => 1,
			'align'       => $settings['align'],
		);

		if ( $settings['max_width_enabled'] ) {
			$args['max_width'] = $settings['max_width'];
		}

		\adsanity_show_ad_group( $args );

	}

}
