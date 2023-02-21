<?php

function find_a_renovator_posts_loadmore_ajax_handler() {

  /**
   * Because we want to orderby randomly, we don't want to update
   * the pagination, we do need to keep track of posts to exclude 
   * to prevent duplication.
   */

  // prepare our arguments for the query
  $posts_per_page  = $_POST[ 'posts_per_page' ];
  $post_contains   = $_POST[ 'contains' ];
  $query_province  = $_POST[ 'query_province' ];
  $query_city      = $_POST[ 'query_city' ];
  $query_type      = $_POST[ 'query_type' ];
  $exclude_posts   = json_decode( $_POST[ 'excluded_ids' ] );

  if( !empty( $query_province ) ) $operator_province = 'IN';
  if( !empty( $query_city ) )     $operator_city = 'IN';
  if( !empty( $query_type ) )     $operator_type = 'IN';

  $new_query = new WP_Query( array(
    'post_type'      => 'find-a-renovator',
    'orderby'        => 'rand',
    'post__not_in'   => $exclude_posts,
    'posts_per_page' => $posts_per_page,
    's'              => $post_contains,
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

  if( $new_query -> have_posts() ) {
    while( $new_query -> have_posts() ) {
      $new_query -> the_post();

      include( get_theme_file_path( 'components/reno-card.php' ) );

      $exclude_posts[] = get_the_ID();
    }
    
    echo '<div id="renoExclusionArray">' . json_encode( $exclude_posts ) . '</div>';
  }

  wp_reset_query();

  die; // here we exit the script and even no wp_reset_query() required!
}
add_action( 'wp_ajax_renovatorposts', 'find_a_renovator_posts_loadmore_ajax_handler' ); // wp_ajax_{action}
add_action( 'wp_ajax_nopriv_renovatorposts', 'find_a_renovator_posts_loadmore_ajax_handler' ); // wp_ajax_nopriv_{action}
