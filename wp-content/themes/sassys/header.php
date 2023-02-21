<?php
  /**
   * The header for our theme
   * Displays all of the <head> section and everything up till <div id="content">
   *
   * @package Understrap
   */

  // Exit if accessed directly.
  defined( 'ABSPATH' ) || exit;
?>

<!DOCTYPE html>

<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php // fonts import ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?> <?php understrap_body_attributes(); ?>>
    <?php do_action( 'wp_body_open' ); ?>

    <div class="site" id="page">
	    <!-- <header class="reno-global-header-container">      
		    <a class="skip-link sr-only sr-only-focusable" 
           href="#content"><?php esc_html_e( 'Skip to content', 'sassys' ); ?></a>

        <nav id="main-nav" class="navbar navbar-expand-md reno-global-header-nav-container" aria-labelledby="main-nav-label">
        <a href="<?php echo get_home_url(); ?>">
          <img src="<?php echo get_stylesheet_directory_uri() . '/images/logo.png'; ?>" alt="" width="168" height="150" />
        </a>
					<h2 id="main-nav-label" class="screen-reader-text">
						<?php esc_html_e( 'Main Navigation', 'sassys' ); ?>
					</h2>

          <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" 
                  data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                  aria-expanded="false"
                  aria-label="<?php esc_attr_e( 'Toggle navigation', 'sassys' ); ?>">
            <div class="mobile-hamburger-icon not-active">
              <span></span>
              <span></span>
              <span></span>
            </div>
          </button>

          <?php
           /* wp_nav_menu(
              array(
                'theme_location'  => 'global-header-menu',
                'container_class' => 'navbar navbar-collapse text-center p-0 collapse',
                'container_id'    => 'navbarNavDropdown',
                'menu_class'      => 'navbar-nav m-auto',
                'fallback_cb'     => '',
                'menu_id'         => 'main-menu',
                'depth'           => 2,
                'walker'          => new sassys_WP_Bootstrap_Navwalker(),
              )
            );*/
          ?>

          <div class="reno-mobile-social-container d-none">
            <img src="<?php echo get_stylesheet_directory_uri() . '/images/logo.png'; ?>"
                 alt="" width="168" height="150" />
            
            <?php include( 'components/social-links.php' ); ?>
          </div>
				</nav>
      </header> -->
