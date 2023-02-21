<?php
/**
 * Adsanity ajax functionality
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
 * AdSanityAjax
 *
 * Contains all helper methods for AdSanity ajax.
 *
 * @pkg AdSanity
 * @since 1.6
 */
class AdSanityAjax {

	/**
	 * Kicks off all actions and filters
	 */
	public static function hooks() {

		add_action(
			'wp_ajax_custom_stats_selection',
			array(
				__CLASS__,
				'custom_stats_selection',
			)
		);

		add_action( 'wp_ajax_adsanity_get_ads_by_term', array( __CLASS__, 'get_ads_by_term' ) );

	}

	/**
	 * Processes the custom stats ajax request.
	 */
	public static function custom_stats_selection() {

		if ( ! isset( $_POST['ads'] ) || ! isset( $_POST['start'] ) || ! isset( $_POST['end'] ) ) {
			self::no_stat_data( __( 'You must choose an ad to view.', 'adsanity' ) );
		}

		$max_results = apply_filters( 'adsanity_custom_reports_max_results', 15 );
		if ( count( $_POST['ads'] ) > $max_results ) {
			self::no_stat_data(
				sprintf(
					__( 'For display reasons, please select no more than %s ads to compare.', 'adsanity' ),
					$max_results
				)
			);
		}

		$ad_ids = array_map( 'intval', $_POST['ads'] );
		$ads = AdSanityQuery::get_ads(
			array(
				'include'      => $ad_ids,
				'include_meta' => true,
			)
		);

		$chart = '';
		$table = array();
		$start = strtotime( sanitize_text_field( wp_unslash( $_POST['start'] ) ) );
		$end = strtotime( sanitize_text_field( wp_unslash( $_POST['end'] ) ) );
		$total_clicks = 0;
		$total_views = 0;
		$total_ctr = 0;
		$all_views = array();
		$all_clicks = array();
		$viewable_data = false;

		foreach ( $ads as $ad ) {

			// Sort the meta fields.
			ksort( $ad->meta );
			$views = array();
			$clicks = array();

			foreach ( $ad->meta as $meta_key => $meta_value ) {

				if ( strpos( $meta_key, 'view' ) !== false ) {

					// Do we have data in the selected date range?
					$timestamp = substr( $meta_key, 7 );
					if ( $start > intval( $timestamp ) || $end < intval( $timestamp ) ) {
						continue;
					}

					$viewable_data = true;

					$clicks_key = '_clicks-' . $timestamp;

					if ( ! isset( $ad->meta[ $clicks_key ] ) ) {
						$ad->meta[ $clicks_key ][0] = 0;
					}

					// Setup Table Data.
					$row = array();
					$row[] = '<tr>';

					// Title.
					$row[] = sprintf( '<td>%s</td>', get_the_title( $ad->ID ) );

					// Date.
					$row[] = sprintf(
						'<td>%s</td>',
						date( get_option( 'date_format' ), intval( $timestamp ) )
					);

					// Views.
					$row[] = sprintf( '<td>%s</td>', number_format_i18n( intval( $meta_value[0] ) ) );

					// Clicks.
					$row[] = sprintf(
						'<td>%s</td>',
						number_format_i18n( intval( $ad->meta[ $clicks_key ][0] ) )
					);

					// CTR.
					$row[] = sprintf(
						'<td>%s%%</td>',
						number_format_i18n(
							( intval( $ad->meta[ $clicks_key ][0] ) / intval( $meta_value[0] ) ) * 100
						)
					);

					$row = apply_filters( 'adsanity_custom_report_table_cell', $row, $ad->ID, $timestamp );

					$row[] = '</tr>';
					$table[ $timestamp . $ad->post_name ] = implode( '', $row );

					$total_views += intval( $meta_value[0] );
					$total_clicks += intval( intval( $ad->meta[ $clicks_key ][0] ) );

					// Setup Chart Data.
					$views[ $timestamp ] = array(
						'id' => $ad->ID,
						'timestamp' => $timestamp,
						'title' => get_the_title( $ad->ID ),
						'viewcount' => intval( $meta_value[0] ),
					);
					$clicks[ $timestamp ] = array(
						'id' => $ad->ID,
						'timestamp' => $timestamp,
						'title' => get_the_title( $ad->ID ),
						'clickcount' => intval( $ad->meta[ $clicks_key ][0] ),
					);

					$all_views[ $ad->ID ] = $views;
					$all_clicks[ $ad->ID ] = $clicks;

				} // Endif.

			} // Endforeach.

		} // Endforeach.

		ksort( $table );

		if ( false === $viewable_data ) {
			self::no_stat_data(
				__( 'There is no statistical data to show with the given parameters.', 'adsanity' )
			);
		}

		echo json_encode(
			array(
				'table' => implode( '', $table ),
				'total_views' => number_format_i18n( intval( $total_views ) ),
				'total_clicks' => number_format_i18n( intval( $total_clicks ) ),
				'total_ctr' => number_format_i18n( ( intval( $total_clicks ) / intval( $total_views ) ) * 100 ) . '%',
				'chart_data' => array(
					'views' => $all_views,
					'clicks' => $all_clicks,
				),
			)
		);
		die();

	}

	/**
	 * No data in the result set, so return an empty statement
	 *
	 * @param  string $message A custom message to be sent back to the browser.
	 */
	public static function no_stat_data( $message = '' ) {

		echo json_encode(
			array(
				'table' => sprintf( '<tr><td colspan="5">%s</td></tr>', $message ),
				'total_views' => 0,
				'total_clicks' => 0,
				'total_ctr' => '0%',
				'chart_data' => array(
					'views' => array(),
					'clicks' => array(),
				),
			)
		);
		die();

	}

	/**
	 * Get the ads and stats based on term_id
	 */
	public static function get_ads_by_term() {

		if ( ! isset( $_POST['term_id'] ) ) {
			wp_send_json_error();
		}

		$term_id = intval( $_POST['term_id'] );

		// Get ads based on term id.
		$ad_args = [
			'post_type'   => 'ads',
			'numberposts' => -1,
			'tax_query'   => [ // phpcs:ignore WordPress.DB.SlowDBQuery
				[
					'taxonomy' => 'ad-group',
					'field'    => 'term_id',
					'terms'    => $term_id,
				],
			],
		];

		$ads = \get_posts( $ad_args );

		$results = array();

		foreach ( $ads as $ad ) {
			$ad_id = $ad->ID;
			$views = 0;
			$clicks = 0;

			$meta = \AdSanity\Meta_Data::get( 'post', $ad_id );
			foreach ( $meta as $meta_key => $meta_val ) {

				if ( substr( $meta_key, 0, 7 ) == '_clicks' ) {
					$clicks = $clicks + $meta_val[0];
				} elseif ( substr( $meta_key, 0, 6 ) == '_views' ) {
					$views = $views + $meta_val[0];
				}
			}

			$ctr = 0;
			if ( 0 != $clicks && 0 != $views ) {
				$ctr = ( $clicks / $views );
			}

			$results[ \get_the_title( $ad_id ) ] = \apply_filters(
				'adsanity_get_ads_by_term_row',
				[
					'link'   => \get_edit_post_link(),
					'views'  => \number_format_i18n( $views, 0 ),
					'clicks' => \number_format_i18n( $clicks, 0 ),
					'ctr'    => \number_format_i18n( intval( $ctr * 100 ), 2 ) . '%',
				],
				$ad_id
			);
		}

		$results = \apply_filters( 'adsanity_get_ads_by_term_rows', $results );

		// Sort results by ctr.
		uasort( $results, array( __CLASS__, 'sort' ) );

		\wp_send_json_success( $results );

	}

	/**
	 * Sort results by ctr.
	 *
	 * @param array $a First sorting value.
	 * @param array $b Second sorting value.
	 */
	public static function sort( $a, $b ) {

		if ( $a['ctr'] == $b['ctr'] ) {

			return 0;

		}

		return ( $a['ctr'] > $b['ctr'] ) ? -1 : 1;

	}
}

if ( is_admin() ) {
	AdSanityAjax::hooks();
}
