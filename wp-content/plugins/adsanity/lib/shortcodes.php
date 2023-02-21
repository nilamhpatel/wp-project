<?php
/**
 * Shortcode related functionality.
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
 * Registers the "adsanity_ad_group" shortcode for use wherever shortcodes are allowed
 * @param  array $atts    accepted shortcode attributes: num_ads, num_columns, group_ids (comma
 *                        separated)
 * @return string         html output from the template tag
 */
function adsanity_ad_group( $atts = array() ) {

	$atts['group_ids'] = array_map( 'intval', explode( ',', $atts['group_ids'] ) );
	$atts['return']    = true;
	$shortcode         = '<div class="adsanity-shortcode">';
	$shortcode        .= adsanity_show_ad_group( $atts );
	$shortcode        .= '</div>';

	return $shortcode;
}
add_shortcode( 'adsanity_group', 'adsanity_ad_group' );


/**
 * Registers the "adsanity" (legacy) and "adsanity_ad" shortcodes for use wherever shortcodes are
 * allowed
 * @param  array $atts  accepted shortcode attributes: id, align
 * @return string       html output from the template tag
 */
function adsanity_shortcode( $atts = array() ) {

	$defaults = array(
		'id'        => 1,
		'align'     => 'alignnone',
		'max_width' => false,
	);
	$atts = extract( shortcode_atts( $defaults, $atts ), EXTR_SKIP );

	$args = array(
		'is_widget' => false,
		'post_id'   => intval( $id ),
		'align'     => $align,
		'return'    => true,
		'max_width' => $max_width,
	);
	return adsanity_show_ad( $args );

}
add_shortcode( 'adsanity', 'adsanity_shortcode' );


///////////////////////////////////////////////////////////////////////////////

// set up translations and data for tinymce
function adsanity_tinymce_javascript_object() {

	global $current_screen;

	if ( is_admin() ) {
		?>
		<script type="text/javascript">
			var AdSanityMCE = {
				adsanity_ad_ads: [
					<?php
						$ads = AdSanityQuery::get_all_ads();
						if ( ! is_wp_error( $ads ) ) {
							foreach ( $ads as $ad ) {
								printf(
									'{ text: \'%s\', value: \'%s\' },',
									esc_js( $ad->post_title ),
									$ad->ID
								);
							}
						}
					?>
				],
				adsanity_ad_ads_label: '<?php _e( 'Select an Ad', 'adsanity' ); ?>',
				adsanity_ad_align: [
					{ text: '<?php _e( 'None', 'adsanity' ); ?>', value: 'alignnone' },
					{ text: '<?php _e( 'Left', 'adsanity' ); ?>', value: 'alignleft' },
					{ text: '<?php _e( 'Center', 'adsanity' ); ?>', value: 'aligncenter' },
					{ text: '<?php _e( 'Right', 'adsanity' ); ?>', value: 'alignright' },
				],
				adsanity_max_width_label: '<?php _e( 'Max Width (px)', 'adsanity' ); ?>',
				adsanity_max_width_enabled_label: '<?php _e( 'Max Width Enabled?', 'adsanity' ); ?>',
				adsanity_ad_align_label: '<?php _e( 'Align', 'adsanity' ); ?>',
				adsanity_ad_tooltip: '<?php _e( 'Insert Ad', 'adsanity' ); ?>',
				adsanity_ad_modal_title: '<?php _e( 'Insert Single Ad', 'adsanity' ); ?>',
				adsanity_ad_group_tooltip: '<?php _e( 'Insert Ad Group', 'adsanity' ); ?>',
				adsanity_ad_group_modal_title: '<?php _e( 'Insert Ad Group', 'adsanity' ); ?>',
				adsanity_ad_group_ad_groups: [
					<?php
						$ad_groups = get_terms( 'ad-group' );
						foreach ( $ad_groups as $ad_group ) {
							printf(
								'{ text: \'%s\', value: \'%s\' },',
								esc_js( $ad_group->name ),
								$ad_group->term_id
							);
						}
					?>
				],
				adsanity_ad_group_groups_label: '<?php _e( 'Select an Ad Group', 'adsanity' ); ?>',
				adsanity_ad_group_num_ads_label: '<?php _e( 'Number of Ads', 'adsanity' ); ?>',
				adsanity_ad_group_num_columns_label: '<?php _e( 'Number of columns', 'adsanity' ); ?>',
				empty_ad_group_fields: '<?php _e( 'Please fill in all fields in a popup.', 'adsanity' ); ?>',
			};
		</script>
		<?php
	}
}
add_action( 'admin_head','adsanity_tinymce_javascript_object' );

// registers the buttons for use
function register_adsanity_buttons( $buttons = array() ) {

	array_push( $buttons, 'adsanity_ad', 'adsanity_ad_group' );
	return $buttons;

}

// add the button to the tinyMCE bar
function add_adsanity_tinymce_plugin( $plugins = array() ) {

	global $post;

	if ( isset( $post->post_type ) && 'ads' === $post->post_type ) {
		return $plugins;
	}

	$plugins['AdSanity'] = ADSANITY_JS . 'tinymce.shortcodes.js';
	return $plugins;

}

// filters the tinyMCE buttons and adds our custom buttons
function adsanity_shortcode_buttons() {

	// Don't bother doing this stuff if the current user lacks permissions
	if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
		return false;
	}

	// Add only in Rich Editor mode
	if ( get_user_option( 'rich_editing' ) == 'true' ) {

		// filter the tinyMCE buttons and add our own
		add_filter( 'mce_external_plugins', 'add_adsanity_tinymce_plugin' );
		add_filter( 'mce_buttons', 'register_adsanity_buttons' );

	}

}

// init process for button control
add_action( 'init', 'adsanity_shortcode_buttons' );
