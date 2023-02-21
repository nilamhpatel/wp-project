<?php

function project_post_type_metaboxes() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'project_post_type_metaboxes',
    'title'        => 'Project Info',
    'object_types' => array( 'design-inspiration' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
    'cmb_styles' => true, // false to disable the CMB stylesheet
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Member',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'project_member',
    'type' => 'text',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'City',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'project_city',
    'type' => 'text',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Year',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'project_year',
    'type' => 'text_small',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Duration',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'project_duration',
    'type' => 'text_medium',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Budget',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'project_budget',
    'type' => 'text',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Awards',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'project_awards',
    'type' => 'textarea_small',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Logo',
    'id'   => 'project_logo',
    'type' => 'file',
    'query_args' => array(
      'type' => array( 'image/gif', 'image/jpeg', 'image/png', ),
    ),
    'preview_size' => 'thumbnail',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Before & After Shortcode',
    'desc' => 'Use the shortcode provided by the Before and After Slider.',
    'id'   => 'project_before_after',
    'type' => 'text_medium',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Modula Image Gallery Shortcode',
    'desc' => 'Use the shortcode provided by the Modula plugin.',
    'id'   => 'project_image_gallery',
    'type' => 'text_medium',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );
}
add_action( 'cmb2_admin_init', 'project_post_type_metaboxes' );



function intereted_project_post_type_metaboxes() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'intereted_project_post_type_metaboxes',
    'title'        => 'Interested Section',
    'object_types' => array( 'design-inspiration' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
    'cmb_styles' => true, // false to disable the CMB stylesheet
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Background Image',
    'id'   => 'interested_bkgd',
    'type' => 'file',
    'query_args' => array(
      'type' => array( 'image/gif', 'image/jpeg', 'image/png', ),
    ),
    'preview_size' => 'thumbnail',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Heading',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'interested_heading',
    'type' => 'text',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Content',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'interested_content',
    'type' => 'textarea_small',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Email',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'interested_email',
    'type' => 'text_email',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );
}
add_action( 'cmb2_admin_init', 'intereted_project_post_type_metaboxes' );



function ad_project_post_type_metaboxes() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'ad_project_post_type_metaboxes',
    'title'        => 'AdSanity Section',
    'object_types' => array( 'design-inspiration' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
    'cmb_styles' => true, // false to disable the CMB stylesheet
  ) );

  $cmb2 -> add_field( array(
    'name' => 'AdSanity Shortcode',
    'id'   => 'project_shortcode_ad',
    'desc' => 'Use the shortcode provided by the AdSanity plugin.',
    'type' => 'text',
  ) );
}
add_action( 'cmb2_admin_init', 'ad_project_post_type_metaboxes' );