<?php
  global $wp_query;

  $page_query     = $wp_query -> query_vars;
  $page_for_posts = get_option( 'page_for_posts' );
  $page_id        = get_queried_object_id();
  $term           = get_term( $page_id, 'design-inspiration-cat' );
  $term_id        = get_term_meta( $term -> term_id, 'category-image-id', true );

  $page_title = get_the_title();
  $page_img   = get_the_post_thumbnail_url( get_the_ID(), 'full' );

  if( $page_for_posts == $page_id && !array_key_exists( 'design-inspiration-cat', $page_query ) ) {
    $page_title = get_the_title( $page_id );
    $page_img   = get_the_post_thumbnail_url( $page_id, 'full' );
  }

  else if( !is_null( $term ) && array_key_exists( 'design-inspiration-cat', $page_query ) ) {
    $page_title    = $term -> name;  
    $get_thumbnail = wp_get_attachment_image_src( $term_id, 'full' );
    $page_img      = $get_thumbnail[0];
  }
?>

<header class="reno-page-header">
  <div class="header-bkgd-img" style="background-image: url('<?= $page_img; ?>');">
    <div class="header-bkgd-gradient"></div>

    <div class="container">
      <div class="row">
        <div class="col">
          <div class="page-header-content">
            <?php include( 'header-logo.php' ); ?>

            <h1 class="reno-h1"><?php echo $page_title; ?></h1>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
