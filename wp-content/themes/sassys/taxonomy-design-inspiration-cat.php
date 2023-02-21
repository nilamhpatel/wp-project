<?php
	/**
	 * Taxonomy: Design Inspiration
	 */

	// Exit if accessed directly.
	defined( 'ABSPATH' ) || exit;

  $tax_id    = get_queried_object_id();
  $term      = get_term( $tax_id, 'design-inspiration-cat' );
  $page_slug = get_site_url() . '/design-inspiration/';

  $posts_per_page = 6;

  $project_list = new WP_Query( array(
    'post_type'      => 'design-inspiration',
    'posts_per_page' => $posts_per_page,
    'tax_query'      => array(
      array(
        'taxonomy' => 'design-inspiration-cat',
        'field'    => 'term_id',
        'terms'    => array( $tax_id )
      )
    )
  ) );

  $project_count = $project_list -> found_posts;
  $max_pages     = $project_list -> max_num_pages - 1;

  $design_inspiration_ad = sassys_theme_site_option( 'design_inspiration_shortcode_ad', false );

	get_header();  
?>

<div class="reno-design-inspiration-tax-container">
  <?php include( 'components/page-header.php' ); ?>

  <div class="reno-design-inspiration-list-container">
    <div class="container">
      <div class="row reno-back-to-parent-container">
        <a href="<?= $page_slug; ?>">
          <i class="fa-solid fa-chevron-left"></i> 
          
          <?= __( 'Back to Design Inspiration', 'sassys' ); ?>
        </a>
      </div>
      
      <?php if( $project_list -> have_posts() ) { ?>
        <div class="card-list-container">
          <?php
            while( $project_list -> have_posts() ) {
              $project_list -> the_post();

              include( 'components/design-card.php' );
            }
          ?>
        </div>
          
        <?php if( $project_count > $posts_per_page ) { ?>
          <div class="load-more-container text-center">
            <a href="#" class="load-more-projects"
              posts-per-page='<?= $posts_per_page; ?>'
              tax-id='<?= $tax_id; ?>'
              current-page='0'
              action="projectposts"
              max-pages='<?= $max_pages; ?>'><?php echo __( 'Load more', 'sassys' ); ?>
            </a>
          </div>
        <?php 
          }
        }
                
        if( !empty( $design_inspiration_ad ) ) {
          $advertisement = $design_inspiration_ad;
        
          include( 'components/advertisement.php' ); 
        }  
      ?>
    </div>
  </div>
</div>

<?php
	get_footer();
