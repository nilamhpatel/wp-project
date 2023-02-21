<?php
/**
 * The main support view.
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
<div class="wrap">
	<h1>AdSanity: <?php _e( 'Support', 'adsanity' ); ?></h1>

	<h2 class="nav-tab-wrapper wp-clearfix">
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-about' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab"><?php _e( 'Welcome', 'adsanity' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-add-ons' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab"><?php _e( 'Add-Ons', 'adsanity' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-support' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab nav-tab-active"><?php _e( 'Support', 'adsanity' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-changelog' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab"><?php _e( 'Changelog', 'adsanity' ); ?></a>
	</h2>
</div>
<div class="wrap about-wrap full-width-layout">

	<div class="feature-section one-col">
		<div class="col">
			<h2>
				<?php _e( 'First-Class Support', 'adsanity' ); ?>
			</h2>
			<p><?php
				printf(
					__( 'If you have any questions about using AdSanity you can access our <a href="%s" target="_blank">support site</a> day or night. If you\'re not able to find the answers you need you can always <a href="%s" target="_blank">submit a support request</a> to our support team and we\'ll help get you the solution you need.', 'adsanity' ),
					'https://adsanityplugin.com/help/',
					'https://pixeljarsupport.zendesk.com/hc/en-us/requests/new'
				);
			?></p>
			<p><?php
				printf(
					__( 'View the %s <a href="%s">about page</a>.', 'adsanity' ),
					'AdSanity',
					add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-about' ), admin_url( 'edit.php' ) )
				);
			?></p>
		</div>
	</div>

	<div class="inline-svg full-width">
		<picture>
			<source media="(max-width: 500px)" srcset="<?php echo 'https://adsanityplugin.com/images/adsanity-help-460x550.jpg'; ?>">
			<img src="https://adsanityplugin.com/images/adsanity-help.jpg" alt="<?php esc_attr_e( 'Screenshot of the AdSanity support site', 'adsanity' ); ?>">
		</picture>
	</div>

	<div class="feature-section one-col">
		<div class="col">
			<h2>
				<?php _e( 'Caching Notice', 'adsanity' ); ?>
			</h2>
			<p><?php _e( 'AdSanity works best when using object caching. If you or your host is using a static page caching system or plugin, like Varnish or WP Super Cache, you may see discrepancies with your internal tracking numbers. In this case, we recommend that you use our Google Analytics Tracking Integration add-on.', 'adsanity' ); ?></p>


		</div>
	</div>

	<?php do_action( 'adsanity_support_screen' ); ?>

</div>
<div class="clear"></div>
