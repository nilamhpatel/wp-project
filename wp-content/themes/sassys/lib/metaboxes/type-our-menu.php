<?php

function our_menu_post_type_metaboxes() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'our_menu_post_type_metaboxes',
    'title'        => 'Builders Association Info',
    'object_types' => array( 'our-menu' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
    'cmb_styles' => true, // false to disable the CMB stylesheet
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Address',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'our_menu_street',
    'type' => 'textarea_small',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );
    
  $cmb2 -> add_field( array(
    'name' => 'City',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'our_menu_city',
    'type' => 'text_medium',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Province',
    'desc' => '<p class="cmb2-metabox-description">Enter as abbreviation (i.e. ON)</p>',
    'id'   => 'our_menu_province',
    'type' => 'text_small',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );
  
  $cmb2 -> add_field( array(
    'name' => 'Postal Code',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'our_menu_zip',
    'type' => 'text_small',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Phone',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'our_menu_phone',
    'type' => 'text_medium',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Website',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'our_menu_website',
    'type' => 'text_url',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Email',
    // 'desc' => '<p class="cmb2-metabox-description">Format: yyyy/mm/dd <br /> Required</p>',
    'id'   => 'our_menu_email',
    'type' => 'text_email',
    // 'attributes'  => array(
    //   'required'    => 'required',
    // ),
  ) );

}
add_action( 'cmb2_admin_init', 'our_menu_post_type_metaboxes' );


