<?php

function register_our_menus_post_type() {
  $labels = array(
    'name'                  => 'Our Menus',
    'singular_name'         => 'Our Menus', 
    'menu_name'             => 'Our Menus',
    'name_admin_bar'        => 'Our Menus',
    'archives'              => 'Our Menus Archives',
    'attributes'            => 'Our Menus Attributes',
    'parent_item_colon'     => 'Parent Our Menus:',
    'all_items'             => 'All Our Menus',
    'add_new_item'          => 'Add New Our Menus',
    'add_new'               => 'Add New',
    'new_item'              => 'New Our Menus',
    'edit_item'             => 'Edit Our Menus',
    'update_item'           => 'Update Our Menus',
    'view_item'             => 'View Our Menus',
    'view_items'            => 'View Our Menus',
    'search_items'          => 'Search Our Menus',
    'not_found'             => 'Not found',
    'not_found_in_trash'    => 'Not found in Trash',
    'featured_image'        => 'Featured Image',
    'set_featured_image'    => 'Set featured image',
    'remove_featured_image' => 'Remove featured image',
    'use_featured_image'    => 'Use as featured image',
    'insert_into_item'      => 'Insert into item',
    'uploaded_to_this_item' => 'Uploaded to this Our Menus',
    'items_list'            => 'Our Menus list',
    'items_list_navigation' => 'Our Menus list navigation',
    'filter_items_list'     => 'Filter items list',
  );

  $rewrite = array( 
    'slug' => '/our-menu', 
    'with_front' => true
  );

  $args = array(
    'label'                 => 'our-menu',
    'description'           => 'Sassys Menus',
    'labels'                => $labels,
    'supports'              => array( 'title', 'thumbnail', 'revisions' ),
		// 'taxonomies'            => array( 'category' ),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 20,
    'menu_icon'             => 'dashicons-building',
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
  
  register_post_type( 'our-menu', $args );
}
add_action( 'init', 'register_our_menus_post_type', 0 );



function register_our_menus_menucategory() {
  $labels = array(
      'name'              => 'Category',
      'singular_name'     => 'Category',
      'search_items'      => 'Search Category',
      'all_items'         => 'All Category',
      'parent_item'       => 'Parent Category',
      'parent_item_colon' => 'Parent Category:',
      'edit_item'         => 'Edit Category',
      'update_item'       => 'Update Category',
      'add_new_item'      => 'Add New Category',
      'new_item_name'     => 'New Category Name',
      'menu_name'         => 'Categories',
  );

  $cat_rewrite = array( 
    'slug' => '/our-menu/menucategory', 
    'with_front' => true
  );

  $args = array(
      'hierarchical'      => true,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'show_in_rest'      => true,
      'rewrite'           => $cat_rewrite,
  );

  register_taxonomy( 'menucategory', array( 'our-menu' ), $args );
}
add_action( 'init', 'register_our_menus_menucategory', 0 );
