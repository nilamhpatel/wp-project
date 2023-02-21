<?php

function page_home_metabox() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'page_home_metabox',
    'title'        => 'Global Partners Metabox',
    'object_types' => array( 'page' ), // Post type
    'show_on_cb'   => 'static_blog_page', // function should return a bool value
    'context'      => 'normal',
    'priority'     => 'high',
    'show_names'   => true, // Show field names on the left
    'cmb_styles'   => true, // false to disable the CMB stylesheet
  ) );

  $cmb2 -> add_field( array(
    'name' => 'AdSanity Shortcode',
    'id'   => 'home_shortcode_ad',
    'desc' => 'Use the shortcode provided by the AdSanity plugin.',
    'type' => 'text',
  ) );
}
add_action( 'cmb2_admin_init', 'page_home_metabox' );
