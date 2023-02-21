<?php
function register_industry_reports_post_type() {
  /* Create custom post type */  
  $labels = array(
      'name'                  => 'Industry Reports',
      'singular_name'         => 'Industry Report', 
      'menu_name'             => 'Industry Reports',
      'name_admin_bar'        => 'Industry Reports',
      'archives'              => 'Industry Report Archives',
      'attributes'            => 'Industry Report Attributes',
      'parent_item_colon'     => 'Parent Industry Report:',
      'all_items'             => 'All Industry Reports',
      'add_new_item'          => 'Add New Industry Report',
      'add_new'               => 'Add New',
      'new_item'              => 'New Industry Report',
      'edit_item'             => 'Edit Industry Report',
      'update_item'           => 'Update Industry Report',
      'view_item'             => 'View Industry Report',
      'view_items'            => 'View Industry Report',
      'search_items'          => 'Search Industry Reports',
      'not_found'             => 'Not found',
      'not_found_in_trash'    => 'Not found in Trash',
      'featured_image'        => 'Featured Image',
      'set_featured_image'    => 'Set featured image',
      'remove_featured_image' => 'Remove featured image',
      'use_featured_image'    => 'Use as featured image',
      'insert_into_item'      => 'Insert into item',
      'uploaded_to_this_item' => 'Uploaded to this Industry Reports',
      'items_list'            => 'Industry Reports list',
      'items_list_navigation' => 'Industry Reports list navigation',
      'filter_items_list'     => 'Filter items list',
    );
  
    $rewrite = array( 
      'slug'       => '/industry-report/',
      'with_front' => false
    );
      
    $args = array(
      'label'                 => 'Industry Reports',
      'description'           => 'Industry Reports',
      'labels'                => $labels,
      'supports'              => array( 'title', 'thumbnail', 'revisions' ),
      'hierarchical'          => false,
      'public'                => false,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 23,
      'menu_icon'             => 'dashicons-media-document',
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => false,
      'can_export'            => true,
      'has_archive'           => false,
      'exclude_from_search'   => true,
      'publicly_queryable'    => true,
      'capability_type'       => 'page',
      'show_in_rest'          => true,
      'rewrite'               => $rewrite,
    );
    
    register_post_type( 'industry-report', $args );
  
    /* Create custom taxonomy/ category */
    register_taxonomy( 'industry_report_topic', [ 'industry-report' ], [
        $cat_rewrite = array( 
          'slug' => '/industry-report-topic/', 
          'with_front' => false,
        ),

        'label'             => 'Topics',
        'hierarchical'      => true,
        'rewrite'           => $cat_rewrite,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'labels'            => [
          'singular_name'              => 'Topic',
          'all_items'                  => 'All Topics',
          'edit_item'                  => 'Edit Topic',
          'view_item'                  => 'View Topic',
          'update_item'                => 'Update Topic',
          'add_new_item'               => 'Add New Topic',
          'new_item_name'              => 'New Topic Name',
          'search_items'               => 'Search Topics',
          'popular_items'              => 'Popular Topics',
          'separate_items_with_commas' => 'Separate topics with comma',
          'choose_from_most_used'      => 'Choose from most used topics',
          'not_found'                  => 'No topics found',
        ]
    ] );
  
    register_taxonomy( 'industry_report_author', [ 'industry-report' ], [
      'label'             => 'Authors',
          'hierarchical'      => true,
          'rewrite'           => [
        'slug' => '/industry-report-author/', 
        'with_front' => false
      ],
          'show_admin_column' => true,
          'show_in_rest'      => true,
          'labels'            => [
              'singular_name'              => 'Author',
              'all_items'                  => 'All Authors',
              'edit_item'                  => 'Edit Author',
              'view_item'                  => 'View Author',
              'update_item'                => 'Update Author',
              'add_new_item'               => 'Add New Author',
              'new_item_name'              => 'New Author Name',
              'search_items'               => 'Search Authors',
              'popular_items'              => 'Popular Authors',
              'separate_items_with_commas' => 'Separate authors with comma',
              'choose_from_most_used'      => 'Choose from most used authors',
              'not_found'                  => 'No authors found',
          ]
      ] );
}
add_action( 'init', 'register_industry_reports_post_type', 0 );

  
/* Create metaboxes for other information */
function industry_report_post_type_metaboxes() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'industry_report_post_type_metaboxes',
    'title'        => 'Industry Report Info',
    'object_types' => array( 'industry-report' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
    'cmb_styles' => true, // false to disable the CMB stylesheet
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Report File',
    'desc' => 'Required',
    'id'   => 'industry_report_url',
    'type' => 'file',
    'options' => array( 'url' => true, ),
    'text' => array( 'add_upload_file_text' => 'Add PDF' ),
    'query_args' => array( 'type' => 'application/pdf', ),
    'attributes'  => array( 'required'    => 'required', ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Published Date',
    'id'   => 'industry_report_published_date',
    'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'type' => 'text_date',
    'date_format' => 'Y/m/d',
    'attributes'  => array(
      'required'    => 'required',
    ),
  ) );
}
add_action( 'cmb2_admin_init', 'industry_report_post_type_metaboxes' );



/* Load more button ajax search  */
function pdf_posts_loadmore_ajax_handler() {
  // prepare our arguments for the query
  $args            = [];
  $args[ 'paged' ] = $_POST[ 'page' ] + 1; // we need next page to be loaded
  $post_type       = $_POST[ 'post_type' ];
  $posts_per_page  = $_POST[ 'posts_per_page' ];
  $query_topic     = $_POST[ 'q_topic' ];
  $query_author    = $_POST[ 'q_author' ];
  $query_year      = $_POST[ 'q_year' ];
  $operator_topic  = $_POST[ 'o_topic' ];
  $operator_author = $_POST[ 'o_author' ];  
  $operator_year   = $_POST[ 'o_year' ];

  // First page is already displayed, start at page 2
  $paged = $args[ 'paged' ] + 1;

  $new_query = new WP_Query( array(
    'paged'          => $paged,
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
  ) );

  // var_dumP( $new_query );

  if( $new_query -> have_posts() ) {

    while( $new_query -> have_posts() ) {
      $new_query -> the_post();
     
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

  wp_reset_query();
  die; // here we exit the script and even no wp_reset_query() required!
}

add_action( 'wp_ajax_pdfposts', 'pdf_posts_loadmore_ajax_handler' ); // wp_ajax_{action}
add_action( 'wp_ajax_nopriv_pdfposts', 'pdf_posts_loadmore_ajax_handler' ); // wp_ajax_nopriv_{action}