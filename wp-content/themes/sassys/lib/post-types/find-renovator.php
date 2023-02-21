<?php

function register_find_a_renovator_post_type() {
  $labels = array(
    'name'                  => 'Find A Renovator',
    'singular_name'         => 'Find A Renovator', 
    'menu_name'             => 'Find A Renovator',
    'name_admin_bar'        => 'Find A Renovator',
    'archives'              => 'Find A Renovator Archives',
    'attributes'            => 'Find A Renovator Attributes',
    'parent_item_colon'     => 'Parent Find A Renovator:',
    'all_items'             => 'All Find A Renovator',
    'add_new_item'          => 'Add New Find A Renovator',
    'add_new'               => 'Add New',
    'new_item'              => 'New Find A Renovator',
    'edit_item'             => 'Edit Find A Renovator',
    'update_item'           => 'Update Find A Renovator',
    'view_item'             => 'View Find A Renovator',
    'view_items'            => 'View Find A Renovator',
    'search_items'          => 'Search Find A Renovator',
    'not_found'             => 'Not found',
    'not_found_in_trash'    => 'Not found in Trash',
    'featured_image'        => 'Featured Image',
    'set_featured_image'    => 'Set featured image',
    'remove_featured_image' => 'Remove featured image',
    'use_featured_image'    => 'Use as featured image',
    'insert_into_item'      => 'Insert into item',
    'uploaded_to_this_item' => 'Uploaded to this Find A Renovator',
    'items_list'            => 'Find A Renovator list',
    'items_list_navigation' => 'Find A Renovator list navigation',
    'filter_items_list'     => 'Filter items list',
  );

  $rewrite = array( 
    'slug' => '/find-renovator', 
    'with_front' => false
  );

  $args = array(
    'label'                 => 'Find A Renovator',
    'description'           => 'sassys Find A Renovator',
    'labels'                => $labels,
    'supports'              => array( 'title', 'thumbnail', 'revisions' ),
		// 'taxonomies'            => array( 'category' ),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 20,
    'menu_icon'             => 'dashicons-hammer',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => true,
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'capability_type'       => 'page',
    'show_in_rest'          => true,
    'rewrite'               => $rewrite,
  );
  
  register_post_type( 'find-a-renovator', $args );
}
add_action( 'init', 'register_find_a_renovator_post_type', 0 );



function register_province_find_a_renovator_cat() {
  $labels = array(
      'name'              => 'Provinces',
      'singular_name'     => 'Province',
      'search_items'      => 'Search Provinces',
      'all_items'         => 'All Provinces',
      'parent_item'       => 'Parent Province',
      'parent_item_colon' => 'Parent Province:',
      'edit_item'         => 'Edit Province',
      'update_item'       => 'Update Province',
      'add_new_item'      => 'Add New Province',
      'new_item_name'     => 'New Province Name',
      'menu_name'         => 'Provinces',
  );

  $cat_rewrite = array( 
    'slug' => '/far-province',
    'with_front' => false
  );

  $args = array(
      'hierarchical'      => true,
      'public' => false,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'show_in_rest'      => true,
      'rewrite'           => $cat_rewrite,
  );

  register_taxonomy( 'province', array( 'find-a-renovator' ), $args );
}
add_action( 'init', 'register_province_find_a_renovator_cat', 0 );



function register_city_find_a_renovator_cat() {
  $labels = array(
      'name'              => 'Cities',
      'singular_name'     => 'City',
      'search_items'      => 'Search Cities',
      'all_items'         => 'All Cities',
      'parent_item'       => 'Parent City',
      'parent_item_colon' => 'Parent City:',
      'edit_item'         => 'Edit City',
      'update_item'       => 'Update City',
      'add_new_item'      => 'Add New City',
      'new_item_name'     => 'New City Name',
      'menu_name'         => 'Cities',
  );

  $cat_rewrite = array( 
    'slug' => '/far-city',
    'with_front' => false
  );

  $args = array(
      'hierarchical'      => true,
      'public' => false,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'show_in_rest'      => true,
      'rewrite'           => $cat_rewrite,
  );

  register_taxonomy( 'city', array( 'find-a-renovator' ), $args );
}
add_action( 'init', 'register_city_find_a_renovator_cat', 0 );



function register_contractor_type_find_a_renovator_cat() {
  $labels = array(
      'name'              => 'Contractor Types',
      'singular_name'     => 'Contrator Type',
      'search_items'      => 'Search Contractor Types',
      'all_items'         => 'All Contractor Types',
      'parent_item'       => 'Parent Contrator Type',
      'parent_item_colon' => 'Parent Contrator Type:',
      'edit_item'         => 'Edit Contrator Type',
      'update_item'       => 'Update Contrator Type',
      'add_new_item'      => 'Add New Contrator Type',
      'new_item_name'     => 'New Contrator Type Name',
      'menu_name'         => 'Contractor Types',
  );

  $cat_rewrite = array( 
    'slug' => '/far-contractor-type',
    'with_front' => false
  );

  $args = array(
      'hierarchical'      => true,
      'public' => false,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'show_in_rest'      => true,
      'rewrite'           => $cat_rewrite,
  );

  register_taxonomy( 'contractor-type', array( 'find-a-renovator' ), $args );
}
add_action( 'init', 'register_contractor_type_find_a_renovator_cat', 0 );
