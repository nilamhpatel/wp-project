<?php
	/**
	 * Template Name: Design Inspiration template
	 */

	// Exit if accessed directly.
	defined( 'ABSPATH' ) || exit;

  $news_posts = get_terms( array(
    'taxonomy'       => 'design-inspiration-cat',
    'posts_per_page' => -1,
  ) );

  $design_ad = get_post_meta( get_the_ID(), 'design_shortcode_ad', true );

	get_header();  
?>

<div class="reno-design-inspiration-page-container">
  <?php include( 'components/page-header.php' ); ?>

  <div class="reno-category-cards-container">
    <div class="container">
      <div class="reno-category-row row row-cols-1 row-cols-md-2 row-cols-xl-3">
        <?php 
          foreach( $news_posts as $cat ) {
            $id       = $cat -> term_id;
            $name     = $cat -> name;
            $image_id = get_term_meta( $id, 'category-image-id', true );
        ?>
          <div class="col">
            <div class="card reno-card-container">
              <a href="<?= get_term_link( $id ); ?>" class="position-relative">
                <?php echo wp_get_attachment_image ( $image_id, 'large' ); ?>

                <div class="background-gradient"></div>

                <h2 class="card-title"><?= $name; ?></h2>
              </a>
            </div>
          </div>
        <?php } ?>
      </div>

      <?php
        if( !empty( $design_ad ) ) {
          $advertisement = $design_ad;
        
          include( 'components/advertisement.php' ); 
        }  
      ?>
    </div>
  </div>
</div>

<?php
	get_footer();
