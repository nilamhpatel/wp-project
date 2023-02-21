<?php
	/**
   * default template
	 */

	// Exit if accessed directly.
	defined( 'ABSPATH' ) || exit;

	get_header();  
?>

<div class="blid-default-page-container">
<?php include( 'components/page-header.php' ); ?>
<?php /*
<!-- <header class="reno-page-header">
        <div class="header-bkgd-img-container">
            <?php
            if( has_post_thumbnail( get_the_ID() ) ) {
            echo get_the_post_thumbnail( get_the_ID(), 'full' );
            }
            ?>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12" style=" position: absolute; z-index: 2; top: 0; left: 0;">
                    <div class="text-center" style="margin-top: 20px;">
                        <a href="/">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/logo-green-check.svg'; ?>" alt="" width="168" height="150" />
                        </a>
                    </div>

                    <h1 style="font-family: 'Poppins'; justify-content: center;
                    font-weight: 800;
                    height: 210px; display: flex; align-items: center;
                    font-size: 80px;
                    line-height: 105px;
                    text-transform: uppercase;
                    color: #FFFFFF;
                    text-shadow: 4px 4px 34px rgba(16, 16, 16, 0.5);"><?php echo get_the_title(); ?></h1>
                </div>
            </div>
        </div>
        </header> -->

        */ ?>
        <div  class="blid-default-page-content margintop100">
          <div class="container ml-1">
            <div class="row justify-content-md-center" style="padding-bottom: 120px; background: white; position: relative;">
            <div class="col-12">
                <div style="padding: 60px 0; max-width: 970px; margin: 0 auto; ">              
                    <?php
                      while( have_posts() ) {
                        the_post();

                        
                        the_content();
                      }
                    ?>
                  </div>
              </div>
            </div>
          </div>
        </div>



        <?php  
          include( 'components/advertisement.php' ); 
        ?>
</div>

<?php 
	get_footer();
