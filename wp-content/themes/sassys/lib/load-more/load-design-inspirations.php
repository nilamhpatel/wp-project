<?php

function project_posts_loadmore_ajax_handler() {

  // prepare our arguments for the query
  $args            = [];
  $args[ 'paged' ] = $_POST[ 'page' ] + 1; // we need next page to be loaded
  $posts_per_page  = $_POST[ 'posts_per_page' ];
  $tax_id          = $_POST[ 'taxonomy' ];

  // First page is already displayed, start at page 2
  $paged = $args[ 'paged' ] + 1;

  $new_query = new WP_Query( array(
    'paged'          => $paged,
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

  if( $new_query -> have_posts() ) {
    while( $new_query -> have_posts() ) {
      $new_query -> the_post();

      include( get_theme_file_path( 'components/design-card.php' ) );
    }    
  }

  wp_reset_query();

  die; // here we exit the script and even no wp_reset_query() required!
}
add_action( 'wp_ajax_projectposts', 'project_posts_loadmore_ajax_handler' ); // wp_ajax_{action}
add_action( 'wp_ajax_nopriv_projectposts', 'project_posts_loadmore_ajax_handler' ); // wp_ajax_nopriv_{action}
