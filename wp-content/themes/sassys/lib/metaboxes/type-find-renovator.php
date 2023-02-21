<?php

function find_a_renovator_post_type_metaboxes() {
  $cmb2 = new_cmb2_box( array(
    'id'           => 'find_a_renovator_post_type_metaboxes',
    'title'        => 'Project Info',
    'object_types' => array( 'find-a-renovator' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true, // Show field names on the left
    'cmb_styles' => true, // false to disable the CMB stylesheet
  ) );

  $cmb2 -> add_field( array(
    'name' => 'iMIS Id',
    'id'   => 'far_imis_id',
    'type' => 'text_small',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'HBA Prov',
    'id'   => 'far_hba_prov',
    'type' => 'text_small',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'HBA',
    'id'   => 'far_hba',
    'type' => 'text',
  ) );

  // $cmb2 -> add_field( array(
  //   'name' => 'Company',
  //   'id'   => 'far_company',
  //   'type' => 'text',
  // ) );

  $cmb2 -> add_field( array(
    'name' => 'Contact First Name',
    'id'   => 'far_contact_first_name',
    'type' => 'text_medium',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Contact Last Name',
    'id'   => 'far_contact_last_name',
    'type' => 'text_medium',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Address 1',
    'id'   => 'far_address_one',
    'type' => 'textarea_small',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Address 2',
    'id'   => 'far_address_two',
    'type' => 'textarea_small',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Address 3',
    'id'   => 'far_address_three',
    'type' => 'textarea_small',
  ) );

  // $cmb2 -> add_field( array(
  //   'name' => 'City',
  //   'id'   => 'far_city',
  //   'type' => 'text_small',
  // ) );

  // $cmb2 -> add_field( array(
  //   'name' => 'Province',
  //   'id'   => 'far_province',
  //   'type' => 'text_small',
  // ) );

  $cmb2 -> add_field( array(
    'name' => 'Postal Code',
    'id'   => 'far_postal_code',
    'type' => 'text_small',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Country',
    'id'   => 'far_country',
    'type' => 'text_small',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Website',
    'id'   => 'far_website',
    'type' => 'text_url',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Contact Email',
    'id'   => 'far_contact_email',
    'type' => 'text_email',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Phone',
    'id'   => 'far_phone',
    'type' => 'text_medium',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Fax',
    'id'   => 'far_fax',
    'type' => 'text_medium',
  ) );

  // $cmb2 -> add_field( array(
  //   'name' => 'Categories',
  //   'id'   => 'far_categories',
  //   'type' => 'textarea_small',
  // ) );

  $cmb2 -> add_field( array(
    'name' => 'Sub Categories',
    'id'   => 'far_sub_categories',
    'type' => 'textarea_small',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Company Description',
    'id'   => 'far_company_description',
    'type' => 'textarea',
  ) );

  $cmb2 -> add_field( array(
    'name' => 'Logo',
    'id'   => 'far_logo',
    'type' => 'file',
    'query_args' => array(
      'type' => array( 'image/gif', 'image/jpeg', 'image/png', ),
    ),
    'preview_size' => 'thumbnail',
  ) );
}
add_action( 'cmb2_admin_init', 'find_a_renovator_post_type_metaboxes' );
