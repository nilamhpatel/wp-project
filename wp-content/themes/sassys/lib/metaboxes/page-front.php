<?php

function front_page_hero_metabox() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'front_page_hero_metabox',
    'title'        => 'Hero Section',
    'object_types' => array( 'page' ),
    'show_on'      => array( 
      'key'   => 'page-template',
      'value' => 'page-front.php'
    ),
    'context'      => 'normal',
    'priority'     => 'high',
    'show_names'   => true, // Show field names on the left
    'cmb_styles'   => true, // false to disable the CMB stylesheet
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Background Image',
    'id'   => 'hero_bkgd_img',
    'type' => 'file',
    'query_args' => array(
      'type' => array( 'image/gif', 'image/jpeg', 'image/png', ),
    ),
    'preview_size' => 'thumbnail',
  ) );

  $cmb2 -> add_field( array(
    'name'    => 'Text',
    'id'      => 'hero_text',
    'type'    => 'text',
    'default' => __( 'Renovate with Confidence', 'sassys' ),
  ) );
  
  $cmb2 -> add_field( array(
    'name' => 'YouTube Video URL TODO: functionality',
    'id'   => 'hero_video_url',
    'desc' => 'ex. https://www.youtube.com/watch?v=8xCjbO75loE',
    'type' => 'oembed',
  ) );
}
add_action( 'cmb2_admin_init', 'front_page_hero_metabox' );



function front_page_carousel_metabox() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'front_page_carousel_metabox',
    'title'        => 'Carousel Section',
    'object_types' => array( 'page' ),
    'show_on'      => array( 
      'key'   => 'page-template',
      'value' => 'page-front.php'
    ),
    'context'      => 'normal',
    'priority'     => 'high',
    'show_names'   => true, // Show field names on the left
    'cmb_styles'   => true, // false to disable the CMB stylesheet
  ) );

  $carousel_id = $cmb2 -> add_field( array(
    'id'          => 'carousel_group',
    'type'        => 'group',
    'options'     => array(
      'group_title'   => 'Slide {#}',
      'add_button'    => 'Add Another Slide',
      'remove_button' => 'Remove Slide',
      'sortable'      => true
    ),
  ) );
  
  $cmb2 -> add_group_field( $carousel_id, array(
    'name' => 'Background Image',
    'id'   => 'carousel_image',
    'type' => 'file',
    'query_args' => array(
      'type' => array( 'image/gif', 'image/jpeg', 'image/png', ),
    ),
    'preview_size' => 'thumbnail',
  ) );

  $cmb2 -> add_group_field( $carousel_id, array(
    'name' => 'Quote',
    'id'   => 'carousel_quote',
    'type' => 'textarea_small',
  ) );

  $cmb2 -> add_group_field( $carousel_id, array(
    'name' => 'Name',
    'id'   => 'carousel_name',
    'type' => 'text_medium',
  ) );

  $cmb2 -> add_group_field( $carousel_id, array(
    'name' => 'Location',
    'id'   => 'carousel_location',
    'type' => 'text',
  ) );
}
add_action( 'cmb2_admin_init', 'front_page_carousel_metabox' );



function front_page_news_metabox() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'front_page_news_metabox',
    'title'        => 'News Section',
    'object_types' => array( 'page' ),
    'show_on'      => array( 
      'key'   => 'page-template',
      'value' => 'page-front.php'
    ),
    'context'      => 'normal',
    'priority'     => 'high',
    'show_names'   => true, // Show field names on the left
    'cmb_styles'   => true, // false to disable the CMB stylesheet
  ) );

  $cmb2 -> add_field( array(
    'name' => 'AdSanity Shortcode',
    'id'   => 'news_shortcode_ad',
    'desc' => 'Use the shortcode provided by the AdSanity plugin.',
    'type' => 'text',
  ) );
  
  $cmb2 -> add_field( array(
    'name' => 'News Heading',
    'id'   => 'news_heading',
    'type' => 'text',
    'default' => __( 'Latest Renovation News', 'sassys' ),
  ) );
}
add_action( 'cmb2_admin_init', 'front_page_news_metabox' );



function front_page_faq_metabox() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'front_page_faq_metabox',
    'title'        => 'FAQ Section',
    'object_types' => array( 'page' ),
    'show_on'      => array( 
      'key'   => 'page-template',
      'value' => 'page-front.php'
    ),
    'context'      => 'normal',
    'priority'     => 'high',
    'show_names'   => true, // Show field names on the left
    'cmb_styles'   => true, // false to disable the CMB stylesheet
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Message',
    'id'   => 'faq_message',
    'type' => 'textarea',
    'default' => __( 'We\'re here to help you make informed decisions about the renovation process and who you hire for your project. sassys recognizes members of the Canadian Home Builders\' Association who abide by the sassys Code of Conduct, so that you can have confidence in your renovation professional.', 'sassys' ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Heading',
    'id'   => 'faq_heading',
    'type' => 'text',
    'default' => __( 'FAQ', 'sassys' ),
  ) );

  $faq_group_id = $cmb2 -> add_field( array(
    'id'          => 'faq_group',
    'desc'        => 'FAQ Accordion',
    'type'        => 'group',
    'options'     => array(
      'group_title'   => 'FAQ {#}',
      'add_button'    => 'Add FAQ',
		  'remove_button' => 'Remove FAQ',
      'sortable'      => true
    ),
  ) );

  $cmb2 -> add_group_field( $faq_group_id, array(
    'name' => 'Question',
    'id'   => 'faq_question',
    'type' => 'text',
  ) );

  $cmb2 -> add_group_field( $faq_group_id, array(
    'name' => 'Answer',
    'id'   => 'faq_answer',
    'type' => 'textarea',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'AdSanity Shortcode',
    'id'   => 'faq_shortcode_ad',
    'desc' => 'Use the shortcode provided by the AdSanity plugin.',
    'type' => 'text',
  ) );
}
add_action( 'cmb2_admin_init', 'front_page_faq_metabox' );
