<?php
	/**
	 * Template Name: Front Page template
	 */

	// Exit if accessed directly.
	defined( 'ABSPATH' ) || exit;

  $hero_img   = get_post_meta( get_the_ID(), 'hero_bkgd_img', true );
  $hero_text  = get_post_meta( get_the_ID(), 'hero_text', true );
  $hero_video = get_post_meta( get_the_ID(), 'hero_video_url', true );

  $carousel_group = get_post_meta( get_the_ID(), 'carousel_group', true );

  $news_ad     = get_post_meta( get_the_ID(), 'news_shortcode_ad', true );
  $news_header = get_post_meta( get_the_ID(), 'news_heading', true );
  $news_posts  = get_posts( array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
  ) );
  
  $faq_message = get_post_meta( get_the_ID(), 'faq_message', true );
  $faq_heading = get_post_meta( get_the_ID(), 'faq_heading', true );
  $faq_group   = get_post_meta( get_the_ID(), 'faq_group', true );
  $faq_ad   = get_post_meta( get_the_ID(), 'faq_shortcode_ad', true );

	get_header();  
?>

<div class="reno-home-page-container">
  <div class="reno-home-hero-container" <?php if( !empty( $hero_img ) ) echo 'style="background-image: url(' . $hero_img . ');"'; ?>>

  <header class="reno-global-header-container">      
		    <a class="skip-link sr-only sr-only-focusable" href="#content"><?php esc_html_e( 'Skip to content', 'sassys' ); ?></a>

        <nav id="main-nav" class="navbar navbar-expand-md reno-global-header-nav-container" aria-labelledby="main-nav-label">
        <div class="home-logo-container">
            <a href="<?php echo get_home_url(); ?>">
              <img src="<?php echo get_stylesheet_directory_uri() . '/images/logo.png'; ?>" alt="" width="168" height="150" />
            </a>
        </div>
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
            wp_nav_menu(
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
            );
          ?>

          <!-- <div class="reno-mobile-social-container d-none">
            <img src="<?php echo get_stylesheet_directory_uri() . '/images/logo.png'; ?>" alt="" width="168" height="150" />
            
            <?php include( 'components/social-links.php' ); ?>
          </div> -->
				</nav>
      </header>

    <div class="container">
      <div class="row">
        <div class="col">
          <?php 
            /*include( 'components/header-logo.php' ); */
            
            echo '<h1 class="homwbannertxt">' . $hero_text . '</h1>';
          ?>
        </div>
      </div>
    </div>
  </div>

  <!-- <div class="reno-renovator-search-container">
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="reno-renovator-outer-container">
            <h2><?= __( 'Find Your sassys Renovator', 'sassys' ); ?></h2>

            <?php include( 'components/reno-search-form.php' ); ?>
          </div>
        </div>
      </div>
    </div>
  </div> -->

  <?php /*if( !empty( $carousel_group ) ) { ?>
    <div class="reno-home-testimonial-container">
      <div id="frontPageTestimonialSlider" class="carousel slide"
           data-bs-ride="carousel" data-bs-interval="12345">
        <div class="carousel-inner">
          <?php 
            $slide_num   = 01;
            $slide_total = count( $carousel_group );

            foreach( $carousel_group as $slide ) {
              $img_id   = $slide[ 'carousel_image_id' ];
              $quote    = $slide[ 'carousel_quote' ];
              $name     = $slide[ 'carousel_name' ];
              $location = $slide[ 'carousel_location' ];
          ?>
            <div class="carousel-item <?php if( $slide_num == 01 ) echo 'active'; ?>">
              <?php echo wp_get_attachment_image( $img_id, 'full', false ); ?>
        
              <div class="container">                
                <div class="testimonial-container">
                  
                <img src="<?php echo get_stylesheet_directory_uri() . '/images/quote.svg'; ?>"
                     alt="" width="43" height="43" class="quote-img" />
                     <div style="position: relative;">

                  <div class="quote"><?= wpautop( $quote ); ?></div>
                  <p class="person"><?= $name; ?></p>
                  <p class="location"><?= $location; ?></p>
                
                  <div class="pagination-container">
                    <span><?= sprintf( "%02d", $slide_num ); ?></span>
                    <div class="testimonial-hyphen"></div>
                    <span><?= sprintf( "%02d", $slide_total ); ?></span>
                  </div>

                  <div class="pagination-control-container" style="">
                    <button type="button" data-bs-slide="prev"
                            data-bs-target="#frontPageTestimonialSlider">
                      <i class="fa-solid fa-chevron-left"></i>

                      <span class="visually-hidden">Previous</span>
                    </button>

                    <button type="button" data-bs-slide="next"
                            data-bs-target="#frontPageTestimonialSlider">
                      <i class="fa-solid fa-chevron-right"></i>
                      
                      <span class="visually-hidden">Next</span>
                    </button>
                  </div>
                  </div>
                </div>
              </div>
            </div>
          <?php 
            $slide_num++;
          
            } 
          ?>
        </div>      
      </div>
    </div>
  <?php }*/ ?>

  <div class="reno-home-welcome-container">    
    <div class="container">
        <div class="row justify-content-md-center">        
            <div class="col-12 col-lg-6">
              <div class="welcometext">
              <img src="<?php echo get_stylesheet_directory_uri() . '/images/08.png'; ?>" alt="" width="" height="" />
                <h2>Welcome to<span> Sassy's</span></h2>
                <div class="txt">
                  <p>Sassy's is a family owned and operated business in the heart of Thorndale, ON. What started as a pizza shop has grown and transformed over the years into a bakery, deli & pizzeria. We offer a large variety of fresh baked breads and sweets, a large take-out menu with something for everyone, Shaw's Ice Cream, deli meats, cheeses and so much more. Our commercial bakery services about a 40 mile radius around Thorndale, providing baked goods to many grocery stores, restaurants, bakeries, golf courses & caters.</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-6">
              <div class="row justify-content-md-center welcomeimgs">  
                  <div class="col-12 col-lg-6 mb-4 mb-lg-0 welcomeimg01">
                      <img src="<?php echo get_stylesheet_directory_uri() . '/images/05.png'; ?>" alt="" width="" height="" />
                      <div class="img1txt"><span>Special Pizza</span><br>Only $50</div>
                  </div>
                  <div class="col-12 col-lg-6  welcomeimg02">
                      <img src="<?php echo get_stylesheet_directory_uri() . '/images/06.png'; ?>" alt="" width="" height="" />
                      <div class="img2txt"><span>Family Pizza</span><br>Only $50</div>
                  </div>
              </div>
            </div>
        </div>
    </div>
  </div>


  <div class="reno-home-simpleimg-container">    
    <div class="row justify-content-md-center">        
          <div class="col-12">
              <img src="<?php echo get_stylesheet_directory_uri() . '/images/07.png'; ?>" alt="" width="" height="" />
          </div>       
    </div>   
  </div>


  
  <div class="reno-home-special-container ">    
     <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12 col-lg-6 specialdays-img"> 
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            <div class="col-12 col-lg-6 specialdays-menu">
           
              <div class="specialtext">             
                <h2>Sassy's Special</h2>
                <p class="specialdates">June 15-17 ( Avalilable 11am - 2pm )</p>
                <div class="speciallist">
                  <div class="specialtxt">
                    <span>Monday</span>
                    <p>2 Piece Chicken with Fries, Salad, Roll & Can Pop $10.99</p>
                  </div>
                  <div class="specialtxt">
                    <span>Tuesday</span>
                    <p>3 Piece Chicken with Fries, Salad, Roll & Can Pop $12.50</p>
                  </div>
                  <div class="specialtxt">
                    <span>Wednesday</span>
                    <p>4 Piece Chicken with Fries, Salad, Roll & Can Pop $12.99</p>
                  </div>
                  <div class="specialtxt">
                    <span>Thursday</span>
                    <p>Medium Fry & 2 Medium Salads $9.99</p>
                  </div>
                  <div class="specialtxt">
                    <span>Friday</span>
                    <p>Chicken Parmesan Sandwich with fries & a can of pop $10.99</p>
                  </div>
                  <div class="specialtxt">
                    <span>Saturday</span>
                    <p>Large Fry & 2 Large Salads $39.99</p>
                  </div>
                  <div class="specialtxt">
                    <span>Sunday</span>
                    <p>Large Fry & 3 Large Salad $49.99</p>
                  </div>

                </div>
                
                </div>
              </div>
            </div>
        </div>
  </div>

</div>


<div class="reno-home-special-container">    
     <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12 col-lg-6 "> 
              <div class="reno-contact-page-container " >
                  <?php 
                  $contactUsFormId = "1";
                  if($contactUsFormId > 0){ ?>
                          <div class="gformright p-4 p-lg-0">
                              <div class="gforminquiries">                            
                                  <?php gravity_form($contactUsFormId, true, false, false, null, true); ?>                           
                              </div>
                          </div>
                  <?php } ?>  
              </div>
            </div>
            <div class="col-12 col-lg-6">           
              <div class="footertext">               
                <div class="footer-info p-5 p-lg-0">
                  <div class="footer-address">
                    <p>225 King St. <br>Throndale Ontario, N0M 2P0</p>
                  </div>
                  <div class="footer-email">
                    <p>sassys.thorndale@gmail.com</p>
                  </div>
                  <div class="footer-phone">
                    <p>(519) 461-1234</p>
                  </div>
                </div>

                <div class="footer-hours">
                <table class="uk-table">
                <tbody>
                  <tr>
                    <td style="width: 100px;">Monday</td>
                    <td>7:00 AM - 8:00 PM</td>
                  </tr>
                  <tr>
                    <td>Tuesday</td>
                    <td>7:00 AM - 8:00 PM</td>
                  </tr>
                  <tr>
                    <td>Wednesday</td>
                    <td>7:00 AM - 8:00 PM</td>
                  </tr>
                  <tr>
                    <td>Thursday</td>
                    <td>7:00 AM - 8:00 PM</td>
                  </tr>
                  <tr>
                    <td>Friday</td>
                    <td>7:00 AM - 8:00 PM</td>
                  </tr>
                  <tr>
                    <td>Saturday</td>
                    <td>7:00 AM - 8:00 PM</td>
                  </tr>
                  <tr>
                    <td>Sunday</td>
                    <td>7:00 AM - 8:00 PM</td>
                  </tr>
                </tbody>
                </table>
                </div>


              </div>
            </div>


            <div class="col-12"> 
              <?php  include( 'components/social-links.php' );  ?>
            </div>

            
        </div>
  </div>
</div>

<?php
	get_footer();
