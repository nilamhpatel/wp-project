<?php
/**
 * Elementor Widget Base.
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
 * AdSanity Single Ad Elementor widget.
 */
abstract class Widget_Base extends \Elementor\Widget_Base {

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
		return $this->get_name();
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget help URL.
	 */
	public function get_custom_help_url() {
		return 'https://adsanityplugin.com/help/';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'adsanity' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'ad', 'ads', 'adsanity' ];
	}

	/**
	 * Register core controls required for all AdSanity widgets.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function register_adsanity_core_controls() {

		$this->add_control(
			'align',
			[
				'label'   => esc_html__( 'Align', 'adsanity' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'alignnone',
				'options' => [
					'alignnone'   => esc_html__( 'None', 'adsanity' ),
					'alignleft'   => esc_html__( 'Left', 'adsanity' ),
					'aligncenter' => esc_html__( 'Center', 'adsanity' ),
					'alignright'  => esc_html__( 'Right', 'adsanity' ),
				],
			]
		);

		$this->add_control(
			'max_width_enabled',
			[
				'label'        => esc_html__( 'Max Width Enabled?', 'adsanity' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'adsanity' ),
				'label_off'    => esc_html__( 'Hide', 'adsanity' ),
				'return_value' => 'on',
				'default'      => '',
			]
		);

		$this->add_control(
			'max_width',
			[
				'label'     => esc_html__( 'Max Width', 'adsanity' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'step'      => 1,
				'default'   => 100,
				'condition' => [
					'max_width_enabled' => 'on',
				],
			]
		);

	}

}
