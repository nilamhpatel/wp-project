<?php
/**
 * Elementor Ad Group widget
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
final class Group extends Widget_Base {

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
		return \__( 'AdSanity Ad Group', 'adsanity' );
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
		return 'adsanity-group';
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
		return 'elementor-adsanity elementor-adsanity-group';
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
			'adsanity_group_section',
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

		$this->add_control(
			'num_ads',
			[
				'label'   => esc_html__( 'Number of Ads', 'adsanity' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1,
				'default' => 1,
			]
		);

		$this->add_control(
			'num_cols',
			[
				'label'   => esc_html__( 'Number of Columns', 'adsanity' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1,
				'default' => 1,
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

		$args = [
			'group_ids'   => $group_ids,
			'num_ads'     => $settings['num_ads'],
			'num_columns' => $settings['num_cols'],
			'align'       => $settings['align'],
		];

		if ( $settings['max_width_enabled'] ) {
			$args['max_width'] = $settings['max_width'];
		}

		\adsanity_show_ad_group( $args );

	}

}
