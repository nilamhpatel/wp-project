<?php
/**
 * The add-ons view in the settings menu.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}
	// example array( 'ad-block' => 'Ad Block' );
	$addons = apply_filters( 'adsanity-addons', array() );
	sort( $addons );
	$last_addon = count( $addons ) - 1;
	$sub = isset( $_REQUEST['sub'] ) ?  $_REQUEST['sub'] : $addons[0]['slug'];
	$actions = array();
?>
<ul class="subsubsub">
<?php
	foreach ( $addons as $addon ) {
		$actions[] = sprintf(
			'<li><a href="%s">%s</a>',
			add_query_arg(
				array(
					'sub' => $addon['slug']
				),
				admin_url( 'edit.php?post_type=ads&page=adsanity-settings&tab=add-ons' )
			),
			esc_html( $addon['name'] )
		);
	}
	echo implode( ' |</li>', $actions );
	echo '</li>';
?>
</ul>
<br class="clear" />

<form action="options.php" method="post" class="adsanity-<?php echo esc_attr( $sub ); ?>">
	<?php settings_fields( sprintf( 'adsanity-%s-options', $sub ) ); ?>
	<?php do_settings_sections( sprintf( 'adsanity-%s-options', $sub ) ); ?>

	<?php submit_button( __( 'Save Changes', 'adsanity' ) ); ?>
</form>
