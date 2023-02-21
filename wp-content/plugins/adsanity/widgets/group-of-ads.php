<?php
/**
 * The ad group widget.
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
 * New WordPress Widget format
 * Wordpress 2.8 and above
 *
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class adsanity_group_ad_Widget extends WP_Widget {

	/**
	 * Default number of ads.
	 *
	 * @var $num_ads
	 */
	var $num_ads = 4;

	/**
	 * Default number of columns.
	 *
	 * @var $num_columns
	 */
	var $num_columns = 1;

	/**
	 * Constructor
	 *
	 * @return void
	 **/
	function __construct() {

		$widget_ops = array(
			'classname'                   => 'widget adsanity-group',
			'description'                 => sprintf( __( 'Display a group of %s Ad units.', 'adsanity' ), 'Adsanity' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct(
			'adsanity-group',
			sprintf( 'AdSanity - %s', __( 'Ad Group', 'adsanity' ) ),
			$widget_ops
		);
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

	}

	/**
	 * Enqueues scripts and styles necessary to make the widget work.
	 *
	 * @param string $hook_suffix Where is this hook being called.
	 * @return void|false
	 */
	function admin_enqueue_scripts( $hook_suffix = '' ) {

		if ( 'widgets.php' !== $hook_suffix ) {
			return false;
		}

		wp_enqueue_style( 'adsanity-group-widgets-admin' );
		wp_enqueue_script( 'adsanity-group-widget-admin' );

	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array $args An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 * @return void Echoes it's output
	 **/
	function widget( $args, $instance ) {

		if ( ! isset( $instance['id'] ) ) {
			$instance['id'] = array();
		}

		if ( ! isset( $instance['num-ads'] ) ) {
			$instance['num-ads'] = $this->num_ads;
		}

		if ( ! isset( $instance['num-columns'] ) ) {
			$instance['num-columns'] = $this->num_columns;
		}

		if ( ! isset( $instance['title'] ) ) {
			$instance['title'] = '';
		}

		$widget_args = array(
			'is_widget'		=> true,
			'title'			=> $instance['title'],
			'group_ids'		=> $instance['id'],
			'num_ads'		=> $instance['num-ads'],
			'num_columns'	=> $instance['num-columns'],
			'widget_args'	=> $args,
		);

		if ( array_key_exists( 'align', $instance ) ) {
			$widget_args['align'] = $instance['align'];
		}

		if ( array_key_exists( 'max-width', $instance ) && $instance['max-width'] ) {
			$widget_args['max_width'] = $instance['max-width'];
		}

		adsanity_show_ad_group( $widget_args );

	}

	/**
	 * Deals with the settings when they are saved by the admin. Here is
	 * where any validation should be dealt with.
	 *
	 * @param array $new_instance An array of new settings as submitted by the admin.
	 * @param array $old_instance An array of the previous settings.
	 * @return array The validated and (if necessary) amended settings
	 **/
	function update( $new_instance, $old_instance ) {

		// Update logic goes here.
		$updated_instance = $new_instance;

		if ( ! isset( $new_instance['num-ads'] ) || $new_instance['num-ads'] < 1 ) {
			$updated_instance['num-ads'] = $this->num_ads;
		}

		if ( ! isset( $new_instance['num-columns'] ) || $new_instance['num-columns'] < 1 ) {
			$updated_instance['num-columns'] = $this->num_columns;
		}

		if ( ! isset( $new_instance['id'] ) ) {
			$updated_instance = array();
		}

		// Check for differences.
		if ( md5( serialize( $old_instance ) ) !== md5( serialize( $updated_instance ) ) ) {

			global $wpdb;
			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_group-of-ads%'" );

		}

		return $updated_instance;

	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @param array $instance An array of the current settings for this widget.
	 * @return void Echoes it's output
	 **/
	function form( $instance ) {

		$defaults = array(
			'title'				=> '',
			'id'				=> array(),
			'num-ads'			=> $this->num_ads,
			'num-columns'		=> $this->num_columns,
			'align'				=> 'alignnone',
			'max-width-enabled'	=> false,
			'max-width'			=> false,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		echo '<div class="adsanity-group-options">';
			echo sprintf(
				'<p><label for="%s">%s</label> ',
				esc_attr( $this->get_field_id( 'title' ) ),
				esc_html__( 'Title:', 'adsanity' )
			);
			echo sprintf(
				'<input class="widefat" type="text" id="%s" name="%s" value="%s" /></p>',
				esc_attr( $this->get_field_id( 'title' ) ),
				esc_attr( $this->get_field_name( 'title' ) ),
				esc_attr( $instance['title'] )
			);
		echo '</div>';

		// Display all ad groups.
		$args = array(
			'orderby' => 'name',
			'order' => 'ASC',
		);
		$groups = get_terms( 'ad-group', $args );
		if ( count( $groups ) > 0 ) {

			echo '<div class="adsanity-group-list">';
			foreach ( $groups as $group ) {

				echo sprintf( '<label for="term-%s">', esc_attr( $group->term_id ) );
				echo sprintf(
					'<input type="checkbox" id="term-%s" name="%s[]" value="%s" %s /> ',
					esc_attr( $group->term_id ),
					esc_attr( $this->get_field_name( 'id' ) ),
					esc_attr( $group->term_id ),
					checked( in_array( $group->term_id, (array) $instance['id'] ), true, false )
				);
				echo esc_html( $group->name );
				echo '<br /></label>';

			}
			echo '</div>';

		} else {
			echo '<p>';
			echo sprintf(
				__( 'No ad groups found. Go %screate%s one.', 'adsanity' ),
				sprintf(
					'<a href="%s">',
					esc_url( admin_url( 'edit-tags.php?taxonomy=ad-group&post_type=ads' ) )
				),
				'</a>'
			);
			echo '</p>';
		}

		echo '<div class="adsanity-group-additional-options">';

		echo sprintf(
			'<p><label for="%s">%s</label>',
			esc_attr( $this->get_field_id( 'num-ads' ) ),
			esc_html__( 'Number of ads to show:', 'adsanity' )
		);
		echo sprintf(
			'<input class="widefat" type="number" min="1" id="%s" name="%s" value="%s" /></p>',
			esc_attr( $this->get_field_id( 'num-ads' ) ),
			esc_attr( $this->get_field_name( 'num-ads' ) ),
			esc_attr( esc_attr( (int) $instance['num-ads'] ) )
		);

		echo sprintf(
			'<p><label for="%s">%s</label>',
			esc_attr( $this->get_field_id( 'num-columns' ) ),
			esc_html__( 'Number of columns:', 'adsanity' )
		);
		echo sprintf(
			'<input class="widefat" type="number" min="1" id="%s" name="%s" value="%s" /></p>',
			esc_attr( $this->get_field_id( 'num-columns' ) ),
			esc_attr( $this->get_field_name( 'num-columns' ) ),
			esc_attr( (int) $instance['num-columns'] )
		);

		printf(
			'<p>
				<label for="%1$s">%2$s</label>
				<input class="adsanity-max-width-enabled" type="checkbox" id="%1$s" name="%3$s" value="1" %4$s/>
			</p>',
			esc_attr( $this->get_field_id( 'max-width-enabled' ) ),
			esc_html__( 'Max Width Enabled?', 'adsanity' ),
			esc_attr( $this->get_field_name( 'max-width-enabled' ) ),
			checked( $instance['max-width-enabled'], true, false )
		);

		$max_width_value = $instance['max-width-enabled'] ? $instance['max-width'] : false;

		printf(
			'<p>
				<label class="%6$s" for="%1$s">%2$s</label>
				<input class="adsanity-max-width" type="number" id="%1$s" name="%3$s" %4$s value="%5$d"/>
			</p>',
			esc_attr( $this->get_field_id( 'max-width' ) ),
			esc_html__( 'Max Width (px):', 'adsanity' ),
			esc_attr( $this->get_field_name( 'max-width' ) ),
			$instance['max-width-enabled'] ? '' : 'disabled',
			esc_attr( $max_width_value ),
			$instance['max-width-enabled'] ? '' : 'adsanity-label-disabled'
		);

		esc_html_e( 'Alignment:', 'adsanity' );

		$align_radio_format = '<label>
			<input type="radio" id="%s" name="%s" value="%s" %s/>
			%s
		</label>';

		printf(
			$align_radio_format,
			esc_attr( $this->get_field_id( 'align' ) ),
			esc_attr( $this->get_field_name( 'align' ) ),
			'alignnone',
			checked( 'alignnone' === $instance['align'], true, false ),
			esc_html__( 'None', 'adsanity' )
		);

		printf(
			$align_radio_format,
			esc_attr( $this->get_field_id( 'align' ) ),
			esc_attr( $this->get_field_name( 'align' ) ),
			'alignleft',
			checked( 'alignleft' === $instance['align'], true, false ),
			esc_html__( 'Left', 'adsanity' )
		);

		printf(
			$align_radio_format,
			esc_attr( $this->get_field_id( 'align' ) ),
			esc_attr( $this->get_field_name( 'align' ) ),
			'aligncenter',
			checked( 'aligncenter' === $instance['align'], true, false ),
			esc_html__( 'Center', 'adsanity' )
		);

		printf(
			$align_radio_format,
			esc_attr( $this->get_field_id( 'align' ) ),
			esc_attr( $this->get_field_name( 'align' ) ),
			'alignright',
			checked( 'alignright' === $instance['align'], true, false ),
			esc_html__( 'Right', 'adsanity' )
		);

		echo '<br><br>';
		echo '</div>';

	}

}

/**
 * Register the Ad Group widget with WordPress.
 *
 * @return void
 */
function adsanity_register_group_ad_widget() {

	register_widget( 'adsanity_group_ad_Widget' );

}

add_action( 'widgets_init', 'adsanity_register_group_ad_widget' );
