<?php

function sassys_theme_options_metabox() {
  $cmb_options = new_cmb2_box( array(
    'id'           => 'sassys_theme_options_metabox',
    'title'        => 'Site Options',
    'object_types' => array( 'options-page' ),
    'option_key'   => 'sassys_theme_options',
  ) );


  
  /************************************ 
   * Design Inspiration Categories 
   ************************************/ 
  $cmb_options -> add_field( array(
    'name' => 'Design Inspiration Categories',
    'id'   => 'design_inspiration_categories',
    'type' => 'title',
  ) );

  $cmb_options -> add_field( array(
    'name' => 'AdSanity Shortcode',
    'id'   => 'design_inspiration_shortcode_ad',
    'desc' => 'Use the shortcode provided by the AdSanity plugin.',
    'type' => 'text',
  ) );



  /************************************ 
   * Site Footer 
   ************************************/
  $cmb_options -> add_field( array(
    'name' => 'Site Footer',
    'id'   => 'global_site_footer_title',
    'type' => 'title',
  ) );

  $cmb_options -> add_field( array(
    'name' => 'Instagram Feed ID',
    'id'   => 'instagram_feed_id',
    'type' => 'text_small',
  ) );
 
  $cmb_options -> add_field( array(
    'name' => 'Callout Message',
    'id'   => 'callout_message',
    'type' => 'text',
    'default' => __( 'Renovate with Confidence', 'sassys' )
  ) );

  $cmb_options -> add_field( array(
    'name' => 'Call to Action Image',
    'id'   => 'cta_image',
    'type' => 'file',
    'query_args' => array(
      'type' => array( 'image/gif', 'image/jpeg', 'image/png', ),
    ),
    'preview_size' => 'thumbnail', // Image size to use when previewing in the admin.
  ) );

  $cmb_options -> add_field( array(
    'name' => 'Call to Action Title',
    'id'   => 'cta_title',
    'type' => 'text',
  ) );

  $cmb_options -> add_field( array(
    'name' => 'Call to Action URL',
    'id'   => 'cta_url',
    'type' => 'text_url',
  ) );



  /************************************ 
   * Social Links
   ************************************/ 
  $cmb_options -> add_field( array(
    'name' => 'Social Links',
    'id'   => 'social_links',
    'type' => 'title',
  ) );

  $cmb_options -> add_field( array(
    'name' => 'Twitter',
    'id'   => 'twitter_url',
    'type' => 'text_URL',
  ) );

  $cmb_options -> add_field( array(
    'name' => 'Instagram',
    'id'   => 'instagram_url',
    'type' => 'text_URL',
  ) );

  $cmb_options -> add_field( array(
    'name' => 'LinkedIn',
    'id'   => 'linkedin_url',
    'type' => 'text_URL',
  ) );

  $cmb_options -> add_field( array(
    'name' => 'Facebook',
    'id'   => 'facebook_url',
    'type' => 'text_URL',
  ) );

  $cmb_options -> add_field( array(
    'name' => 'YouTube',
    'id'   => 'youtube_url',
    'type' => 'text_URL',
  ) );
}
add_action( 'cmb2_admin_init', 'sassys_theme_options_metabox' );





/**
 * Wrapper function around cmb2_get_option
 *
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function sassys_theme_site_option( $key = '', $default = false ) {
  if( function_exists( 'cmb2_get_option' ) ) {
    
    // Use cmb2_get_option as it passes through some key filters.
    return cmb2_get_option( 'sassys_theme_options', $key, $default );
  }
  
  // Fallback to get_option if CMB2 is not loaded yet.
  $opts = get_option( 'sassys_theme_options', $default );
  $val = $default;
  
  if( 'all' == $key ) {
    $val = $opts;
  } 
  
  elseif( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
    $val = $opts[ $key ];
  }
  
  return $val;
}
