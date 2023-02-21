<?php
/**
 * The view for the General settings page.
 *
 * @package WordPress
 * @subpackage AdSanity
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

?>

<form action="options.php" method="post">
	<?php settings_fields( ADSANITY_ADMIN_OPTIONS ); ?>
	<?php do_settings_sections( ADSANITY_ADMIN_OPTIONS ); ?>

	<?php submit_button( __( 'Save Changes', 'adsanity' ) ); ?>
</form>

<div id="adsanity-reset-stats">
	<h2><?php esc_html_e( 'Reset Stats', 'adsanity' ); ?></h2>
	<?php if ( is_plugin_active( 'adsanity-google-analytics-tracking-integration/adsanity-google-analytics-tracking-integration.php' ) ) { ?>
		<p><?php esc_html_e( 'Note: Clicking this buttton will delete the internal, not remote Google Analytics, view and click data for all ads. This is a destructive, non-reversible action. We recommend you make a backup of your database before proceeding.', 'adsanity' ); ?></p>
	<?php } else { ?>
		<p><?php esc_html_e( 'Note: Clicking this buttton will delete the internal view and click data for all ads. This is a destructive, non-reversible action. We recommend you make a backup of your database before proceeding.', 'adsanity' ); ?></p>
	<?php } ?>
	<button id="reset-stats" class="reset-stats button"><?php esc_html_e( 'Reset All Stats', 'adsanity' ); ?></button>
</div>
