<?php
	/**
	 * Template Name: Single Category template
	 */

	// Exit if accessed directly.
	defined( 'ABSPATH' ) || exit;

  $term      = get_the_terms( get_the_ID(), 'design-inspiration-cat' );
  $tax_id    = $term[0] -> term_taxonomy_id;
  $term_name = $term[0] -> name;
  $back_link = get_term_link( $tax_id );

  $project_member = get_post_meta( get_the_ID(), 'project_member', true );
  $project_city = get_post_meta( get_the_ID(), 'project_city', true );
  $project_year = get_post_meta( get_the_ID(), 'project_year', true );
  $project_duration = get_post_meta( get_the_ID(), 'project_duration', true );
  $project_budget = get_post_meta( get_the_ID(), 'project_budget', true );
  $project_awards = get_post_meta( get_the_ID(), 'project_awards', true );
  $project_logo_id = get_post_meta( get_the_ID(), 'project_logo_id', true );
  $project_before_after = get_post_meta( get_the_ID(), 'project_before_after', true );
  $project_image_gallery = get_post_meta( get_the_ID(), 'project_image_gallery', true );

  $interested_bkgd    = get_post_meta( get_the_ID(), 'interested_bkgd', true );
  $interested_heading = get_post_meta( get_the_ID(), 'interested_heading', true );
  $interested_content = get_post_meta( get_the_ID(), 'interested_content', true );
  $interested_email   = get_post_meta( get_the_ID(), 'interested_email', true );

  $project_ad = get_post_meta( get_the_ID(), 'project_shortcode_ad', true );

  // exclude the current post
  $similar_projects = new WP_Query( array(
    'post_type'      => 'design-inspiration',
    'posts_per_page' => 3,
    'orderby'        => 'rand',
    'post__not_in'   => array( $post -> ID ),
    'tax_query'      => array(
      array(
        'taxonomy' => 'design-inspiration-cat',
        'field'    => 'term_id',
        'terms'    => array( $tax_id )
      )
    )
  ) );

	get_header();  
?>

<div class="reno-project-post-container">
  <?php include( 'components/page-header.php' ); ?>

  <div class="reno-outer-project-content-container">
    <div class="container">
      <div class="row reno-back-to-parent-container">
        <a href="<?= $back_link; ?>">
          <i class="fa-solid fa-chevron-left"></i> 
          
          <?= __( 'Back to ', 'sassys' ) . $term_name; ?>
        </a>
      </div>
    </div>

    <div class="reno-inner-project-content-container">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="project-details-container">
              <div class="data-container">
                <?php if( !empty( $project_member ) ) { ?>
                  <p class="label"><?= __( 'Member:', 'sassys' ); ?></p>
                  
                  <p class="member-value"><?= $project_member; ?></p>
                <?php } ?>

                <div class="data-row">
                  <div class="d-flex">
                    <?php if( !empty( $project_member ) ) { ?>
                      <div class="data-column">
                        <p class="label"><?= __( 'City:', 'sassys' ); ?></p>
                        <p class="data"><?= $project_city; ?></p>
                      </div>
                    <?php 
                      }
                    
                      if( !empty( $project_year ) ) {
                    ?>
                      <div class="data-column">
                        <p class="label"><?= __( 'Year:', 'sassys' ); ?></p>
                        <p class="data"><?= $project_year; ?></p>
                      </div>
                    <?php } ?>
                  </div>

                  <div class="d-flex">
                    <?php if( !empty( $project_duration ) ) { ?>
                      <div class="data-column">
                        <p class="label"><?= __( 'Duration:', 'sassys' ); ?></p>
                        <p class="data"><?= $project_duration; ?></p>
                      </div>
                    <?php 
                      }
                    
                      if( !empty( $project_budget ) ) {
                    ?>
                      <div class="data-column">
                        <p class="label"><?= __( 'Budget:', 'sassys' ); ?></p>
                        <p class="data"><?= $project_budget; ?></p>
                      </div>
                    <?php } ?>
                  </div>
                </div>

                <?php if( !empty( $project_awards ) ) { ?>
                  <div>
                    <p class="label"><?= __( 'Awards:', 'sassys' ); ?></p>
                    <p class="data"><?= $project_awards; ?></p>
                  </div>
                <?php } ?>
              </div>

              <?php if( !empty( $project_logo_id ) ) { ?>
                <div class="d-flex align-items-center">
                  <?php
                    echo wp_get_attachment_image( 
                      $project_logo_id, array( 234, 234 ), false, array( 'class' => '' )
                    );
                  ?>
                </div>
              <?php } ?>
            </div>

            <?php 
  			      while( have_posts() ) {
                echo '<div class="content-container">';
					        the_post();
                  the_content();
                echo '</div>';
              }
            
              if( !empty( $project_before_after ) ) { 
            ?>
              <div class="reno-before-and-after-slider-container">
                <?php echo do_shortcode( $project_before_after ); ?>
              </div>
            <?php
              }

              if( !empty( $project_image_gallery ) ) { 
            ?>
              <div class="reno-image-gallery-container">
                <?php echo do_shortcode( $project_image_gallery ); ?>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <?php 
    if( !empty( $interested_bkgd ) &&
        !empty( $interested_heading ) && 
        !empty( $interested_email ) ) { 
  ?>
    <div class="reno-interested-bkgd-container" 
        style=" background-image: url('<?php echo $interested_bkgd; ?>'); ">
      <div class="reno-interested-content-container">
        <div class="content-container">
          <h2 class="reno-h2"><?= $interested_heading; ?></h2>

          <?php if( !empty( $interested_content ) ) echo wpautop( $interested_content ); ?>

          <a class="green-button"
             href="mailto:<?= $interested_email; ?>"><?= __( 'Email the renovator', 'sassys' ); ?></a>
        </div>
      </div>
    </div>
  <?php 
    }
    
    if( $similar_projects -> have_posts() ) {
  ?>
    <div class="reno-similiar-projects-container">
      <div class="container">
        <div class="row">
          <div class="col">
            <h2 class="reno-h2"><?= __( 'Similar projects', 'sassys' ); ?></h2>
                  
            <div class="row">
              <?php
                while( $similar_projects -> have_posts() ) {
                  $similar_projects -> the_post();
              ?>
                <div class="col-lg-4 col-md-6">
                  <a href="<?= get_the_permalink(); ?>">
                    <div class="image-container">
                      <?php
                        if( has_post_thumbnail( get_the_ID() ) ) {
                          echo get_the_post_thumbnail( get_the_ID(), array( 377, 260 ) );
                        }
                      ?>
                    </div>

                    <div class="title-container">
                      <h4 class="similar-title"><?= get_the_title(); ?></h4>
                    </div>
                  </a>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php 
    }
    
    if( !empty( $project_ad ) ) {
      $advertisement = $project_ad;
    
      include( 'components/advertisement.php' ); 
    }  
  ?>
</div>

<?php
	get_footer();
