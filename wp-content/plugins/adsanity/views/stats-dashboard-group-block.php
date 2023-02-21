<?php
/**
 * The ad group stats dashboard block view.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

$term_args = array(
	'taxonomy'   => 'ad-group',
	'hide_empty' => true,
);
$ad_groups = new WP_Term_Query( $term_args );

$show_group_block = apply_filters( 'adsanity_show_ad_group_block', true );

if ( ! empty( $ad_groups->get_terms() ) && $show_group_block ) {

	?>

	<div class="all-time-table" id="adsanity-ad-group-dashboard">
		<h3><?php esc_html_e( 'All-Time By Ad Group' ); ?></h3>
		<select>
		<?php
			foreach ( $ad_groups->get_terms() as $ad_group ) {
				printf( '<option value="%s">%s</option>',
					esc_attr( $ad_group->term_id ),
					esc_html( $ad_group->name )
				);
			}
		?>
		</select>
		<br><br>
		<table class="widefat loading">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Ad Title', 'adsanity' ); ?></th>
					<th><?php esc_html_e( 'Views', 'adsanity' ); ?></th>
					<th><?php esc_html_e( 'Clicks', 'adsanity' ); ?></th>
					<th><?php esc_html_e( 'CTR', 'adsanity' ); ?></th>
					<?php do_action( 'adsanity_group_block_table_header' ); ?>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot>
				<tr>
					<th><?php esc_html_e( 'Ad Title', 'adsanity' ); ?></th>
					<th><?php esc_html_e( 'Views', 'adsanity' ); ?></th>
					<th><?php esc_html_e( 'Clicks', 'adsanity' ); ?></th>
					<th><?php esc_html_e( 'CTR', 'adsanity' ); ?></th>
					<?php do_action( 'adsanity_group_block_table_header' ); ?>
				</tr>
			</tfoot>
		</table>
	</div>

	<?php
}
