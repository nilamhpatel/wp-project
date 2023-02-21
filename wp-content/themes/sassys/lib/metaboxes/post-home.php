<?php

function reno_blog_post_metabox() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'reno_blog_post_metabox',
    'title'        => 'Blog Metabox',
    'object_types' => array( 'post' ),
    'context'      => 'normal',
    'priority'     => 'high',
    'show_names'   => true, // Show field names on the left
    'cmb_styles'   => true, // false to disable the CMB stylesheet
  ) );

  $cmb2 -> add_field( array(
    'name' => 'AdSanity Shortcode',
    'id'   => 'blog_post_shortcode_ad',
    'desc' => 'Use the shortcode provided by the AdSanity plugin.',
    'type' => 'text',
  ) );
}
add_action( 'cmb2_admin_init', 'reno_blog_post_metabox' );
