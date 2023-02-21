<?php
/**
 * The main stats view.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

	global $wpdb;

	// Add ability to override transients
	$override_transients = apply_filters( 'adsanity_override_total_stats_transient', false );

	if ( ! $override_transients ) {
		$total_views	= get_transient( 'adsanity-alltime-total-views' );
		$total_clicks	= get_transient( 'adsanity-alltime-total-clicks' );
		$top_ten_clicks	= get_transient( 'adsanity-alltime-top-ten-clicks' );
		$top_ten_ctr	= get_transient( 'adsanity-alltime-top-ten-ctr' );
	} else {
		$total_views	= false;
		$total_clicks	= false;
		$top_ten_clicks	= false;
		$top_ten_ctr	= false;
	}

	if (
		false == $total_views ||
		false == $total_clicks ||
		false == $top_ten_clicks ||
		false == $top_ten_ctr
	) {
		$total_views = $total_clicks = 0;
		$top_ten = array();
		$args = \apply_filters(
			'adsanity_total_stats_args',
			[
				'post_type' => 'ads',
				'nopaging'  => true,
			]
		);
		$ads = \get_posts( $args );
		foreach ( $ads as $ad ) {
			$ad_id = $ad->ID;
			$top_ten[ $ad_id ] = array( 'views' => 0, 'clicks' => 0, 'ctr' => 0.00 );

			$meta = \AdSanity\Meta_Data::get( 'post', $ad_id );
			foreach ( $meta as $meta_key => $meta_val ) {
				if ( substr( $meta_key, 0, 7 ) == '_clicks' ) {
					$total_clicks += $meta_val[0];
					$top_ten[ $ad_id ]['clicks'] += $meta_val[0];
				} elseif ( substr( $meta_key, 0, 6 ) == '_views' ) {
					$total_views += $meta_val[0];
					$top_ten[ $ad_id ]['views'] += $meta_val[0];
				}
			}

			$top_ten[ $ad_id ]['ctr'] = \number_format_i18n(
				(
					(int) $top_ten[ $ad_id ]['clicks'] > 0 &&
					(int) $top_ten[ $ad_id ]['views'] > 0
				) ?
					( (int) $top_ten[ $ad_id ]['clicks'] / (int) $top_ten[ $ad_id ]['views'] * 100 )
					:
					'0',
				2
			);
		}

		uasort( $top_ten, 'adsanity_sort_by_clicks' );
		$top_ten_clicks = array_slice( $top_ten, 0, 10, true );

		uasort( $top_ten, 'adsanity_sort_by_ctr' );
		$top_ten_ctr = array_slice( $top_ten, 0, 10, true );

		if ( ! $override_transients ) {
			set_transient( 'adsanity-alltime-total-views',		$total_views, HOUR_IN_SECONDS );
			set_transient( 'adsanity-alltime-total-clicks',		$total_clicks, HOUR_IN_SECONDS );
			set_transient( 'adsanity-alltime-top-ten-clicks',	$top_ten_clicks, HOUR_IN_SECONDS );
			set_transient( 'adsanity-alltime-top-ten-ctr',		$top_ten_ctr, HOUR_IN_SECONDS );
		}
		wp_reset_query();
	}

	function adsanity_sort_by_clicks( $a, $b ) {
		if ( $a["clicks"] == $b["clicks"] ) {
			return 0;
		}
		return ( $a["clicks"] > $b["clicks"] ) ? -1 : 1;
	}

	function adsanity_sort_by_ctr( $a, $b ) {
		if ( $a["ctr"] == $b["ctr"] ) {
			return 0;
		}
		return ( $a["ctr"] > $b["ctr"] ) ? -1 : 1;
	}
?>

	<?php do_action( 'adsanity_before_stats_dashboard' ); ?>

	<!-- All-Time Summary -->
	<div class="all-time-summary">
		<h3><?php _e( 'All-Time Summary', 'adsanity' ) ?></h3>
		<table class="widefat">
		<tbody>
			<tr>
				<td><?php _e( 'Total Views', 'adsanity' ) ?></td>
				<td><?php _e( 'Total Clicks', 'adsanity' ) ?></td>
				<td><?php _e( 'Total Click Through Rate', 'adsanity' ) ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html( number_format_i18n( $total_views, 0 ) ); ?></td>
				<td><?php echo esc_html( number_format_i18n( $total_clicks, 0 ) ); ?></td>
				<td><?php
					$ctr = ( intval( $total_clicks ) > 0 && intval( $total_views ) > 0 ) ? ( intval( $total_clicks ) / intval( $total_views ) * 100 ) : '0';
					echo esc_html( number_format_i18n( $ctr, 2 ).'%' );
				?></td>
			</tr>
		</tbody>
		</table>
	</div>

	<!-- COLUMN 1 -->
	<div class="all-time-table">
		<h3><?php _e( 'All-Time Top 10 Clicks', 'adsanity' ) ?></h3>
		<table class="widefat">
		<thead>
			<tr>
				<th><?php _e( 'Ad Title', 'adsanity' ) ?></th>
				<th><?php _e( 'Clicks', 'adsanity' ) ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if( isset( $top_ten_clicks ) && !empty( $top_ten_clicks ) > 0 ) :
				foreach( (array)$top_ten_clicks as $ad_id => $tracking ) : ?>
				<tr>
					<td><?php
						if ( current_user_can( 'manage_options' ) ) {
							printf(
								'<a href="%s">%s</a>',
								admin_url( '/post.php?post=' . $ad_id . '&action=edit' ),
								get_the_title( $ad_id )
							);
						} else {
							echo get_the_title( $ad_id );
						}
					?></td>
					<td><?php echo esc_html( number_format_i18n( $tracking['clicks'], 0 ) ); ?></td>
				</tr>
				<?php
				endforeach;
			else :
			?>
				<tr>
					<td colspan="2"><?php _e( 'No clicks have been collected yet.' , 'adsanity' ) ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<th><?php _e( 'Ad Title', 'adsanity' ) ?></th>
				<th><?php _e( 'Clicks', 'adsanity' ) ?></th>
			</tr>
		</tfoot>
	</table>
	</div>

	<!-- COLUMN 2 -->
	<div class="all-time-table">
		<h3><?php _e( 'All-Time Top 10 Click Through Rate', 'adsanity' ) ?></h3>
		<table class="widefat">
		<thead>
			<tr>
				<th><?php _e( 'Ad Title', 'adsanity' ) ?></th>
				<th><?php _e( 'Views', 'adsanity' ) ?></th>
				<th><?php _e( 'Clicks', 'adsanity' ) ?></th>
				<th><?php _e( 'CTR', 'adsanity' ) ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if( isset( $top_ten_ctr ) && !empty( $top_ten_ctr ) > 0 ) :
				foreach( (array)$top_ten_ctr as $ad_id => $tracking ) : ?>
				<tr>
					<td><?php
						if ( current_user_can( 'manage_options' ) ) {
							printf(
								'<a href="%s">%s</a>',
								admin_url( '/post.php?post=' . $ad_id . '&action=edit' ),
								get_the_title( $ad_id )
							);
						} else {
							echo get_the_title( $ad_id );
						}
					?></td>
					<td><?php echo esc_html(
						number_format_i18n(
							$tracking['views'],
							0
						)
					); ?></td>
					<td><?php echo esc_html( number_format_i18n( $tracking['clicks'], 0 ) ); ?></td>
					<td><?php echo esc_html( number_format_i18n( $tracking['ctr'], 2 ) ); ?>%</td>
				</tr>
				<?php
				endforeach;
			else :
			?>
				<tr>
					<td colspan="4"><?php _e( 'No views or clicks have been collected yet.' , 'adsanity' ) ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<th><?php _e( 'Ad Title', 'adsanity' ) ?></th>
				<th><?php _e( 'Views', 'adsanity' ) ?></th>
				<th><?php _e( 'Clicks', 'adsanity' ) ?></th>
				<th><?php _e( 'CTR', 'adsanity' ) ?></th>
			</tr>
		</tfoot>
		</table>
	</div>

	<!-- COLUMN 3 -->
	<?php require_once plugin_dir_path( __FILE__ ) . 'stats-dashboard-group-block.php'; ?>

	<?php do_action( 'adsanity_after_stats_columns' ); ?>
