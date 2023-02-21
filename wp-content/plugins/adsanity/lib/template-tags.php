<?php
/**
 * Template tags for AdSanity.
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
 * Ad group template tag
 *
 * @param array $args accepted args.
 *              - (bool) is_widget: outputs widget wrap/title.
 *              - (array) widget_args: only used in widget mode.
 *              - (string) title: only used in widget mode.
 *              - (array) group_ids: array of ad-group term ids.
 *              - (int) num_ads: number of ads to show.
 *              - (int) num_columns: number of columns.
 *              - (bool) return: whether to return or directly output.
 *              - (bool) max_width: number of pixels to limit the width of the ad.
 *              - (bool) align: alignment for the ad group.
 * @return string if return is set to true, will return the html. used in shortcodes.
 */
function adsanity_show_ad_group( $args = array() ) {

	$defaults = array(
		'is_widget'   => false,
		'widget_args' => array(),
		'title'       => false,
		'group_ids'   => array(),
		'num_ads'     => 0,
		'num_columns' => 0,
		'return'      => false,
		'max_width'   => false,
		'align'       => 'alignnone',
	);
	$values   = wp_parse_args( $args, $defaults );

	$values['group_ids']    = apply_filters( 'adsanity_hide_ad_group', $values['group_ids'] );
	$values['post__not_in'] = apply_filters( 'adsanity_hide_ad_in_group', array(), $values['group_ids'] );
	$values['num_ads']      = intval( $values['num_ads'] );
	$values['num_columns']  = intval( $values['num_columns'] );

	// Sometimes max width is enabled, but no value is passed
	// for the actual max width. This should default to 100.
	if ( isset( $args['max_width_enabled'] ) && boolval( $args['max_width_enabled'] ) && ! boolval( $values['max_width'] ) ) {

		$values['max_width'] = 100;

	}

	if ( count( $values['group_ids'] ) < 1 ) {
		return '';
	}

	if ( boolval( $values['return'] ) ) {
		ob_start();
	}

	$now = time();

	// Get the cached version of the ad first.
	$cache_key = sprintf(
		'group-of-ads-%s',
		AdSanityUtility::hash( wp_json_encode( $values ) )
	);
	$ads       = \get_transient( $cache_key );
	if ( false === $ads || $ads instanceof \WP_Query ) {

		\delete_transient( $cache_key );

		$ads_args = [
			'post_type'     => 'ads',
			'numberposts'   => $values['num_ads'],
			'orderby'       => 'rand',
			'no_found_rows' => true,
			'tax_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery
				[
					'taxonomy' => 'ad-group',
					'field'    => 'id',
					'terms'    => $values['group_ids'],
				],
			],
			'meta_query'    => [ // phpcs:ignore WordPress.DB.SlowDBQuery
				[
					'key'     => '_start_date',
					'value'   => $now,
					'type'    => 'numeric',
					'compare' => '<=',
				],
				[
					'key'     => '_end_date',
					'value'   => $now,
					'type'    => 'numeric',
					'compare' => '>=',
				],
			],
		];

		if ( ! empty( $values['post__not_in'] ) ) {
			$ads_args['post__not_in'] = $values['post__not_in'];
		}

		$ads = \get_posts( $ads_args );

		// Cache for 60 seconds.
		\set_transient( $cache_key, $ads, MINUTE_IN_SECONDS );

	}

	if ( ! empty( $ads ) ) {

		if ( boolval( $values['is_widget'] ) ) {

			echo $values['widget_args']['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput

			if ( boolval( $values['title'] ) && ! empty( $values['title'] ) ) {

				echo $values['widget_args']['before_title']; // phpcs:ignore WordPress.Security.EscapeOutput
				echo \esc_html( $values['title'] );
				echo $values['widget_args']['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput

			}
		}

		printf( '<div class="ad-%s">', \esc_attr( $values['align'] ) );

		$index = 0;
		foreach ( $ads as $ad ) {

			// Count the view.
			\adsanity_view( $ad->ID );

			$size = Adsanity\Meta_Data::get( 'post', $ad->ID, '_size', true );

			// Override in a parent or child theme.
			$custom_template = \locate_template(
				array(
					sprintf( 'ad-%s.php', $ad->ID ),
					sprintf( 'ad-%s.php', $size ),
					'ad.php',
				)
			);

			if ( 0 === $index || 0 === $index % intval( $values['num_columns'] ) ) {
				echo '<div class="ad-row">';
			}

			if ( ! empty( $custom_template ) ) {

				require $custom_template;

			} else {

				require ADSANITY_THEME . 'ad.php';

			}

			if ( 0 === ( $index + 1 ) % intval( $values['num_columns'] ) ) {
				echo '</div>';
			}

			$index++;
		}

		// Close up the ad-row div if necessary.
		if ( 0 !== $index % intval( $values['num_columns'] ) ) {
			echo '</div>';
		}

		// Close up the alignment div.
		echo '</div>';

		if ( boolval( $values['is_widget'] ) ) {

			echo $values['widget_args']['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput

		}

	}

	if ( boolval( $values['return'] ) ) {

		$output = ob_get_contents();
		ob_end_clean();
		return $output;

	}

}

/**
 * Single ad template tag
 *
 * @param array $args accepted args.
 *              - (bool) is_widget: outputs widget wrap/title.
 *              - (array) widget_args: only used in widget mode.
 *              - (string) title: only used in widget mode.
 *              - (int) post_id: ID of ad to show.
 *              - (string) align: alignnone|alignleft|aligncenter|alignright.
 *              - (bool) return: whether to return or directly output.
 *              - (bool) max_width: number of pixels to limit the width of the ad.
 *              - (bool) align: alignment for the ad group.
 * @return string if return is set to true, will return the html. used in shortcodes.
 */
function adsanity_show_ad( $args = array() ) {

	$defaults = array(
		'is_widget'   => false,
		'widget_args' => array(),
		'title'       => false,
		'post_id'     => 1,
		'align'       => false,
		'return'      => false,
		'max_width'   => false,
		'track_this'  => true,
	);
	$values   = wp_parse_args( $args, $defaults );

	$values['post_id'] = intval( $values['post_id'] );

	$hide_ad = apply_filters( 'adsanity_hide_ad', false, $values['post_id'] );
	if ( boolval( $hide_ad ) ) {
		return '';
	}

	// Sometimes max width is enabled, but no value is passed
	// for the actual max width. This should default to 100.
	if ( isset( $args['max_width_enabled'] ) && boolval( $args['max_width_enabled'] ) && ! boolval( $values['max_width'] ) ) {

		$values['max_width'] = 100;

	}

	if ( $values['return'] ) {
		ob_start();
	}

	$now = time();

	// Get the cached version of the ad first.
	$cache_key = sprintf( 'single-ad-%s', AdSanityUtility::hash( wp_json_encode( $values['post_id'] ) ) );
	$ad        = \get_transient( $cache_key );

	if ( false === $ad || $ad instanceof \WP_Query ) {

		\delete_transient( $cache_key );

		// Get the ad using start/end dates.
		$ad = \get_posts(
			[
				'p'             => $values['post_id'],
				'post_type'     => 'ads',
				'numberposts'   => 1,
				'no_found_rows' => true,
				'meta_query'    => [ // phpcs:ignore WordPress.DB.SlowDBQuery
					[
						'key'     => '_start_date',
						'value'   => $now,
						'type'    => 'numeric',
						'compare' => '<=',
					],
					[
						'key'     => '_end_date',
						'value'   => $now,
						'type'    => 'numeric',
						'compare' => '>=',
					],
				],
			]
		);

		$transient_expiration = DAY_IN_SECONDS / 2;

		// If the end date for this ad is in less than
		// 12 hours, use that for the transient expiration
		//
		// Don't use the standard loop.
		if ( ! empty( $ad ) ) {

			$end_date = Adsanity\Meta_Data::get( 'post', $values['post_id'], '_end_date', true );
			if ( $end_date ) {

				if ( ( $end_date - time() ) < $transient_expiration ) {
					$transient_expiration = $end_date - time();
				}
			}

		}

		\set_transient( $cache_key, $ad, $transient_expiration ); // Cache for 12 hours or end date of this ad.

	}

	if ( ! empty( $ad ) ) {

		$ad = $ad[0];

		// Count the view.
		if ( $values['track_this'] ) {

			\adsanity_view( $values['post_id'] );

		}

		if ( boolval( $values['is_widget'] ) ) {

			echo $values['widget_args']['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput

			if ( boolval( $values['title'] ) && ! empty( $values['title'] ) ) {

				echo $values['widget_args']['before_title']; // phpcs:ignore WordPress.Security.EscapeOutput
				echo \esc_html( $values['title'] );
				echo $values['widget_args']['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput

			}

		}

		$size = Adsanity\Meta_Data::get( 'post', $values['post_id'], '_size', true );

		// Override in a parent or child theme.
		$custom_template = \locate_template(
			array(
				sprintf( 'ad-%s.php', $values['post_id'] ),
				sprintf( 'ad-%s.php', $size ),
				'ad.php',
			)
		);
		if ( ! empty( $custom_template ) ) {

			require $custom_template;

		} else {

			require ADSANITY_THEME . 'ad.php';

		}

		if ( boolval( $values['is_widget'] ) ) {

			echo $values['widget_args']['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput

		}

	}

	if ( boolval( $values['return'] ) ) {

		$output = ob_get_contents();
		ob_end_clean();
		return $output;

	}

}
