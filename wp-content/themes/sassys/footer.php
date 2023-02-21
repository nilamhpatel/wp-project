<?php
  /**
   * The template for displaying the footer
   * Contains the closing of the #content div and all content after
   *
   */

  // Exit if accessed directly.
  defined( 'ABSPATH' ) || exit;

  $instagram_feed_id = sassys_theme_site_option( 'instagram_feed_id', false );
  $callout_message   = sassys_theme_site_option( 'callout_message', false );
  $cta_image_id      = sassys_theme_site_option( 'cta_image_id', false );
  $cta_title         = sassys_theme_site_option( 'cta_title', false );
  $cta_url           = sassys_theme_site_option( 'cta_url', false );

  $footer_copy_text = __( 'Sassys.  All Rights Reserved.', 'sassys' );
?>

      <footer class="reno-footer-container">
        <div class="container">
          <div class="row justify-content-lg-center call-to-action-container">
            <div class="col-lg-6">
            <div class="text-center">         
              <img src="<?php echo get_stylesheet_directory_uri() . '/images/logo.png'; ?>" class="footer-logo-center" alt="" width="168" height="150" />
            </div>
            </div>
          </div>          
                
          <div class="nav-container">
            <?php
              echo '<span>&copy; ' . get_the_date( 'Y' ) . ' ' . $footer_copy_text . '</span>'; 
                  
             /* wp_nav_menu( array(
                'theme_location' => 'footer-policy-nav',
                'menu_class'     => '',
                'depth'          => 1,
                'container'      => false,
                'fallback_cb'    => false
              ) ); */
            ?>
          </div>
        </div>
      </footer>
      
    </div><!-- #page we need this extra closing tag here -->

    <?php wp_footer(); ?>
  </body>
</html>
