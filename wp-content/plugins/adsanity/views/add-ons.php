<?php
/**
 * The add-ons main view.
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
	<h1>AdSanity: <?php esc_html_e( 'Add-Ons', 'adsanity' ); ?></h1>

	<h2 class="nav-tab-wrapper wp-clearfix">
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-about' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab"><?php _e( 'Welcome', 'adsanity' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-add-ons' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab nav-tab-active"><?php _e( 'Add-Ons', 'adsanity' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-support' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab"><?php _e( 'Support', 'adsanity' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'post_type' => 'ads', 'page' => 'adsanity-changelog' ), admin_url( 'edit.php' ) ) ); ?>" class="nav-tab"><?php _e( 'Changelog', 'adsanity' ); ?></a>
	</h2>
</div>
<div class="wrap about-wrap full-width-layout">

	<div class="feature-section one-col">
		<div class="col">
			<h2>
				<?php esc_html_e( 'Available Add-Ons', 'adsanity' ); ?>
			</h2>
			<p>
			<?php
				esc_html_e( 'AdSanity has a number of free add-ons that are available to every AdSanity Core license holder. In addition, we have several pro add-ons that are freely available for AdSanity Developer License holders and available for purchase for our other license holders. You can see details for each of our add-ons below.', 'adsanity' );
			?>
			</p>
		</div>
	</div>

	<?php

	// Get AdSanity products.
	$products = wp_remote_get( 'https://adsanityplugin.com/edd-api/products/?number=-1' );
	$products = json_decode( wp_remote_retrieve_body( $products ) );
	if ( property_exists( $products, 'products' ) ) {

		$products = $products->products;

		// Array to store add-ons.
		$add_ons = array(
			'pro'   => array(),
			'basic' => array(),
		);

		// Split add-ons into pro and basic.
		foreach ( $products as $product ) {

			if ( property_exists( $product, 'info' ) &&
				property_exists( $product->info, 'category' ) &&
				is_array( $product->info->category ) ) {

				// Loop over this item's categories.
				foreach ( $product->info->category as $category ) {

					if ( 'pro' === $category->slug ) {
						array_push( $add_ons['pro'], $product );
					}

					if ( 'basic' === $category->slug ) {
						array_push( $add_ons['basic'], $product );
					}

				}

			}

		}

		$add_on_card = '<article class="card">
			<div class="optional-heading">%s</div>
			<div class="image-wrap"><img src="%s" /></div>
			<h1>%s</h1>
			<p>%s</p>
			<div class="button-wrap"><a href="%s" target="_blank">%s</a></div>
		</article>';
		?>
		<section class="cards">
			<header>
				<h1><?php esc_html_e( 'Pro Add-Ons', 'adsanity' ); ?></h1>
			</header>
			<?php
			// Print out pro cards.
			foreach ( $add_ons['pro'] as $add_on ) {

				printf(
					$add_on_card, // Escaped above.
					(
						is_plugin_active( $add_on->info->file_path ) ?
							'<p class="active">Active</p>' :
							(
								file_exists( trailingslashit( WP_PLUGIN_DIR ) . $add_on->info->file_path ) ?
									'<p class="inactive">Inactive</p>' :
									''
							)
					),
					esc_url( $add_on->info->thumbnail ),
					esc_html( $add_on->info->title ),
					esc_html( $add_on->info->excerpt ),
					esc_url( $add_on->info->link ),
					esc_html__( 'More Info', 'adsanity' )
				);

			}
			?>
		</section>
		<section class="cards">
			<header>
				<h1><?php esc_html_e( 'Basic Add-Ons', 'adsanity' ); ?></h1>
			</header>
			<?php
			// Print out basic cards.
			foreach ( $add_ons['basic'] as $add_on ) {


				printf(
					$add_on_card, // Escaped above.
					(
						is_plugin_active( $add_on->info->file_path ) ?
							'<p class="active">Active</p>' :
							(
								file_exists( trailingslashit( WP_PLUGIN_DIR ) . $add_on->info->file_path ) ?
									'<p class="inactive">Inactive</p>' :
									''
							)
					),
					esc_url( $add_on->info->thumbnail ),
					esc_html( $add_on->info->title ),
					esc_html( $add_on->info->excerpt ),
					esc_url( 'https://adsanityplugin.com/checkout/purchase-history/' ),
					esc_html__( 'Download', 'adsanity' )
				);

			}
			?>
		</section>
		<?php } else { ?>
		<div class="notice error">
			<p>
				<?php esc_html_e( 'There was an error connecting to adsanityplugin.com. Please try your request later.', 'adsanity' ); ?>
			</p>
		</div>
		<?php } ?>
</div>
