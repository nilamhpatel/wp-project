<?php
  $blog_title = $blog_id = '';

  if( is_front_page() || is_single() ) {
    $blog_id    = $post -> id; 
    $blog_title = $post -> post_title;    
  }

  else {
    $blog_id    = get_the_ID();
    $blog_title = get_the_title();
  }

  $blog_cat = get_the_category( $blog_id )[0] -> name;
?>

<div class="col reno-blog-card-container">
  <a class="card h-100" href="<?php echo get_the_permalink( $blog_id ); ?>">
    <?php
      if( has_post_thumbnail( $blog_id ) ) {
        echo '<div class="featured-image-container">';
          echo get_the_post_thumbnail( $blog_id , array( 452, 452 ) );
        echo '</div>';
      }
            
      else {
        echo '<div class="default-image-container">';
          echo '<img src="' . get_stylesheet_directory_uri() .
               '/images/logo-green-check.svg" alt="" width="168" height="150" />';
        echo '</div>';
      } 
    ?>
    
    <div class="content-container">
      <span class="category"><?= $blog_cat; ?></span>
      <h3 class="title"><?= $blog_title; ?></h3>
    </div>
  </a>
</div>
