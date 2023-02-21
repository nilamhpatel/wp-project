<?php
	/**
	 * Template Name: Find A Renovator template
	 */

	// Exit if accessed directly.
	defined( 'ABSPATH' ) || exit;

  $query_province    = $query_city    = $query_type    = $reno_contains = '';
  $operator_province = $operator_city = $operator_type = 'NOT_EXISTS';
  
  if( !empty( $_GET[ 'province' ] ) && isset( $_GET[ 'province' ] ) ) {
    $query_province    = $_GET[ 'province' ];
    $operator_province = 'IN';
  }

  if( !empty( $_GET[ 'city' ] ) && isset( $_GET[ 'city' ] ) ) {
    $query_city    = $_GET[ 'city' ];
    $operator_city = 'IN';
  }

  if( !empty( $_GET[ 'type' ] ) && isset( $_GET[ 'type' ] ) ) {
    $query_type    = $_GET[ 'type' ];
    $operator_type = 'IN';
  }

  if( isset( $_GET[ 'contains' ] ) && !empty( $_GET[ 'contains' ] ) ) {
    $reno_contains = $_GET[ 'contains' ];
  }

  $posts_per_page = 6;

  $far_posts = new WP_Query( array(
    'post_type'      => 'find-a-renovator',
    'orderby'        => 'rand',
    'posts_per_page' => $posts_per_page,
    's'              => $reno_contains,
    'tax_query' => array(
      array(
        'taxonomy' => 'province',
        'field'    => 'name',
        'terms'    => $query_province,  
        'operator' => $operator_province
      ),
      array(
        'taxonomy' => 'city',
        'field'    => 'name',
        'terms'    => $query_city,  
        'operator' => $operator_city
      ),
      array(
        'taxonomy' => 'contractor-type',
        'field'    => 'name',
        'terms'    => $query_type,
        'operator' => $operator_type
      ),
    ),
  ) );

  $far_count = $far_posts -> found_posts;
  $max_far_pages = $far_posts -> max_num_pages - 1;

  $renovator_ad = get_post_meta( get_the_ID(), 'renovator_shortcode_ad', true );

	get_header();  
?>

<div class="reno-find-a-renovator-page-container">
  <?php include( 'components/page-header.php' ); ?>

  <div style="margin-top: -100px; margin-bottom: 85px;">
    <div class="container" style="background: white; position: relative; margin-bottom: 20px;">
      <div class="row">
        <div class="col">
          <?php include( 'components/reno-search-form.php' ); ?>
        </div>
      </div>
    </div>

    <div class="container find-a-renovator-list-container">
      <?php 
        if( !empty( $query_province ) || !empty( $query_city ) ||
            !empty( $query_type ) || !empty( $reno_contains ) ) { 
      ?>
        <div class="search-results-container">
          <?php 
            if( !empty( $query_province ) || !empty( $query_city ) || !empty( $query_type ) ) { 
              $filterArray = [];

              if( !empty( $query_province ) ) $filterArray[] = strtoupper( $query_province );
              if( !empty( $query_city ) )     $filterArray[] = ucfirst( $query_city );
              if( !empty( $query_type ) )     $filterArray[] = ucfirst( $query_type );

              echo '<h2 class="search-title">' . __( 'Filter by:', 'sassys' ) . 
                   ' &ldquo;<span>' . implode( ', ', $filterArray ) . '</span>&rdquo;</h2>';
            }
            
            if( !empty( $reno_contains ) ) {
              echo '<h2 class="search-title">' . __( 'Search Results for:', 'sassys' ) .
                   ' &ldquo;<span>' . $reno_contains . '</span>&rdquo;</h2>'; 
            } 
            
            echo '<p class="results-count">' . __( 'Results:', 'sassys' ) . ' ' . $far_count; 
          ?>
        </div>
      <?php 
        }
         
        $exclude_posts = [];
        
        while( $far_posts -> have_posts() ) {
          $far_posts -> the_post();

          include( 'components/reno-card.php' ); 

          $exclude_posts[] = get_the_ID();
        }
      ?>
    </div>

    <?php if( $far_count > $posts_per_page ) { ?>
        <div class="load-more-container text-center">
          <a href="#" class="load-more-renovators" id="loadMoreRenovators"
            posts-per-page='<?= $posts_per_page; ?>'
            current-page='0'
            query-province="<?= $query_province; ?>"
            query-city="<?= $query_city; ?>"
            query-type="<?= $query_type; ?>"
            contains="<?= $reno_contains; ?>"
            exclude-posts=<?= json_encode( $exclude_posts ); ?>
            action="renovatorposts"
            max-pages='<?= $max_far_pages; ?>'><?php echo __( 'Load more', 'sassys' ); ?>
          </a>
        </div>   
      <?php  
        }
        
        if( !empty( $renovator_ad ) ) {
          $advertisement = $renovator_ad;
        
          include( 'components/advertisement.php' ); 
        }  
      ?>    
  </div>
</div>

<?php
	get_footer();
