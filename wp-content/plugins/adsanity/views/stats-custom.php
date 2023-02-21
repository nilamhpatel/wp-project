<?php
/**
 * The custom stats main view.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}
?>
<h3><?php _e( 'Custom Reports', 'adsanity' ) ?></h3>
<p class="description">
	<?php _e( 'Choose from the options below to customize the results.', 'adsanity' ) ?>
</p>

<div id="customizing-bar">
	<form id="data-export" method="post">
		<?php wp_nonce_field( 'adsanity-stat-export', '_adsanity_export_nonce' ); ?>
		<div>
			<div>
				<label for="start_date"><?php esc_html_e( 'Start Date', 'adsanity' ) ?></label>
				<input
					type="text"
					name="start_date"
					value="<?php echo esc_attr( date( 'F d, Y', time() - ( DAY_IN_SECONDS * 30 ) ) ); ?>"
					id="start_date"
				/>
			</div>
			<div>
				<label for="end_date"><?php esc_html_e( 'End Date', 'adsanity' ) ?></label>
				<input
					type="text"
					name="end_date"
					value="<?php echo esc_attr( date( 'F d, Y' ) ); ?>"
					id="end_date" />
			</div>
		</div>
		<div>
			<?php do_action( 'adsanity_custom_stats_before_ad_selection' ); ?>
			<div id="ad-choices">
				<label for="ad-search"><?php esc_html_e( 'Choose Ads to View', 'adsanity' ) ?></label>
				<input
					type="text"
					name="ad-search"
					placeholder="<?php esc_attr_e( 'Search Ads', 'adsanity' ); ?>"
					id="ad-search"
					class="widefat"
				/>
				<span class="mass-select">
					<a href="#" class="selectall"><?php _e( 'All', 'adsanity' ); ?></a>
					<a href="#" class="selectnone"><?php _e( 'None', 'adsanity' ); ?></a>
				</span>
				<ul>
				<?php
					$ads = AdSanityQuery::get_all_ads();
					if ( ! is_wp_error( $ads ) ) {
						foreach ( $ads as $ad ) {
							echo sprintf(
								'<li><label for="ad-%1$d">',
								intval( $ad->ID )
							);
							echo sprintf(
								'<input type="checkbox" name="ad-%1$d" id="ad-%1$d" value="%1$d" /> ',
								intval( $ad->ID )
							);
							echo get_the_title( $ad->ID );
							echo '</label></li>';
						}
					}
				?>
				</ul>
			</div>
		</div>
	</form>
</div>
<div id="custom-reports-loading" style="display: none;">
	<img src="<?php echo esc_attr( admin_url( '/images/spinner.gif' ) ); ?>" alt="<?php esc_attr_e( 'Loading animation', 'adsanity' ); ?>">
</div>
<div id="custom-views-container" style="display: none;">
	<h3><?php _e( 'View Data', 'adsanity' ) ?></h3>
	<canvas id="custom-views-container-canvas"></canvas>
</div>

<div id="custom-clicks-container" style="display: none;">
	<h3><?php _e( 'Click Data', 'adsanity' ) ?></h3>
	<canvas id="custom-clicks-container-canvas"></canvas>
</div>

<div id="custom-report-container">
	<h3>
		<?php _e( 'Detailed Report', 'adsanity' ) ?>
		<a href="#export" id="export-csv" class="add-new-h2">
			<?php _e( 'Export CSV', 'adsanity' ); ?>
		</a>
	</h3>
	<table class="widefat">
	<thead>
		<tr>
			<th><?php _e( 'Ad Name', 'adsanity' ) ?></th>
			<th><?php _e( 'Date', 'adsanity' ) ?></th>
			<th><?php _e( 'Views', 'adsanity' ) ?></th>
			<th><?php _e( 'Clicks', 'adsanity' ) ?></th>
			<th><?php _e( 'CTR', 'adsanity' ) ?></th>
			<?php do_action( 'adsanity_custom_reports_table_heading' ); ?>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th><?php _e( 'Totals', 'adsanity' ) ?></th>
			<th>&nbsp;</th>
			<th id="total-views">0</th>
			<th id="total-clicks">0</th>
			<th id="total-ctr">0%</th>
			<?php do_action( 'adsanity_custom_reports_table_total' ); ?>
		</tr>
		<tr>
			<th><?php _e( 'Ad Name', 'adsanity' ) ?></th>
			<th><?php _e( 'Date', 'adsanity' ) ?></th>
			<th><?php _e( 'Views', 'adsanity' ) ?></th>
			<th><?php _e( 'Clicks', 'adsanity' ) ?></th>
			<th><?php _e( 'CTR', 'adsanity' ) ?></th>
			<?php do_action( 'adsanity_custom_reports_table_heading' ); ?>
		</tr>
	</tfoot>
	<tbody id="adsanity-data">
		<tr>
			<td colspan="5">
				<?php _e( 'Make your selections above to see detailed data here.', 'adsanity' ) ?>
			</td>
		</tr>
	</tbody>
	</table>
</div>
