<?php

$theme = wp_get_theme();

function static_blog_page() {
  $blog_page_ID = intval( get_option( 'page_for_posts' ) );
  $post_id      = intval( $_GET[ 'post' ] );
  
  if( $blog_page_ID === $post_id ) return true;
  
  return false;
}

function wysiwyg_fix( $meta_key, $post_id = 0 ) {
  global $wp_embed;

  $post_id = $post_id ? $post_id : get_the_id();

  if( !empty( get_post_meta( $post_id, $meta_key, 1 ) ) ) $content = get_post_meta( $post_id, $meta_key, 1 );
  
  $content = $wp_embed -> autoembed( $content );
  $content = $wp_embed -> run_shortcode( $content );
  $content = wpautop( $content );
  $content = do_shortcode( $content );

  return $content;
}

// include( locate_template( 'lib/metaboxes/global-boxes.php' ) );
// include( locate_template( 'lib/metaboxes/cheat-sheet.php' ) );

include( locate_template( 'lib/metaboxes/page-our-menu.php' ) );
include( locate_template( 'lib/metaboxes/page-design-inspirations.php' ) );
include( locate_template( 'lib/metaboxes/page-find-renovator.php' ) );
include( locate_template( 'lib/metaboxes/page-front.php' ) );
include( locate_template( 'lib/metaboxes/page-home.php' ) );

include( locate_template( 'lib/metaboxes/post-home.php' ) );

include( locate_template( 'lib/metaboxes/type-our-menu.php' ) );
include( locate_template( 'lib/metaboxes/type-design-inspirations.php' ) );
include( locate_template( 'lib/metaboxes/type-find-renovator.php' ) );
include( locate_template( 'lib/metaboxes/page-contact.php' ) );
