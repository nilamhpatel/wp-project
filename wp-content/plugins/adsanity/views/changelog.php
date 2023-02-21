<?php
/**
 * The changelog main view.
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
	<h1>AdSanity: <?php esc_html_e( 'Changelog', 'adsanity' ); ?></h1>

	<h2 class="nav-tab-wrapper wp-clearfix">
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-about' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab"><?php esc_html_e( 'Welcome', 'adsanity' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-add-ons' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab"><?php esc_html_e( 'Add-Ons', 'adsanity' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-support' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab"><?php esc_html_e( 'Support', 'adsanity' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-changelog' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab nav-tab-active"><?php esc_html_e( 'Changelog', 'adsanity' ); ?></a>
	</h2>
</div>

<div class="wrap about-wrap full-width-layout">

	<div class="feature-section one-col">
		<div class="col">
			<h2>
				<?php esc_html_e( 'AdSanity 1.9 features Custom Elementor Modules, an updated reporting interface, WordPress 6.0 Full-Site Editing compatibility, developer features, and more!', 'adsanity' ); ?>
			</h2>
			<p>
				<?php
					esc_html_e( 'There are a lot of things that we are proud of in this release. Lots of new features and improvements as well as a few bugfixes. We hope this makes your advertising experience even better.', 'adsanity' );
				?>
			</p>
		</div>
	</div>

	<div class="inline-svg full-width">
		<picture>
			<source media="(max-width: 500px)" srcset="<?php echo 'https://adsanityplugin.com/images/adsanity-plugin-460x550.jpg'; ?>">
			<img src="https://adsanityplugin.com/images/adsanity-plugin-1200x600.jpg" alt="<?php esc_attr_e( 'AdSanity: Simplified Ad Management for WordPress', 'adsanity' ); ?>">
		</picture>
	</div>

	<!-- What's New? -->
	<div class="feature-section one-col">
		<div class="col">
			<h2>
			<?php esc_html_e( 'What\'s New?', 'adsanity' ); ?>
			</h2>
		</div>
	</div>
	<section class="cards">
		<article class="card">
			<h1><?php esc_html_e( 'Custom Elementor Modules', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'If you are an Elementor user, you have probably had to use our widgets or shortcodes in the past. Well, no more! We built some custom Elementor modules so you can use the tools that you already know and love.', 'adsanity' ); ?></p>
		</article>
		<article class="card">
			<h1><?php esc_html_e( 'Reset Stats', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'We\'ve added a "Reset" button to delete (permanently) all internal statistics. This is a destructive operation and the data will no longer be retrievable.', 'adsanity' ); ?></p>
		</article>
		<article class="card">
			<h1><?php esc_html_e( 'Under-The-Hood Storage', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'We completely rewrote our metadata storage methodology to allow for alternative statistic storage which will increase performance overall.', 'adsanity' ); ?></p>
		</article>
		<article class="card">
			<h1><?php esc_html_e( 'More Actions and Filters', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'We added more developer oriented hooks to allow for further customization of the AdSanity platform.', 'adsanity' ); ?></p>
		</article>
	</section>

	<!-- Updated Features. -->
	<div class="feature-section one-col">
		<div class="col">
			<h2>
			<?php esc_html_e( 'Updated Features.', 'adsanity' ); ?>
			</h2>
		</div>
	</div>
	<section class="cards">
		<article class="card">
			<h1><?php esc_html_e( 'Reporting Engine', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'More obvious visual indicators have been added while custom reports are loading.', 'adsanity' ); ?></p>
		</article>
	</section>

	<!-- Bugs Squashed. -->
	<div class="feature-section one-col">
		<div class="col">
			<h2>
			<?php esc_html_e( 'Bugs Squashed.', 'adsanity' ); ?>
			</h2>
		</div>
	</div>
	<section class="cards">
		<article class="card">
			<h1><?php esc_html_e( 'Page Builders and Nested Loops', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'Compatibility with page builders and nested WordPress loops has been improved across the board.', 'adsanity' ); ?></p>
		</article>
		<article class="card">
			<h1><?php esc_html_e( 'WordPress 6.0 compatibility', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'WordPress 6.0 introduces the first iteration of the full-site editing experience. While it looks the same as the block editor, it has some subtle differences. We have updated the way our blocks work to handle this so you can put ads wherever you want in your theme.', 'adsanity' ); ?></p>
		</article>
	</section>

	<hr />

	<?php do_action( 'adsanity_changelog_screen' ); ?>

	<div class="return-to-dashboard">
		<a href="<?php echo esc_url( add_query_arg( 'post_type', 'ads', admin_url( 'post-new.php' ) ) ); ?>"><?php esc_html_e( 'Create Ad', 'adsanity' ); ?></a> |
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-stats' ), admin_url( 'edit.php' ) ) ); ?>"><?php esc_html_e( 'View Reports', 'adsanity' ); ?></a> |
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-add-ons' ), admin_url( 'edit.php' ) ) ); ?>"><?php esc_html_e( 'View Add-Ons', 'adsanity' ); ?></a> |
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-support' ), admin_url( 'edit.php' ) ) ); ?>"><?php esc_html_e( 'Get Support', 'adsanity' ); ?></a>
	</div>
</div>

<div class="clear"></div>
