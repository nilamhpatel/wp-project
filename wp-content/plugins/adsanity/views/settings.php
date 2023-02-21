<?php
/**
 * The main settings view.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

	$is_general_tab = true;
	$is_add_ons_tab = false;
	$is_licenses_tab = false;

	if ( isset( $_GET['tab'] ) && $_GET['tab'] != 'general' ) {
		$is_general_tab = false;

	}

	if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'add-ons' ) {
		$is_add_ons_tab = true;

	} elseif ( isset( $_GET['tab'] ) && $_GET['tab'] == 'licenses' ) {
		$is_licenses_tab = true;

	}
?>
<div class="wrap">
	<h1>AdSanity: <?php _e( 'Settings', 'adsanity' ); ?></h1>

	<?php if ( isset( $_REQUEST['settings-updated'] ) ) : ?>
		<?php if ( $_REQUEST['settings-updated'] == 'true' ) : ?>
			<div id="message" class="updated settings-error"><p><?php _e( 'Settings saved', 'adsanity' ) ?></p></div>
		<?php else : ?>
			<div id="message" class="error settings-error"><p><?php _e( 'Settings not saved', 'adsanity' ) ?></p></div>
		<?php endif; ?>
	<?php endif; ?>

	<h2 class="nav-tab-wrapper">
		<a class="nav-tab<?php echo ( $is_general_tab ? ' nav-tab-active' : '' ) ?>" href="<?php echo admin_url( 'edit.php?post_type=ads&page=adsanity-settings&tab=general' ) ?>">
			<?php _e( 'General', 'adsanity' ) ?>
		</a>
		<?php if ( has_filter( 'adsanity-addons' ) ) : ?>
		<a class="nav-tab<?php echo ( $is_add_ons_tab ? ' nav-tab-active' : '' ) ?>" href="<?php echo admin_url( 'edit.php?post_type=ads&page=adsanity-settings&tab=add-ons' ) ?>">
			<?php _e( 'Add-Ons', 'adsanity' ) ?>
		</a>
		<?php endif; ?>
		<a class="nav-tab<?php echo ( $is_licenses_tab ? ' nav-tab-active' : '' ) ?>" href="<?php echo admin_url( 'edit.php?post_type=ads&page=adsanity-settings&tab=licenses' ) ?>">
			<?php _e( 'Licenses', 'adsanity' ) ?>
		</a>
	</h2>

	<?php
		if ( $is_add_ons_tab ) {
			require_once( ADSANITY_VIEWS . 'settings-add-ons.php' );
		} elseif ( $is_licenses_tab ) {
			require_once( ADSANITY_VIEWS . 'settings-licenses.php' );
		} else {
			require_once( ADSANITY_VIEWS . 'settings-general.php' );
		}
	?>

</div>
<div class="clear"></div>
