<?php
  /**
   * Blog Post
   */

  // Exit if accessed directly.
  defined( 'ABSPATH' ) || exit;

  $posts_per_page = 3;
  $cat            = get_the_category();
  $page_for_posts = get_option( 'page_for_posts' );

  $blog_posts = get_posts( array(
    'post_type'      => 'post',
    'post__not_in'   => array( $post -> ID ),
    'category'       => $cat[0] -> term_id,
    'posts_per_page' => $posts_per_page,
  ) );

  $bkgd_header = '';

  if( has_post_thumbnail( $page_for_posts ) ) {
    $bkgd_header = 'background-image: url(' . get_the_post_thumbnail_url( $page_for_posts ) . ');';
  }

  $cat_label    = get_the_category();
  $blog_post_ad = get_post_meta( get_the_ID(), 'blog_post_shortcode_ad', true );

  get_header();  
?>

<div class="reno-blog-post-container">
  <header class="header-container" style="<?= $bkgd_header ?>">
    <?php include( 'components/header-logo.php' ); ?>
  </header>
  
  <div>
    <?php include( 'components/social-sharing.php' ); ?>

    <div class="container blog-post-main-container" id="content" tabindex="-1">    
      <div class="row">
        <main class="col blog-post-main-col">
          <?php
            while( have_posts() ) {
              the_post();
          ?>       
            <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
              <header class="entry-header">
                <h1 class="entry-title"><?php echo get_the_title(); ?></h1>

                <span class="entry-cat"><?= $cat_label[0] -> name; ?></span>
              </header>

              <div class="entry-content">
                <?php 
                  echo get_the_post_thumbnail( 
                    $post -> ID, array( 470, 470 ), array( 'class' => 'reno-featured-image' ) 
                  );
                ?>

                <div class="content">
                  <?php the_content(); ?>
                </div>
              </div>
            </article>
          <?php } ?>
        </main>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="border-container">
        <hr />
            
        <img src="<?php echo get_stylesheet_directory_uri() . '/images/logo-green-check.svg'; ?>"
             alt="" width="168" height="150" />
      </div>
      
      <div class="related-news-container">
        <h2 class="reno-h2"><?= __( 'related news', 'sassys' ); ?></h2>

        <div class="reno-row-adjustment row row-cols-1 row-cols-sm-2 row-cols-md-3">
          <?php foreach( $blog_posts as $post ) include( 'components/blog-card.php' ); ?>
        </div>            
      </div>
    </div>
  </div>

  <?php
    if( !empty( $blog_post_ad ) ) {
      $advertisement = $blog_post_ad;
      
      include( 'components/advertisement.php' ); 
    }  
  ?>
</div>

<?php
					
	get_footer();
