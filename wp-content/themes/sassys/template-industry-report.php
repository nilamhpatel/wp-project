<?php
/**
 * Template Name: Industry Report Template
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

  $query_topic    = $query_author    = $query_year = '';
  $operator_topic = $operator_author = 'NOT_EXISTS';
  $operator_year  = 'NOT IN';
  
  if( !empty( $_GET[ 'topic' ] ) && isset( $_GET[ 'topic' ] ) ) {
    $query_topic    = $_GET[ 'topic' ];
    $operator_topic = 'IN';
  }

  if( !empty( $_GET[ 'author' ] ) && isset( $_GET[ 'author' ] ) ) {
    $query_author    = $_GET[ 'author' ];
    $operator_author = 'IN';
  }

  if( !empty( $_GET[ 'search_year' ] ) && isset( $_GET[ 'search_year' ] ) ) {
    $query_year   = $_GET[ 'search_year' ];
    $operator_year = 'LIKE';
  }

  $post_type      = 'industry-report';
  $posts_per_page = 12;

  $pdf_args = array( 
    'post_type'      => $post_type,
    'posts_per_page' => $posts_per_page,
    'tax_query' => array(
      array(
        'taxonomy' => 'industry_report_topic',
        'field'    => 'name',
        'terms'    => $query_topic,  
        'operator' => $operator_topic
      ),
      array(
        'taxonomy' => 'industry_report_author',
        'field'    => 'name',
        'terms'    => $query_author,
        'operator' => $operator_author
      ),
    ),
    'meta_query' => array(
      array(
        'key'     => 'industry_report_published_date',
        'value'   => $query_year,
        'compare' => $operator_year,
      ),
    ),
  );

  // Query for default page setup
  $pdf_posts = new WP_Query( $pdf_args );

  // Query for search filter
  $all_posts = get_posts( array(
    'post_type'   => $post_type,
    'numberposts' => -1
  ) );

  $max_num_pdf_pages = $pdf_posts -> max_num_pages - 1;

	get_header();

?>

<div class="bild-pdf-page-container">
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-10">
        <div class="intro-content-container">
          <?php
            while( have_posts() ) {
              the_post();
              the_content();
            }
          ?>
        </div>
      </div>
    </div>
  </div>

  <div id="search-reports-anchor" class="container-fluid search-card-container">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col">
          <?php
            echo '<h2>' . __( 'Search Reports', 'wpgta' ) . '</h2>'; 
            
            $search_year = $search_topic = $search_author = array();           

            foreach( $all_posts as $post ) {
              $topic_terms  = get_the_terms( $post -> ID, 'industry_report_topic' );
              $author_terms = get_the_terms( $post -> ID, 'industry_report_author' );

              if( $topic_terms ) {
                foreach( $topic_terms as $topic ) {
                  $search_topic[] =  $topic -> name;
                } 
              }

              if( $author_terms ) {
                foreach( $author_terms as $author ) {
                  $search_author[] =  $author -> name;
                } 
              }
              
              $post_year = get_post_meta( $post -> ID, 'industry_report_published_date' );
              $post_year = substr( $post_year[0], 0, 4 );

              $search_year[] = strval( $post_year );
            }

            $search_year   = array_unique( array_filter( $search_year, 'strlen' ) );
            $search_topic  = array_unique( $search_topic );
            $search_author = array_unique( $search_author );            

            sort( $search_year );
            sort( $search_topic );
            sort( $search_author );
          ?>

          <form class="search-pdf-form-container" action="#search-reports-anchor">
            <div class="search-field-container ginput_container_select">
              <label for="topic"><?= __( 'Topic', 'wpgta' ); ?></label>

              <select id="topic" name="topic">
                <option value=""><?= __( 'All', 'wpgta' ); ?></option>
                  
                <?php
                  foreach( $search_topic as $st ) {
                    $selected = '';
                    
                    if( $query_topic === $st ) $selected = 'selected';

                    echo '<option value="' . $st . '" ' . $selected . '>' . $st . '</option>';
                  }
                ?>
              </select>
            </div>

            <div class="search-field-container ginput_container_select">
              <label for="search_year"><?= __( 'Year', 'wpgta' ); ?></label>
              
              <select id="search_year" name="search_year">
                <option value=""><?= __( 'All', 'wpgta' ); ?></option>
                
                <?php 
                  foreach( $search_year as $sy ) {
                    $selected = '';
                    
                    if( $query_year === $sy ) $selected = 'selected';

                    echo '<option value="' . $sy . '" ' .  $selected . '>' . $sy . '</option>';
                  }
                ?>
              </select>
            </div>

            <div class="search-field-container ginput_container_select">
              <label for="author"><?= __( 'Author', 'wpgta' ); ?></label>

              <select id="author" name="author">           
                <option value=""><?= __( 'All', 'wpgta' ); ?></option>           
                  
                <?php                
                  foreach( $search_author as $sa ) {
                    $selected = '';
                    
                    if( $query_author === $sa ) $selected = 'selected';

                    echo '<option value="' . $sa . '" ' . $selected . '>' . $sa . '</option>';
                  }
                ?>
              </select>
            </div>

            <input type="submit" value="<?= __( 'search', 'wpgta' ); ?>" />
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="container pdf-post-list-container">
    <div class="row pdf-post-list">
      <?php
        if( $pdf_posts -> have_posts() ) {          
          while( $pdf_posts -> have_posts() ) {
            $pdf_posts -> the_post();

            if( $post_type === 'industry-report' ) {
              $pdf_url  = get_post_meta( get_the_ID(), 'industry_report_url', true );
              $pdf_date = get_post_meta( get_the_ID(), 'industry_report_published_date', true );
              $pdf_cat  = 'report';
            }
          
            if( $post_type === 'advocacy' ) {
              $pdf_url  = get_post_meta( get_the_ID(), 'advocacy_url', true );
              $pdf_date = get_post_meta( get_the_ID(), 'advocacy_published_date', true );
              $pdf_cat  = $post_type;
            }
          
            $pub_date = date( "F d, Y", strtotime( $pdf_date ) );
          
            echo '<div class="col-md-6 col-lg-4 max-card-width">';
              echo '<a href="' . $pdf_url . '" target="_blank">';
                if( has_post_thumbnail( get_the_ID() ) ) {
                  echo '<div class="featured-image-container">';
                    echo get_the_post_thumbnail( get_the_ID(), array( 560, 560 ) );
                  echo '</div>';
                }
          
                else {
                  echo '<div class="default-image-container">';
                    echo '<img src="' . get_stylesheet_directory_uri() .
                        '/images/default-image-logo-lg.png" alt="" width="92" height="99" />';
                  echo '</div>';
                } 
          
                echo '<div class="info-container ' . $pdf_cat . '">';
                  echo '<span class="type">' . $pdf_cat . '</span>';
                  echo '<h3 class="title">' . get_the_title() . '</h3>';
                  echo '<span class="date">' . $pub_date . '</span>';
                echo '</div>';
              echo '</a>';
            echo '</div>';
            

          }
        }

        else {
          echo '<p class="m-0">No matches found. Please try another search.</p>';
        }
      ?>
    </div>

    <?php if( $max_num_pdf_pages > 0 ) { ?>
      <div class="load-more-container text-center">
        <a href="#" class="load-more-pdf-cards"
          posts-per-page='<?= $posts_per_page; ?>'
          post-type='<?= $post_type; ?>'
          current-page='0'
          action="pdfposts"
          q-topic="<?= $query_topic; ?>" q-author="<?= $query_author; ?>" q-year="<?= $query_year; ?>" 
          o-topic="<?= $operator_topic; ?>" o-author="<?= $operator_author; ?>" 
          o-year="<?= $operator_year; ?>"        
          max-pages='<?= $max_num_pdf_pages; ?>'>Load more —
        </a>
      </div> 
    <?php } ?>
  </div>
</div>


<?php
wp_enqueue_script( 'script', get_stylesheet_directory_uri() . '/js/load-more.js', array( 'jquery' ), 1.1, true);

get_footer();
