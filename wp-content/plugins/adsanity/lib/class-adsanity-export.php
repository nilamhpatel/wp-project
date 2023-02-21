<?php
/**
 * Handles the sport functionality of AdSanity.
 *
 * @package WordPress
 * @subpackage AdSanity
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'You are not allowed to call this file directly.' );

}

/**
 * Generates stat output as a CSV
 * @since 1.6
 */
class AdSanity_Export {

	/**
	 * Processes the custom stats export request
	 */
	public static function custom_stats_export() {

		$ads_to_export = array();

		// phpcs:ignore WordPress.Security.NonceVerification
		foreach ( $_POST as $key => $val ) {

			if ( 'ad' === substr( $key, 0, 2 ) && 'ad-search' !== $key ) {

				array_push( $ads_to_export, $val );

			}

		}

		if ( 0 === count( $ads_to_export ) ) {

			wp_die( esc_html__( 'You must choose an ad to export.', 'adsanity' ) );

		}

		// phpcs:disable WordPress.Security.NonceVerification
		if ( isset( $_POST['start_date'], $_POST['end_date'] ) ) {

			$start = strtotime( sanitize_text_field( wp_unslash( $_POST['start_date'] ) ) );
			$end   = strtotime( sanitize_text_field( wp_unslash( $_POST['end_date'] ) ) );

		} else {

			wp_die( esc_html__( 'You must select a date range to export.', 'adsanity' ) );

		}
		// phpcs:enable WordPress.Security.NonceVerification

		$ads = AdSanityQuery::get_ads(
			array(
				'include'      => $ads_to_export,
				'include_meta' => true,
			)
		);

		$table         = array();
		$table_data    = array();
		$total_clicks  = 0;
		$total_views   = 0;
		$viewable_data = false;

		// Header row.
		$row           = array();
		$row[]         = __( 'Title', 'adsanity' );
		$row[]         = __( 'Date', 'adsanity' );
		$row[]         = __( 'Views', 'adsanity' );
		$row[]         = __( 'Clicks', 'adsanity' );
		$row[]         = __( 'CTR %', 'adsanity' );
		$table_data[0] = apply_filters( 'adsanity_export_stats_header_row_data', $row );
		$table[0]      = self::prepare_csv_line( $table_data[0] );

		foreach ( $ads as $ad ) {

			// Sort the meta fields.
			ksort( $ad->meta );

			foreach ( $ad->meta as $meta_key => $meta_value ) {

				if ( strpos( $meta_key, 'view' ) !== false ) {

					// Do we have data in the selected date range?
					$timestamp = substr( $meta_key, 7 );
					if ( $start > intval( $timestamp ) || $end < intval( $timestamp ) ) {

						continue;

					}

					$viewable_data = true;

					// Set Clicks to 0 if we don't find any clicks.
					$clicks_key = '_clicks-' . $timestamp;
					if ( ! isset( $ad->meta[ $clicks_key ] ) ) {

						$ad->meta[ $clicks_key ][0] = 0;

					}

					// Set up table data.
					$row_key = $timestamp . $ad->post_name;

					$row                    = array();
					$row[]                  = html_entity_decode( get_the_title( $ad->ID ) );
					$row[]                  = date( get_option( 'date_format' ), intval( $timestamp ) );
					$row[]                  = number_format_i18n( intval( $meta_value[0] ) );
					$row[]                  = number_format_i18n( intval( $ad->meta[ $clicks_key ][0] ) );
					$row[]                  = number_format_i18n( ( intval( $ad->meta[ $clicks_key ][0] ) / intval( $meta_value[0] ) ) * 100 );
					$table_data[ $row_key ] = apply_filters( 'adsanity_export_stats_data_row_data', $row, $timestamp, $ad );
					$table[ $row_key ]      = self::prepare_csv_line( $table_data[ $row_key ] );

					$total_views  += intval( $meta_value[0] );
					$total_clicks += intval( intval( $ad->meta[ $clicks_key ][0] ) );

				}

			}

		}

		ksort( $table );

		if ( false === $viewable_data ) {

			wp_die( esc_html__( 'There is no statistical data to show for the given parameters.', 'adsanity' ) );

		}

		// Totals.
		$row                    = array();
		$row[]                  = __( 'Totals', 'adsanity' );
		$row[]                  = '';
		$row[]                  = number_format_i18n( intval( $total_views ) );
		$row[]                  = number_format_i18n( intval( $total_clicks ) );
		$row[]                  = number_format_i18n( ( intval( $total_clicks ) / intval( $total_views ) ) * 100 ) . '%';
		$table_data[2147483647] = apply_filters( 'adsanity_export_stats_totals_row_data', $row, $table_data, $total_views, $total_clicks );
		$table[2147483647]      = self::prepare_csv_line( $table_data[2147483647] );

		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment; filename="adsanity-stats' . time() . '.csv"' );
		echo wp_kses_data( implode( PHP_EOL, $table ) );
		die();

	}

	/**
	 * Combine an array of values into a csv ready format
	 *
	 * @param  array $values Columns of data.
	 * @return string        Comma separated and quoted string of text.
	 */
	public static function prepare_csv_line( $values = array() ) {

		$line = '';

		$values = array_map(
			function ( $v ) {
				return '"' . str_replace( '"', '""', $v ) . '"';
			},
			$values
		);

		$line .= implode( ',', $values );

		return $line;

	}

}
