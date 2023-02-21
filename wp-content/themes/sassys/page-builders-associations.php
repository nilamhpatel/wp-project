<?php
	/**
	 * Template Name: our menus template
	 */

	// Exit if accessed directly.
	defined( 'ABSPATH' ) || exit;

  global $wp;

  $builders_page = home_url( $wp -> request );
  $builder_terms = get_terms( array(
    'taxonomy'       => 'menucategory',
    'posts_per_page' => -1,
  ) );

  $all_active       = 'active';
  $menucategory = '';
  $operator_builder = 'NOT_EXISTS';

  if( isset( $_GET[ 'menucategory' ] ) && !empty( $_GET[ 'menucategory' ] ) ) {
    $menucategory = $_GET[ 'menucategory' ];
    $operator_builder = 'IN';
    $all_active = '';
  }

  $posts_per_page = 12;
  $builder_posts  = new WP_Query( array(
    'post_type'      => 'our-menu',
    'posts_per_page' => $posts_per_page,
    'tax_query'      => array(
      array(
        'taxonomy' => 'menucategory',
        'field'    => 'slug',
        'terms'    => $menucategory,  
        'operator' => $operator_builder
      ),
    )
  ) );      

  $builder_posts_count = $builder_posts -> found_posts;      
  $max_builder_pages   = $builder_posts -> max_num_pages - 1;

  $builders_ad = get_post_meta( get_the_ID(), 'builders_shortcode_ad', true );

	get_header();  
?>

<div class="reno-our-menus-page-container">
  <?php include( 'components/page-header.php' ); ?>

  <div class="reno-builder-category-container">
    <div class="container">
      <div class="reno-builder-category-row row">
        <div class="col">
          <div class="reno-category-container">
            <ul>
              <li class="<?= $all_active; ?>">
                <a href="<?= $builders_page; ?>"><?= __( 'All', 'sassys' ); ?></a>
              </li>

              <?php 
                foreach( $builder_terms as $cat ) {
                  $name = $cat -> name;
                  $slug = $cat -> slug;
                  $active = '';

                  if( $menucategory == $slug ) $active = 'active';
              
                  echo '<li class="' . $active . '">';
                    echo '<a href="'. $builders_page . '?menucategory=' . $slug . '">' . $name . '</a>';
                  echo '</li>';
                }                
              ?>
            </ul>
          </div>
        </div>
      </div>

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 our-menu-list-container">
        <?php 
          if( $builder_posts -> have_posts() ) {  
            while( $builder_posts -> have_posts() ) {
              $builder_posts -> the_post();
            
              include( 'components/builders-card.php' );
            }
          } 
        ?>           
      </div>

      <?php if( $builder_posts_count > $posts_per_page ) { ?>
        <div class="load-more-container text-center">
          <a href="#" class="load-more-our-menu"
            posts-per-page='<?= $posts_per_page; ?>'
            query-location='<?= $menucategory; ?>'
            current-page='0'
            action="builderposts"
            max-pages='<?= $max_builder_pages; ?>'><?php echo __( 'Load more', 'sassys' ); ?>
          </a>
        </div>   
      <?php } ?>
    </div>
  </div>

  <?php
    if( !empty( $builders_ad ) ) {
      $advertisement = $builders_ad;
    
      include( 'components/advertisement.php' ); 
    }  
  ?>
</div>

<?php
	get_footer();
