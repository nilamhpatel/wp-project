<?php
/**
 * The licenses view within the settings.
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
<form action="options.php" method="post" class="adsanity-licenses">
	<?php settings_fields( 'adsanity-licenses' ); ?>
	<?php do_settings_sections( 'adsanity-licenses' ); ?>

	<?php submit_button( __( 'Save Changes', 'adsanity' ) ); ?>
</form>
