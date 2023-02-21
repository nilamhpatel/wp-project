<?php

function register_design_inspirations_post_type() {
  $labels = array(
    'name'                  => 'Design Inspirations',
    'singular_name'         => 'Design Inspiration', 
    'menu_name'             => 'Design Inspirations',
    'name_admin_bar'        => 'Design Inspiration',
    'archives'              => 'Design Inspiration Archives',
    'attributes'            => 'Design Inspiration Attributes',
    'parent_item_colon'     => 'Parent Design Inspirations:',
    'all_items'             => 'All Design Inspirations',
    'add_new_item'          => 'Add New Design Inspiration',
    'add_new'               => 'Add New',
    'new_item'              => 'New Design Inspiration',
    'edit_item'             => 'Edit Design Inspiration',
    'update_item'           => 'Update Design Inspiration',
    'view_item'             => 'View Design Inspiration',
    'view_items'            => 'View Design Inspiration',
    'search_items'          => 'Search Design Inspirations',
    'not_found'             => 'Not found',
    'not_found_in_trash'    => 'Not found in Trash',
    'featured_image'        => 'Featured Image',
    'set_featured_image'    => 'Set featured image',
    'remove_featured_image' => 'Remove featured image',
    'use_featured_image'    => 'Use as featured image',
    'insert_into_item'      => 'Insert into item',
    'uploaded_to_this_item' => 'Uploaded to this Design Inspiration',
    'items_list'            => 'Design Inspirations list',
    'items_list_navigation' => 'Design Inspirations list navigation',
    'filter_items_list'     => 'Filter items list',
  );

  $rewrite = array( 
    'slug' => '/design-inspiration/project', 
    'with_front' => true
  );

  $args = array(
    'label'                 => 'Design Inspirations',
    'description'           => 'sassys Design Inspirationss',
    'labels'                => $labels,
    'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		// 'taxonomies'            => array( 'category' ),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 20,
    'menu_icon'             => 'dashicons-art',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => true,
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'capability_type'       => 'page',
    'show_in_rest'          => true,
    'rewrite'               => $rewrite,
  );
  
  register_post_type( 'design-inspiration', $args );
}
add_action( 'init', 'register_design_inspirations_post_type', 0 );



function register_projects_design_inspiration_cat() {
  $labels = array(
      'name'              => 'Design Categories',
      'singular_name'     => 'Design Category',
      'search_items'      => 'Search Design Categories',
      'all_items'         => 'All Design Categories',
      'parent_item'       => 'Parent Design Category',
      'parent_item_colon' => 'Parent Design Category:',
      'edit_item'         => 'Edit Design Category',
      'update_item'       => 'Update Design Category',
      'add_new_item'      => 'Add New Design Category',
      'new_item_name'     => 'New Design Category Name',
      'menu_name'         => 'Design Categories',
  );

  $cat_rewrite = array( 
    'slug' => '/design-inspiration',
    'with_front' => true
  );

  $args = array(
      'hierarchical'      => true,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'show_in_rest'      => true,
      'rewrite'           => $cat_rewrite,
  );

  register_taxonomy( 'design-inspiration-cat', array( 'design-inspiration' ), $args );
}
add_action( 'init', 'register_projects_design_inspiration_cat', 0 );



/**
 * Plugin class
 **/
if ( ! class_exists( 'CT_TAX_META' ) ) {
	class CT_TAX_META {
		public function __construct() {}
	 
		/**
		 * Initialize the class and start calling our hooks and filters
		 * @since 1.0.0
		 */
    public function init() {
      add_action( 
        'design-inspiration-cat_add_form_fields', array ( $this, 'add_category_image' ), 10, 2 
      );
      add_action( 'created_design-inspiration-cat', array ( $this, 'save_category_image' ), 10, 2 );
      add_action( 
        'design-inspiration-cat_edit_form_fields', array ( $this, 'update_category_image' ), 10, 2
      );
      add_action(
        'edited_design-inspiration-cat', array ( $this, 'updated_category_image' ), 10, 2
      );
      add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );
      add_action( 'admin_footer', array ( $this, 'add_script' ) );
    }
	
		public function load_media() {
			wp_enqueue_media();
		}


	 
		/**
		 * Add a form field in the new category page
		 * @since 1.0.0
		 */
		public function add_category_image ( $taxonomy ) { 
	?>
		<div class="form-field term-group">
			<label for="category-image-id"><?php _e('Image', 'hero-theme'); ?></label>
			
			<input type="hidden" id="category-image-id" name="category-image-id" 
							class="custom_media_url" value="" />
			
			<div id="category-image-wrapper"></div>
			
			<p>
				<input type="button" class="button button-secondary ct_tax_media_button"
								id="ct_tax_media_button" name="ct_tax_media_button" 
								value="<?php _e( 'Add Image', 'hero-theme' ); ?>" />
				<input type="button" class="button button-secondary ct_tax_media_remove" 
								id="ct_tax_media_remove" name="ct_tax_media_remove" 
								value="<?php _e( 'Remove Image', 'hero-theme' ); ?>" />
			</p>
		</div>
	<?php
		}
		
	  /**
		 * Save the form field
		 * @since 1.0.0
	   */
		public function save_category_image ( $term_id, $tt_id ) {
			if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
				$image = $_POST['category-image-id'];
				add_term_meta( $term_id, 'category-image-id', $image, true );
			}
		}
	 
		/**
		 * Edit the form field
		 * @since 1.0.0
		 */
	 	public function update_category_image ( $term, $taxonomy ) {
	?>
		<tr class="form-field term-group-wrap">
			<th scope="row">
				<label for="category-image-id"><?php _e( 'Image', 'hero-theme' ); ?></label>
			</th>
		
			<td>
				<?php $image_id = get_term_meta ( $term -> term_id, 'category-image-id', true ); ?>
		
				<input type="hidden" id="category-image-id" name="category-image-id" 
							 value="<?php echo $image_id; ?>" />
				 
				<div id="category-image-wrapper">
					<?php if ( $image_id ) echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
				</div>
				 
				<p>
					<input type="button" class="button button-secondary ct_tax_media_button"
								 id="ct_tax_media_button" name="ct_tax_media_button"
								 value="<?php _e( 'Add Image', 'hero-theme' ); ?>" />
					<input type="button" class="button button-secondary ct_tax_media_remove" 
								 id="ct_tax_media_remove" name="ct_tax_media_remove"
								 value="<?php _e( 'Remove Image', 'hero-theme' ); ?>" />
				</p>
			</td>
		</tr>
	<?php
	 }
	



	/*
	 * Update the form field value
	 * @since 1.0.0
	 */
	 public function updated_category_image ( $term_id, $tt_id ) {
		 if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
			 $image = $_POST['category-image-id'];
			 update_term_meta ( $term_id, 'category-image-id', $image );
		 } else {
			 update_term_meta ( $term_id, 'category-image-id', '' );
		 }
	 }
	
	/*
	 * Add script
	 * @since 1.0.0
	 */
	 public function add_script() { ?>
		 <script>
			 jQuery(document).ready( function($) {
				 function ct_media_upload(button_class) {
					 var _custom_media = true,
					 _orig_send_attachment = wp.media.editor.send.attachment;
					 $('body').on('click', button_class, function(e) {
						 var button_id = '#'+$(this).attr('id');
						 var send_attachment_bkp = wp.media.editor.send.attachment;
						 var button = $(button_id);
						 _custom_media = true;
						 wp.media.editor.send.attachment = function(props, attachment){
							 if ( _custom_media ) {
								 $('#category-image-id').val(attachment.id);
								 $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
								 $('#category-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
							 } else {
								 return _orig_send_attachment.apply( button_id, [props, attachment] );
							 }
							}
					 wp.media.editor.open(button);
					 return false;
				 });
			 }
			 ct_media_upload('.ct_tax_media_button.button'); 
			 $('body').on('click','.ct_tax_media_remove',function(){
				 $('#category-image-id').val('');
				 $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
			 });
			 // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
			 $(document).ajaxComplete(function(event, xhr, settings) {
				 var queryStringArr = settings.data.split('&');
				 if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
					 var xml = xhr.responseXML;
					 $response = $(xml).find('term_id').text();
					 if($response!=""){
						 // Clear the thumb image
						 $('#category-image-wrapper').html('');
					 }
				 }
			 });
		 });
	 </script>
	 <?php }
	
		}
	 
	$CT_TAX_META = new CT_TAX_META();
	$CT_TAX_META -> init();
	 
  }
  

