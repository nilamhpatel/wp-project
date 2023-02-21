<?php

function design_inspiration_ad_metabox() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'design_inspiration_ad_metabox',
    'title'        => 'Ad Section',
    'object_types' => array( 'page' ),
    'show_on'      => array( 
      'key'   => 'page-template',
      'value' => 'page-design-inspiration.php'
    ),
    'context'      => 'normal',
    'priority'     => 'high',
    'show_names'   => true, // Show field names on the left
    'cmb_styles'   => true, // false to disable the CMB stylesheet
  ) );

  $cmb2 -> add_field( array(
    'name' => 'AdSanity Shortcode',
    'id'   => 'design_shortcode_ad',
    'desc' => 'Use the shortcode provided by the AdSanity plugin.',
    'type' => 'text',
  ) );
}
add_action( 'cmb2_admin_init', 'design_inspiration_ad_metabox' );
