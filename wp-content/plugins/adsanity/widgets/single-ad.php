<?php
/**
 * The single ad widget.
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
class adsanity_single_ad_Widget extends WP_Widget {

	/**
	 * Constructor
	 *
	 * @return void
	 **/
	function __construct() {

		$widget_ops = array(
			'classname'                   => 'adsanity-single',
			'description'                 => sprintf( __( 'Display a single %s Ad unit.', 'adsanity' ), 'Adsanity' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct(
			'adsanity-single',
			sprintf( 'AdSanity - %s', __( 'Single Ad', 'adsanity' ) ),
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

		wp_enqueue_style( 'adsanity-single-widgets-admin' );
		wp_enqueue_script( 'adsanity-single-widget-admin' );

	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array $args An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 * @return void Echoes it's output
	 **/
	function widget( $args, $instance ) {

		$widget_args = array(
			'is_widget'		=> true,
			'title'			=> $instance['title'],
			'post_id'		=> $instance['id'],
			'widget_args'	=> $args
		);

		if ( array_key_exists( 'max-width', $instance ) && $instance['max-width'] ) {
			$widget_args['max_width'] = $instance['max-width'];
		}

		adsanity_show_ad( $widget_args );

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

		if ( ! isset( $updated_instance['id'] ) ) {

			$updated_instance['id'] = 0;

		}

		// Check for differences.
		if ( md5( serialize( $old_instance ) ) !== md5( serialize( $updated_instance ) ) ) {

			global $wpdb;
			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_single-ad%'" );

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
			'title'             => '',
			'id'                => 1,
			'max-width-enabled' => false,
			'max-width'         => false,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$now = time();

		// Display all ads that are not expired.
		$ads = get_posts(
			array(
				'post_type'     => 'ads',
				'nopaging'      => true,
				'meta_query'    => array( // phpcs:ignore WordPress.DB.SlowDBQuery
					array(
						'key'     => '_end_date',
						'value'   => $now,
						'type'    => 'numeric',
						'compare' => '>=',
					),
				),
				'no_found_rows' => true,
			)
		);
		if ( is_array( $ads ) && count( $ads ) > 0 ) {

			echo sprintf(
				'<p><label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'title' ) ),
				esc_html__( 'Title:', 'adsanity' )
			);
			echo sprintf(
				'<input class="widefat" id="%s" name="%s" type="text" value="%s" /></p>',
				esc_attr( $this->get_field_id( 'title' ) ),
				esc_attr( $this->get_field_name( 'title' ) ),
				esc_attr( $instance['title'] )
			);

			echo sprintf(
				'<label for="search-ads">%s</label>',
				esc_html__( 'Search Ads:', 'adsanity' )
			);
			printf(
				'<input type="text" id="search-ads" class="adsanity-single-ad-search" value="" placeholder="%s"/>',
				esc_attr__( 'search ads', 'adsanity' )
			);


			echo sprintf(
				'<div class="adsanity-single-ad-list" id="%s">',
				esc_attr( $this->id )
			);
			foreach ( $ads as $ad ) {

				echo sprintf(
					'<label for="%s-%s">',
					esc_attr( $this->get_field_id( 'id' ) ),
					esc_attr( $ad->ID )
				);
				echo sprintf(
					'<input type="radio" id="%s-%s" name="%s" value="%s" %s /> ',
					esc_attr( $this->get_field_id( 'id' ) ),
					esc_attr( $ad->ID ),
					esc_attr( $this->get_field_name( 'id' ) ),
					esc_attr( $ad->ID ),
					checked( $ad->ID, $instance['id'], false )
				);
				echo esc_html( get_the_title( $ad->ID ) );
				echo sprintf(
					'<br /><small>%s</small>',
					esc_html( Adsanity\Meta_Data::get( 'post', $ad->ID , '_size', true ) )
				);
				if ( Adsanity\Meta_Data::get( 'post', $ad->ID , '_start_date', true ) > $now ) {

					echo sprintf(
						'<small style="color: red">Scheduled for %s</small>',
						esc_html( date( 'm/d/Y', Adsanity\Meta_Data::get( 'post', $ad->ID , '_start_date', true ) ) )
					);

				}
				echo '</label>';
			}
			echo '</div>';

		} else {

			echo '<p>';
			echo wp_kses_post(
				sprintf(
					__( 'No ads found. Go %screate%s one.', 'adsanity' ),
					sprintf(
						'<a href="%s">',
						esc_url( admin_url( 'post-new.php?post_type=ads' ) )
					),
					'</a>'
				)
			);
			echo '</p>';

		}

		echo '<div class="adsanity-single-additional-options">';

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

		echo '</div>';

	}
}

/**
 * Register the Single Ad widget with WordPress.
 *
 * @return void
 */
function adsanity_register_single_ad_widget() {

	register_widget( 'adsanity_single_ad_Widget' );

}

add_action( 'widgets_init', 'adsanity_register_single_ad_widget' );
