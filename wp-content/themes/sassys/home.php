<?php
	/**
	 * Blog Template
	 */

	// Exit if accessed directly.
	defined( 'ABSPATH' ) || exit;

  $posts_per_page = 12;
  $post_category  = $post_contains = '';

  if( isset( $_GET[ 'category' ] ) && !empty( $_GET[ 'category' ] ) ) {
    $post_category  = $_GET[ 'category' ];
  }

  if( isset( $_GET[ 'contains' ] ) && !empty( $_GET[ 'contains' ] ) ) {
    $post_contains  = $_GET[ 'contains' ];
  }

  $blog_posts = new WP_Query( array(
    'post_type'      => 'post',
    'posts_per_page' => $posts_per_page,
    'category_name'  => $post_category ,
    's'              => $post_contains
  ) );

  $blog_posts_count    = $blog_posts -> found_posts;
  $max_num_pages = $blog_posts -> max_num_pages - 1;

  $cat_list = get_categories();
  $home_ad  = get_post_meta( intval( get_option( 'page_for_posts' ) ), 'home_shortcode_ad', true );

	get_header();
?>

<div class="reno-blog-page-container">
  <?php include( 'components/page-header.php' ); ?>

  <div class="blog-page-outer-container">
    <div class="container blog-page-main-container">
      <div class="row justify-content-md-center">
        <div class="col">
          <form id="my-form" class="search-blog-form-container" action="#search-blog-anchor">
            <div class="search-field-container ginput_container_select">
              <label for="Category"><?= __( 'Category', 'sassys' ); ?></label>

              <select id="category" name="category">
                <option value=""><?= __( 'Category', 'sassys' ); ?></option>            

                <?php
                  foreach( $cat_list as $cat ) {
                    $value    = $cat -> slug;
                    $label    = $cat -> name;
                    $selected = '';

                    if( $post_category == $value ) $selected = 'selected';

                    echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
                  }                    
                ?>
              </select>
            </div>

            <div class="input-field-container gfield text-field">
              <label for="name"><?= __( 'containing text...', 'sassys' ); ?></label>

              <input placeholder="containing text..." id="name" value="<?= $post_contains; ?>" 
                     type="text" name="contains" class="" />
            </div>

            <div class="button-container">
              <input type="submit" value="<?= __( 'filter', 'sassys' ); ?>" />

              <input type="reset" value="<?= __( 'reset', 'sassys' ); ?>" />
            </div>
          </form>
        </div>
      </div>
  
      <?php if( $blog_posts -> have_posts() ) { ?>
        <div class="blog-list-container row row-cols-1 row-cols-sm-2 row-cols-md-3">
          <?php
            while( $blog_posts -> have_posts() ) {
              $blog_posts -> the_post();
          
              include( 'components/blog-card.php' );             
            }
          ?>
        </div>

        <?php if( $blog_posts_count > $posts_per_page ) { ?>
          <div class="load-more-container text-center">
            <a href="#" class="load-more-blogs"
              posts-per-page='<?= $posts_per_page; ?>'
              category='<?= $post_category; ?>'
              contains='<?= $post_contains; ?>'
              current-page='0'
              action="blogposts"
              max-pages='<?= $max_num_pages; ?>'><?php echo __( 'Load more', 'sassys' ); ?>
            </a>
          </div>   
        <?php 
          }
        } 
          
        if( !empty( $home_ad ) ) {
          $advertisement = $home_ad;
        
          include( 'components/advertisement.php' ); 
        }  
      ?>
    </div>
  </div>
</div>
<style>
  .content-container {
    padding: 20px;
}
.default-image-container {
    text-align: center;
    margin: 0 auto;
    float: none;
}
</style>
<?php
	get_footer();
