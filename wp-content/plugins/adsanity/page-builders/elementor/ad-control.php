<?php
/**
 * Elementor Ad Control.
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

class Ad_Control extends \Elementor\Base_Data_Control {

	public function get_type() {
		return 'adsanity-ad';
	}

	protected function get_default_settings() {
		return 0;
	}

	public function get_default_value() {
		return 0;
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
	}

	public function enqueue() {}

}
