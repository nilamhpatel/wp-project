<?php
/**
 * The About main view.
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
	<h1><?php _e( 'Welcome to ', 'adsanity' ); ?> AdSanity</h1>

	<h2 class="nav-tab-wrapper wp-clearfix">
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-about' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab nav-tab-active"><?php _e( 'Welcome', 'adsanity' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-add-ons' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab"><?php _e( 'Add-Ons', 'adsanity' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-support' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab"><?php _e( 'Support', 'adsanity' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-changelog' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab"><?php _e( 'Changelog', 'adsanity' ); ?></a>
	</h2>
</div>
<div class="wrap about-wrap full-width-layout">

	<div class="feature-section one-col">
		<div class="col">
			<h2>
				<?php esc_html_e( 'AdSanity is the easiest Ad Management plugin for WordPress.', 'adsanity' ); ?>
			</h2>
			<p>
				<?php
					esc_html_e( 'If you have used WordPress to write blog posts or pages, you already know how to create ads in AdSanity. We have purposefully used the WordPress interface to make AdSanity easy to pick up without training.', 'adsanity' );
				?>
			</p>
		</div>
	</div>

	<div class="inline-svg full-width">
		<picture>
			<source media="(max-width: 500px)" srcset="https://adsanityplugin.com/images/about-460x550.gif">
			<img src="https://adsanityplugin.com/images/about.gif" alt="">
		</picture>
	</div>

	<!-- Easy Set Up -->
	<div class="feature-section one-col">
		<div class="col">
			<h2>
				<?php esc_html_e( 'Easy Set Up', 'adsanity' ); ?>
			</h2>
		</div>
	</div>
	<section class="cards">
		<article class="card">
			<div class="image-wrap">
				<img src="https://adsanityplugin.com/images/familiar-user-interface.jpg" alt="<?php esc_attr_e( 'WordPress logo', 'adsanity' ); ?>">
			</div>
			<h1><?php esc_html_e( 'Familiar User Interface', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'Users familiar with managing posts and categories will find managing ads and groups easy.', 'adsanity' ); ?></p>
		</article>
		<article class="card">
			<div class="image-wrap">
				<img src="https://adsanityplugin.com/images/automatic-tracking.jpg" alt="<?php esc_attr_e( 'Line graph icon', 'adsanity' ); ?>">
			</div>
			<h1><?php esc_html_e( 'Automatic Tracking', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'As soon as you install AdSanity and place ads on your site, we start tracking views and clicks.', 'adsanity' ); ?></p>
		</article>
	</section>

	<!-- Placing Your Ads -->
	<div class="feature-section one-col">
		<div class="col">
			<h2>
				<?php esc_html_e( 'Placing Your Ads', 'adsanity' ); ?>
			</h2>
		</div>
	</div>
	<section class="cards">
		<article class="card">
			<div class="image-wrap">
				<img src="https://adsanityplugin.com/images/gutenberg.jpg" alt="<?php esc_attr_e( 'Gutenberg blocks', 'adsanity' ); ?>">
			</div>
			<h1><?php esc_html_e( 'Gutenberg Blocks', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'A new editing experience has come to WordPress &mdash; Gutenberg. You can now include ads natively in Gutenberg with our three core blocks, with additional blocks available in our optional add-ons.', 'adsanity' ); ?></p>
		</article>
		<article class="card">
			<div class="image-wrap">
				<img src="https://adsanityplugin.com/images/beaver-builder.jpg" alt="<?php esc_attr_e( 'Beaver builder modules', 'adsanity' ); ?>">
			</div>
			<h1><?php esc_html_e( 'Beaver Builder Modules', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'Page builder plugins are increasingly common these days. At Pixel Jar, we love Beaver Builder. So as soon as we finalized Gutenberg support, we built some custom modules for Beaver Builder.', 'adsanity' ); ?></p>
		</article>
		<article class="card">
			<div class="image-wrap">
				<img src="https://adsanityplugin.com/images/divi.jpg" alt="<?php esc_attr_e( 'Divi modules', 'adsanity' ); ?>">
			</div>
			<h1><?php esc_html_e( 'Divi Modules', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'The popularity of page builders has given rise to the Divi page builder by Elegant Themes. With a large community like this, we decided to build modules to support Divi directly!', 'adsanity' ); ?></p>
		</article>
		<article class="card">
			<div class="image-wrap">
				<img src="https://adsanityplugin.com/images/elementor.jpg" alt="<?php esc_attr_e( 'Elementor modules', 'adsanity' ); ?>">
			</div>
			<h1><?php esc_html_e( 'Elementor Modules', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'Not to be outdone, Elementor offers it\'s own take on the page building space. We have been getting more of our customers asking for Elementor modules to make their building easier, so we have those too.', 'adsanity' ); ?></p>
		</article>
		<article class="card">
			<div class="image-wrap">
				<img src="https://adsanityplugin.com/images/widgets.jpg" alt="<?php esc_attr_e( 'Page layout icon', 'adsanity' ); ?>">
			</div>
			<h1><?php esc_html_e( 'Widgets', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'Most themes have widget areas. We provide three widgets with AdSanity Core, and more options through our optional add-ons.', 'adsanity' ); ?></p>
		</article>
		<article class="card">
			<div class="image-wrap">
				<img src="https://adsanityplugin.com/images/shortcodes.jpg" alt="<?php esc_attr_e( 'An AdSanity shortcode within sample text', 'adsanity' ); ?>">
			</div>
			<h1><?php esc_html_e( 'Shortcodes', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'Sometimes you want to include ads right within your content. AdSanity allows you to do that through easy to access shortcodes.', 'adsanity' ); ?></p>
		</article>
		<article class="card">
			<div class="image-wrap">
				<img src="https://adsanityplugin.com/images/template-tags.jpg" alt="<?php esc_attr_e( 'An AdSanity template tag in code', 'adsanity' ); ?>">
			</div>
			<h1><?php esc_html_e( 'Template Tags', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'You can use our template tags to place ads anywhere on your site through the WordPress template structure.', 'adsanity' ); ?></p>
		</article>
	</section>

	<!-- Customization -->
	<div class="feature-section one-col">
		<div class="col">
			<h2>
				<?php esc_html_e( 'Customization', 'adsanity' ); ?>
			</h2>
		</div>
	</div>
	<section class="cards">
		<article class="card">
			<div class="image-wrap">
				<img src="https://adsanityplugin.com/images/publishing-options.jpg" alt="<?php esc_attr_e( 'calendar icon', 'adsanity' ); ?>">
			</div>
			<h1><?php esc_html_e( 'Publishing Options', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'You probably want to use your house ads forever, but you will also have advertisers that only want to advertise for a period of time. AdSanty can handle that.', 'adsanity' ); ?></p>
		</article>
		<article class="card">
			<div class="image-wrap">
				<img src="https://adsanityplugin.com/images/reporting.jpg" alt="<?php esc_attr_e( 'bar graph icon', 'adsanity' ); ?>">
			</div>
			<h1><?php esc_html_e( 'Reporting', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'We pull together some high level stats for you on the dashboard, but you also have control to slice and dice your stats however you want.', 'adsanity' ); ?></p>
		</article>
		<article class="card">
			<div class="image-wrap">
				<img src="https://adsanityplugin.com/images/add-ons.jpg" alt="<?php esc_attr_e( 'plug icon', 'adsanity' ); ?>">
			</div>
			<h1><?php esc_html_e( 'Add-Ons', 'adsanity' ); ?></h1>
			<p><?php esc_html_e( 'We built AdSanity to be extensible so that you can build specific integrations for your needs. We even have some integrations out already built for you!', 'adsanity' ); ?></p>
		</article>
	</section>

	<hr />

	<?php do_action( 'adsanity_about_screen' ); ?>

	<div class="return-to-dashboard">
		<a href="<?php echo esc_url( add_query_arg( 'post_type', 'ads', admin_url( 'post-new.php' ) ) ); ?>"><?php _e( 'Create Ad', 'adsanity' ); ?></a> |
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-stats' ), admin_url( 'edit.php' ) ) ); ?>"><?php _e( 'View Reports', 'adsanity' ); ?></a> |
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-add-ons' ), admin_url( 'edit.php' ) ) ); ?>"><?php _e( 'View Add-Ons', 'adsanity' ); ?></a> |
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-support' ), admin_url( 'edit.php' ) ) ); ?>"><?php _e( 'Get Support', 'adsanity' ); ?></a>
	</div>
</div>

<div class="clear"></div>
