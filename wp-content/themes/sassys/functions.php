<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/main{$suffix}.css";
	$theme_scripts = "/js/main{$suffix}.js";

	wp_enqueue_style( 
		'sassys-styles', get_stylesheet_directory_uri() . $theme_styles, array(),
		$the_theme->get( 'Version' )
	);
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 
		'sassys-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(),
		$the_theme->get( 'Version' ), true
	);
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
// function add_child_theme_textdomain() {
// 	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
// }
// add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @param string $current_mod The current value of the theme_mod.
 * @return string
 */
function understrap_default_bootstrap_version( $current_mod ) {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer', get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ), '20130508', true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );



/**
 * Register the nav menus
 */
register_nav_menus( [
	'global-header-menu' => 'Global Header Menu',
	// 'footer-column-2'		 => 'Footer Column Two',
	// 'footer-column-3'		 => 'Footer Column Three',
	// 'footer-column-4'		 => 'Footer Column Four',
	'footer-policy-nav'  => 'Footer Policy Nav',
] );



/**
 * Remove parent theme page templates
 */
function remove_parent_page_templates( $page_templates ) {
  unset( 
		$page_templates[ 'page-templates/blank.php' ],
		$page_templates[ 'page-templates/both-sidebarspage.php' ],
		$page_templates[ 'page-templates/empty.php' ],
		$page_templates[ 'page-templates/fullwidthpage.php' ],
		$page_templates[ 'page-templates/left-sidebarpage.php' ],
		$page_templates[ 'page-templates/right-sidebarpage.php' ],
	);

  return $page_templates;
}
add_filter( 'theme_page_templates', 'remove_parent_page_templates' );



/**
 * Import lib files
 */
require_once dirname( __FILE__ ) . '/lib/load-more.php';
require_once dirname( __FILE__ ) . '/lib/metaboxes.php';
require_once dirname( __FILE__ ) . '/lib/post-types.php';
// require_once dirname( __FILE__ ) . '/lib/shortcodes.php';
require_once dirname( __FILE__ ) . '/lib/nav-walker.php';
require_once dirname( __FILE__ ) . '/lib/site-options.php';





/**
 * Templates and Page IDs without editor
 */
function ea_disable_editor( $id = false ) {
	$excluded_templates = array(
		'page-our-menus.php',
		'page-design-inspiration.php',
		'page-find-renovator.php'
	);

	// $excluded_ids = array( get_option( 'page_on_front' ) );

	if( empty( $id ) ) return false;

	$id 			= intval( $id );
	$template = get_page_template_slug( $id );

	// return in_array( $id, $excluded_ids ) || in_array( $template, $excluded_templates );
	return in_array( $template, $excluded_templates );
}



/**
 * Disable Gutenberg by template
 */
function ea_disable_gutenberg( $can_edit, $post_type ) {
	if( ! ( is_admin() && !empty( $_GET['post'] ) ) ) return $can_edit;
	if( ea_disable_editor( $_GET['post'] ) ) $can_edit = false;

	return $can_edit;
}



/**
 * Disable Classic Editor by template
 */
function ea_disable_classic_editor() {
	$screen = get_current_screen();

	if( 'page' !== $screen->id || ! isset( $_GET['post']) ) return;
	if( ea_disable_editor( $_GET['post'] ) ) remove_post_type_support( 'page', 'editor' );
}
add_filter( 'gutenberg_can_edit_post_type', 'ea_disable_gutenberg', 10, 2 );
add_filter( 'use_block_editor_for_post_type', 'ea_disable_gutenberg', 10, 2 );
add_action( 'admin_head', 'ea_disable_classic_editor' );



/**
 * Point find a renovaotr form results to the proper page
 */
function renovator_search_form_redirect() {
	
	// Only run function on the homepage search form
	if( is_page_template( 'page-front.php' ) ) {

		// If the search button is clicked
		if( $_REQUEST[ 'renovator-search' ] == 'search' ) { 

			// Gather any queries
			$search_array = [];

			if( $_REQUEST[ 'province' ] ) $search_array[] = 'province=' . $_REQUEST[ 'province' ];
			if( $_REQUEST[ 'city' ] ) 	  $search_array[] = 'city=' . $_REQUEST[ 'city' ];
			if( $_REQUEST[ 'type' ] )     $search_array[] = 'type=' . $_REQUEST[ 'type' ];
			if( $_REQUEST[ 'contains' ] ) $search_array[] = 'contains=' . $_REQUEST[ 'contains' ];

			// Go to proper page, bring along your queries
			wp_redirect( get_home_url() . '/find-a-renovator/?' . implode( '&', $search_array ) );

      die();
    }
	}
}
add_action( 'template_redirect', 'renovator_search_form_redirect' );
