<?php
/**
 * Plugin Name: BEAF - Ultimate Before After Image Slider & Gallery
 * Plugin URI: https://themefic.com/plugins/beaf/
 * Description: Want to show comparison of two images? With BEAF, you can easily create before and after image slider or image gallery. Elementor Supported.
 * Version: 4.3.15
 * Author: Themefic
 * Author URI: https://themefic.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: bafg
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit();
}

class BAFG_Before_After_Gallery {
    
    public function __construct(){
      
        /*
        * Enqueue css and js for BAFG
        */
        add_action( 'wp_enqueue_scripts', array($this, 'bafg_image_before_after_foucs_scripts'), 999 ); 
        
		// Check if Elementor installed and activated
		if ( did_action( 'elementor/loaded' ) ) {
			add_action( 'elementor/editor/before_enqueue_scripts', array($this, 'bafg_image_before_after_foucs_scripts') );
		}
        
        /*
        * BAFG init
        */
        add_action( 'init', array( $this, 'bafg_image_before_after_foucs_posttype' ) );

        // BEAF_PLUGIN_URL
        if(!defined('BEAF_PLUGIN_URL')){ 
            define( 'BEAF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        }
        
        /*
        * BAFG meta fields
        */
        $this->bafg_meta_fields();
        
        /*
        * Require admin file
        */
        require_once('admin/bafg-admin.php');
        /*
        * Require admin global options file
        */
        require_once('admin/global-options.php');
        
        /*
        * Adding shortcode for bafg
        */
        add_shortcode('bafg', array( $this, 'bafg_post_shortcode' ));
		
		/*
		* Gallery shortcode
		*/
        add_shortcode('bafg_gallery', array( $this, 'bafg_gallery_shortcode' ));
        
        /*
        * Submenu for pro version
        */
        add_action('admin_menu', array( $this, 'bafg_register_submenu_page' ) );
        
        /*
        * Require bafg wp widget
        */
        require_once('inc/widget/bafg-widget.php');
		
        /*
        * Require function file
        */
        require_once('inc/functions.php');
        
        /*
        * Require elementor widget
        */
        require_once('inc/bafg-elementor/bafg-elementor.php');
        
        /* 
        * Filter the single_template with our custom function
        */
        add_filter('single_template', array( $this, 'bafg_custom_single_template') );

        /* 
        * Initialize the appsero
        */
        $this->appsero_init_tracker_beaf_before_and_after_gallery();
    }
    
    /*
    * Enqueue css and js in frontend
    */
    public function bafg_image_before_after_foucs_scripts() {
        
        wp_enqueue_style( 'bafg_twentytwenty', plugin_dir_url( __FILE__ ) . 'assets/css/twentytwenty.css'); 
        wp_enqueue_style( 'bafg-style', plugin_dir_url( __FILE__ ) . 'assets/css/bafg-style.css'); 

        $debug_mode = is_array(get_option('bafg_tools')) ? get_option('bafg_tools')['enable_debug_mode'] : '';
        $in_footer = false;
        if( !empty($debug_mode) ){
            $in_footer = true;
        }
        wp_enqueue_script( 'eventMove', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.event.move.js', array('jquery'), null, $in_footer );
        wp_enqueue_script( 'bafg_twentytwenty', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.twentytwenty.js', array('jquery','eventMove'), null, $in_footer );
        wp_enqueue_script( 'bafg_custom_js', plugin_dir_url( __FILE__ ) . 'assets/js/bafg-custom-js.js', array('jquery','bafg_twentytwenty'), null, true );       
    }
    
    //register post type
    public function bafg_image_before_after_foucs_posttype() {
        register_post_type( 'bafg',
            array(
                'labels' => array(
                    'name' => _x( 'Before and After Slider', 'bafg' ),
                    'singular_name' => _x( 'Before and After Slider', 'bafg' ),
                    'add_new' => __( 'Add New', 'bafg' ),
                    'add_new_item' => __( 'Add New Slider', 'bafg' ),
                    'new_item' => __( 'New Slider', 'bafg' ),
                    'edit_item' => __( 'Edit Slider', 'bafg' ),
                    'view_item' => __( 'View Slider', 'bafg' ),
                    'all_items' => __( 'All Sliders', 'bafg' ),
                    'search_items' => __( 'Search Sliders', 'bafg' ),
                    'not_found' => __( 'No slider found.', 'bafg' ),
                    'not_found_in_trash' => __( 'No slider found in Trash.', 'bafg' ),
                ),
                'has_archive' => true,                
                'public' => false,
				'publicly_queryable' => true,
				'show_ui' => true,
				'exclude_from_search' => true,
				'show_in_nav_menus' => false,
				'has_archive' => false,
				'rewrite' => false,
                'supports' => apply_filters('bafg_post_type_supports', array('title')),
                'menu_icon'  => 'dashicons-format-gallery'
            )
        );
		
		// Register Custom Taxonomy
		$labels = array(
			'name'                       => _x( 'Categories', 'Taxonomy General Name', 'bafg' ),
			'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'bafg' ),
			'menu_name'                  => __( 'Category', 'bafg' ),
			'all_items'                  => __( 'All Items', 'bafg' ),
			'parent_item'                => __( 'Parent Item', 'bafg' ),
			'parent_item_colon'          => __( 'Parent Item:', 'bafg' ),
			'new_item_name'              => __( 'New Item Name', 'bafg' ),
			'add_new_item'               => __( 'Add New Item', 'bafg' ),
			'edit_item'                  => __( 'Edit Item', 'bafg' ),
			'update_item'                => __( 'Update Item', 'bafg' ),
			'view_item'                  => __( 'View Item', 'bafg' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'bafg' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'bafg' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'bafg' ),
			'popular_items'              => __( 'Popular Items', 'bafg' ),
			'search_items'               => __( 'Search Items', 'bafg' ),
			'not_found'                  => __( 'Not Found', 'bafg' ),
			'no_terms'                   => __( 'No items', 'bafg' ),
			'items_list'                 => __( 'Items list', 'bafg' ),
			'items_list_navigation'      => __( 'Items list navigation', 'bafg' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
		);
		register_taxonomy( 'bafg_gallery', array( 'bafg' ), $args );
		
    }
	
    /*
    * Adding submenu for pro version
    */
    public function bafg_register_submenu_page() {

		if ( !is_plugin_active( 'beaf-before-and-after-gallery-pro/before-and-after-gallery-pro.php' ) ){
            add_submenu_page(
                'edit.php?post_type=bafg',
                'Go Pro',
                '<span class="bafg-pro-link">★ Go Pro</span>',
                'manage_options',
                'https://themefic.com/plugins/beaf/pro/'
            );
        }
		
    }

    /*
     metabox included
    */
    public function bafg_meta_fields(){
        require_once('metabox/bafg-metaboxs.php');
    }

    /*
    * BAFG shortcode callback
    */
    public function bafg_post_shortcode( $atts, $content = null ){

        extract( shortcode_atts(array(
            'id' => ''
        ), $atts) );
        
		ob_start();
		
		$b_image = get_post_meta( $id, 'bafg_before_image', true);
		$a_image = get_post_meta( $id, 'bafg_after_image', true); 
		$orientation = !empty(get_post_meta( $id, 'bafg_image_styles', true)) ? get_post_meta( $id, 'bafg_image_styles', true) : 'horizontal';
		$offset = !empty(get_post_meta( $id, 'bafg_default_offset', true)) ? get_post_meta( $id, 'bafg_default_offset', true) : '0.5';
		$before_label = !empty(get_post_meta( $id, 'bafg_before_label', true)) ? get_post_meta( $id, 'bafg_before_label', true) : 'Before';
		$after_label = !empty(get_post_meta( $id, 'bafg_after_label', true)) ? get_post_meta( $id, 'bafg_after_label', true) : 'After';
		$overlay = !empty(get_post_meta( $id, 'bafg_no_overlay', true)) ? get_post_meta( $id, 'bafg_no_overlay', true) : 'no';
		$move_slider_on_hover = !empty(get_post_meta( $id, 'bafg_move_slider_on_hover', true)) ? get_post_meta( $id, 'bafg_move_slider_on_hover', true) : 'no';
		$click_to_move = !empty(get_post_meta( $id, 'bafg_click_to_move', true)) ? get_post_meta( $id, 'bafg_click_to_move', true) : 'no'; 
		$skip_lazy_load = get_post_meta( $id, 'skip_lazy_load', true);
        $before_img_alt = get_post_meta( $id, 'before_img_alt', true) ? get_post_meta( $id, 'before_img_alt', true) : '';
        $after_img_alt = get_post_meta( $id, 'after_img_alt', true) ? get_post_meta( $id, 'after_img_alt', true) : '';
		if( $skip_lazy_load == 'yes' ) {
			$skip_lazy = 'skip-lazy';
			$data_skip_lazy = 'data-skip-lazy';
		}else {
			$skip_lazy = '';
			$data_skip_lazy = '';
		}
		
		if(get_post_status($id) == 'publish' ) :
		?>

        <?php do_action('bafg_before_slider', $id); ?>

        <div class="bafg-twentytwenty-container <?php echo esc_attr('slider-'.$id.''); ?> <?php if(get_post_meta($id, 'bafg_custom_color', true) == 'yes') echo 'bafg-custom-color'; ?>" bafg-orientation="<?php echo esc_attr($orientation); ?>" bafg-default-offset="<?php echo esc_attr($offset); ?>" bafg-before-label="<?php echo esc_attr($before_label); ?>" bafg-after-label="<?php echo esc_attr($after_label); ?>" bafg-overlay="<?php echo esc_attr($overlay); ?>" bafg-move-slider-on-hover="<?php echo esc_attr($move_slider_on_hover); ?>" bafg-click-to-move="<?php echo esc_attr($click_to_move); ?>">
        
            <img class="<?php echo esc_attr( $skip_lazy ); ?>" <?php echo esc_attr( $data_skip_lazy ); ?> src="<?php echo esc_url($b_image); ?>" alt="<?php echo esc_attr( $before_img_alt ); ?>">
            <img class="<?php echo esc_attr( $skip_lazy ); ?>" <?php echo esc_attr( $data_skip_lazy ); ?> src="<?php echo esc_url($a_image); ?>" alt="<?php echo esc_attr( $after_img_alt ); ?>">
            
        </div>

        <?php do_action('bafg_after_slider', $id); ?>

        <style>
            <?php $bafg_before_label_background= !empty(get_post_meta($id, 'bafg_before_label_background', true)) ? get_post_meta($id, 'bafg_before_label_background', true) : '';

            $bafg_before_label_color= !empty(get_post_meta($id, 'bafg_before_label_color', true)) ? get_post_meta($id, 'bafg_before_label_color', true) : '';

            $bafg_after_label_background= !empty(get_post_meta($id, 'bafg_after_label_background', true)) ? get_post_meta($id, 'bafg_after_label_background', true) : '';

            $bafg_after_label_color= !empty(get_post_meta($id, 'bafg_after_label_color', true)) ? get_post_meta($id, 'bafg_after_label_color', true) : '';

            ?><?php if( !empty($bafg_before_label_background) || !empty($bafg_before_label_color)) {
                ?>.<?php echo 'slider-'.$id.' ';

                ?>.twentytwenty-before-label::before {
                    background: <?php echo esc_attr($bafg_before_label_background);
                    ?>;
                    color: <?php echo esc_attr($bafg_before_label_color);
                    ?>;
                }

                <?php
            }

            ?><?php if( !empty($bafg_after_label_background) || !empty($bafg_after_label_color)) {
                ?>.<?php echo 'slider-'.$id.' ';

                ?>.twentytwenty-after-label::before {
                    background: <?php echo esc_attr($bafg_after_label_background);
                    ?>;
                    color: <?php echo esc_attr($bafg_after_label_color);
                    ?>;
                }

                <?php
            }

            ?>

        </style>
        <?php
		endif;
		
        return ob_get_clean();
    }
	
	/*
    * BAFG Gallery shortcode callback
    */
    public function bafg_gallery_shortcode( $atts, $content = null ){

		ob_start();
		
        extract( shortcode_atts(array(
            'category' => '',
            'column' => '',
            'items' => -1,
			'info'	=> ''
        ), $atts) );
		
		
        if( $category != '' ) {
			
			$gallery_query = new WP_Query( array(
				'post_type' => 'bafg',
				'posts_per_page' => $items,
				'tax_query' => array(
					array (
						'taxonomy' => 'bafg_gallery',
						'field' => 'id',
						'terms' => $category,
					)
				),
			) );
			$column = !empty($column) ? $column : '2';

			switch ($column) {
			  case "2":
				$col = '6';
				break;
			  case "3":
				$col = '4';
				break;
			  case "4":
				$col = '3';
				break;
			  default:
				$col = '6';
			}

			?>
		<?php $gallery_id = rand(10, 200); ?>
		<?php if($info != 'true'): ?>
		<style>
			.bafg-gallery-row.gallery-id-<?php echo esc_attr($gallery_id);

			?>.bafg-slider-info {
				display: none !important
			}

		</style>
		<?php endif; ?>

		<div class="bafg-row bafg-gallery-row gallery-id-<?php echo esc_attr($gallery_id); ?>">
    	<?php
			while ( $gallery_query->have_posts() ) :
				$gallery_query->the_post();

				echo '<div class="bafg-col-'.$col.'">';
				echo do_shortcode('[bafg id="'.get_the_id().'"]');
				echo '</div>';

			endwhile;
			?>
		</div>
		<?php
			wp_reset_postdata();
		}
		
        return ob_get_clean();
    }

    public function bafg_custom_single_template($single) {

        global $post;

        /* Checks for single template by post type */
        if ( $post->post_type == 'bafg' ) {
            if ( file_exists( plugin_dir_path( __FILE__ ) . 'inc/templates/single-bafg.php' ) ) {
                return plugin_dir_path( __FILE__ ) . 'inc/templates/single-bafg.php';
            }
        }

        return $single;

    }
    
    /**
     * Initialize the plugin tracker
     *
     * @return void
     */
    public function appsero_init_tracker_beaf_before_and_after_gallery() {

        if ( ! class_exists( 'Appsero\Client' ) ) {
            require_once ( __DIR__ . '/inc/app/src/Client.php');
        }

        $client = new Appsero\Client( 'daee3b5d-d8a3-46f0-ae49-7b6f869f4b42', 'Ultimate Before After Image Slider & Gallery – BEAF', __FILE__ );

        // Change Admin notice text

       	$notice = sprintf( $client->__trans( 'Want to help make <strong>%1$s</strong> even more awesome? Allow %1$s to collect non-sensitive diagnostic data and usage information. I agree to get Important Product Updates & Discount related information on my email from  %1$s (I can unsubscribe anytime).' ), $client->name );
       	$client->insights()->notice($notice); 

        
        // Active insights
        $client->insights()->init();

    }

    

}

new BAFG_Before_After_Gallery();
