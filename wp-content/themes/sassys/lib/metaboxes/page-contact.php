<?php
function contact_page_metabox() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'contact_page_form_id',
    'title'        => 'Contact Page',
    'object_types' => array( 'page' ),
    'show_on'      => array( 
      'key'   => 'page-template',
      'value' => 'page-contact.php'
    ),
    'context'      => 'normal',
    'priority'     => 'high',
    'show_names'   => true, // Show field names on the left
    'cmb_styles'   => true, // false to disable the CMB stylesheet
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Left Text',
    'id'   => 'contact_left_text',
    'type' => 'text',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Button Text',
    'id'   => 'contact_button_text',
    'type' => 'text',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Button Link',
    'id'   => 'contact_button_link',
    'type' => 'text',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Contact Form ID',
    'id'   => 'contact_form_id',
    'type' => 'text_small',
  ) );
}
add_action( 'cmb2_admin_init', 'contact_page_metabox' );
