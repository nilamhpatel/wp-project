<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit();
}

function bafg_admin_scripts() {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
}

function bafg_admin_styles() {
    wp_enqueue_style('thickbox');
}
add_action('admin_print_scripts', 'bafg_admin_scripts');
add_action('admin_print_styles', 'bafg_admin_styles');


add_action( 'admin_enqueue_scripts', 'bafg_enqueue_color_ficker');
if ( ! function_exists( 'bafg_enqueue_color_ficker' ) ){
    function bafg_enqueue_color_ficker($hook) {
        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
    }
}

//Register Meta box
add_action('add_meta_boxes', function (){
 
    add_meta_box('bafg-metabox','Before after content','bafg_metabox_callback','bafg','normal','high');
    add_meta_box('bafg_shortcode_metabox','Shortcode','bafg_shortcode_callback','bafg','side','high');
    //add_meta_box('bafg_blackfriday','Black Friday Offer','bafg_blackfriday','bafg','side','high');
});

//Metabox content
if( !function_exists('bafg_metabox_callback') ){
function bafg_metabox_callback($post){
    ob_start();
?>
<div class="bafg-tab">
    <a class="bafg-tablinks active" onclick="bafg_option_tab(event, 'bafg_gallery_content')"><?php echo esc_html__('Content','bafg'); ?></a>
    <a class="bafg-tablinks" onclick="bafg_option_tab(event, 'bafg_gallery_options')"><?php echo esc_html__('Options','bafg'); ?></a>
    <a class="bafg-tablinks" onclick="bafg_option_tab(event, 'bafg_gallery_style')"><?php echo esc_html__('Style','bafg'); ?></a>
</div>

<div id="bafg_gallery_content" class="bafg-tabcontent" style="display: block;">
    <table class="bafg-option-table">
        <?php
        ob_start();
        ?>
        <tr>
            <td class="bafg-option-label">
                <p><label for="bafg_before_after_method"><?php echo esc_html__("Before After Method","bafg-pro");?></label></p>
            </td>
            <td class="bafg-option-content">
                <ul>
                    <li><input type="radio" class="" name="bafg_before_after_method" id="bafg_before_after_method1" value="method_1" checked="checked"> <label for="bafg_before_after_method1">Method 1 (Using 2 images)</label></li>
                    <li><input type="radio" class="" name="bafg_before_after_method" id="bafg_before_after_method2" value="method_2">
                    <label for="bafg_before_after_method2">Method 2 (Using 1 image) <div class="bafg-tooltip"><span>?</span>
                        <div class="bafg-tooltip-info">Pro feature! <br>You can make a slider using one image with an effect.</div>
                        </div>
                    </label>
                    </li>
                    <li>
                        <input type="radio" class="" name="bafg_before_after_method" id="bafg_before_after_method3" value="method_3">
                        <label for="bafg_before_after_method3"><?php _e('Method 3 (Using 3 images)','bafg') ?> <div class="bafg-tooltip"><span>?</span>
                            <div class="bafg-tooltip-info">Pro addon required! <br>You can make a slider using 3 images.</div>
                            </div>
                        </label>
                    </li>
                </ul>
                <p>Choose a method to make a before after slider using a single image or 2 images.</p>
            </td>
        </tr>
        <?php
        $bafg_before_after_method = ob_get_clean();
        echo apply_filters( 'bafg_before_after_method', $bafg_before_after_method, $post );
        ?>
        
        <?php
        ob_start();
        $enabled_wm = bafg_option_value('enable_watermark');
        if($enabled_wm == 'on') :
            ?>
            <tr class="bafg-watermark-enable">
                <td class="bafg-option-label">
                    <p><label for="bafg_wm_enable_disable"><?php echo esc_html__("Watermark Enable/Disable","bafg-pro");?></label></p>
                </td>
                <td class="bafg-option-content">
                    <ul>
                        <li><input type="radio" class="" name="" id="bafg_wm_enable" value="yes" checked="checked"> <label for="bafg_wm_enable"><?php _e( 'Enable','bafg' ); ?></label></li>
                        <li>
                            <input type="radio" class="" name="" id="bafg_wm_disable" value="no"> <label for="bafg_wm_disable"><?php _e( 'Disable','bafg' ); ?><div class="bafg-tooltip"><span>?</span>
                            <div class="bafg-tooltip-info">Pro feature! <br>Watermark Addon required to activate this feature</div>
                                </div></label>
                        </li>                    
                    </ul>
                    <p><?php _e( 'Enable or Disable watermark for this indiviusal slider','bafg' ); ?></p>
                </td>
            </tr>
        <?php
        endif;
        $bafg_en_dis_watermark = ob_get_clean();
        echo apply_filters( 'bafg_enable_disable_watermark', $bafg_en_dis_watermark, $post );
        ?>

        <?php 
        $before_img_alt = get_post_meta( $post->ID, 'before_img_alt', true) ? get_post_meta( $post->ID, 'before_img_alt', true) : '';
        $after_img_alt = get_post_meta( $post->ID, 'after_img_alt', true) ? get_post_meta( $post->ID, 'after_img_alt', true) : '';

        ?>
        <tr class="bafg-row-before-image">
            <td class="bafg-option-label">
                <p><label><?php echo esc_html__('Before image','bafg'); ?></label></p>
            </td>
            <td class="bafg-option-content">
                <input type="text" name="bafg_before_image" id="bafg_before_image" size="50" value="<?php echo esc_url(get_post_meta( $post->ID, 'bafg_before_image', true )); ?>" />
                <input class="bafg_button" id="bafg_before_image_upload" type="button" value="Add or Upload Image">
                <img id="bafg_before_image_thumbnail" src="<?php echo esc_url(get_post_meta( $post->ID, 'bafg_before_image', true )); ?>">
                <div class="img-alt-tag">
                    <span><?php _e( 'Image Alt: ' ); ?></span>
                    <input type="text" name="before_img_alt" id="before_img_alt" value="<?php echo esc_attr( $before_img_alt ); ?>" />
                </div>
            </td>
        </tr>
        <tr class="bafg-row-after-image">
            <td class="bafg-option-label"><label for="bafg_before_after_method"><?php echo esc_html__('After image','bafg'); ?></label></td>
            <td class="bafg-option-content">
                <input type="text" name="bafg_after_image" id="bafg_after_image" size="50" value="<?php echo esc_url(get_post_meta( $post->ID, 'bafg_after_image', true )); ?>" />
                <input class="bafg_button" id="bafg_after_image_upload" type="button" value="Add or Upload Image">
                <img id="bafg_after_image_thumbnail" src="<?php echo esc_url(get_post_meta( $post->ID, 'bafg_after_image', true )); ?>">
                <div class="img-alt-tag">
                    <span><?php _e( 'Image Alt: ' ); ?></span>
                    <input type="text" name="after_img_alt" id="after_img_alt" value="<?php echo esc_attr( $after_img_alt ); ?>" />
                </div>
            </td>
        </tr>
        <?php
        ob_start();
        ?>
        <tr class="bafg-row-before-after-image" style="display: none">
            <td class="bafg-option-label"><label>Before after image <div class="bafg-tooltip"><span>?</span>
                <div class="bafg-tooltip-info">Pro feature!</div>
                </div></label>
            </td>
            <td class="bafg-option-content">
                <input type="text" name="bafg_before_after_image" id="bafg_before_after_image" size="50" disabled />
                <input class="bafg_button" id="bafg_before_after_image_upload" type="button" value="Add or Upload Image">
                <input type="hidden" name="img_txt_id" id="img_txt_id" value="" />
            </td>
        </tr>
        <?php
        $bafg_before_after_image = ob_get_clean(); 
        echo apply_filters( 'bafg_before_after_image', $bafg_before_after_image, $post );
        ?>

        <?php

        //Image upload fields for 3 images slider
        ob_start();
        ?>
        <tr class="bafg-row-first-image" style="display: none">
            <td class="bafg-option-label"><label><?php _e( 'First Image','bafg' ); ?> <div class="bafg-tooltip"><span>?</span>
                        <div class="bafg-tooltip-info"><?php _e( 'Pro feature!','bafg' ); ?></div>
                    </div></label></td>
            <td class="bafg-option-content">
                <input type="text" name="bafg_bottom_image" id="bafg_bottom_image" size="50" disabled/>
                <input class="bafg_button" id="bafg_bottom_image_upload" type="button" value="Add or Upload Image">
                <input type="hidden" name="img_txt_id" id="img_txt_id" value="" />
            </td>
        </tr>
        <tr class="bafg-row-second-image" style="display: none">
            <td class="bafg-option-label"><label><?php _e( 'Second image','bafg' ); ?> <div class="bafg-tooltip"><span>?</span>
                        <div class="bafg-tooltip-info"><?php _e( 'Pro feature!','bafg' ); ?></div>
                    </div></label></td>
            <td class="bafg-option-content">
                <input type="text" name="bafg_middle_image" id="bafg_top_image" size="50" disabled />
                <input class="bafg_button" id="bafg_before_after_image_upload" type="button" value="Add or Upload Image">
                <input type="hidden" name="img_txt_id" id="img_txt_id" value="" />
            </td>
        </tr>
        <tr class="bafg-row-third-image" style="display: none">
            <td class="bafg-option-label"><label><?php _e( 'Third image','bafg' ); ?> <div class="bafg-tooltip"><span>?</span>
                        <div class="bafg-tooltip-info"><?php _e( 'Pro feature!','bafg' ); ?></div>
                    </div></label></td>
            <td class="bafg-option-content">
                <input type="text" name="bafg_top_image" id="bafg_before_after_image" size="50" disabled />
                <input class="bafg_button" id="bafg_before_after_image_upload" type="button" value="Add or Upload Image">
                <input type="hidden" name="img_txt_id" id="img_txt_id" value="" />
            </td>
        </tr>
        <?php
        $bafg_before_after_three_image = ob_get_clean(); 
        echo apply_filters( 'bafg_before_after_three_image', $bafg_before_after_three_image, $post );
        ?>
        
        <?php	
        $bafg_slider_title = get_post_meta( $post->ID, 'bafg_slider_title', true );
		?>
		<tr class="bafg-row-before-after-title">
			<td class="bafg-option-label"><label><?php echo esc_html__( 'Slider Title', 'bafg' ); ?></label></td>
			<td class="bafg-option-content">
				<input type="text" class="regular-text" name="bafg_slider_title" id="bafg_slider_title"  value="<?php echo esc_html($bafg_slider_title); ?>" placeholder="<?php echo esc_html__('Optional', 'bafg'); ?>">
			</td>
		</tr>

        <?php
        $bafg_slider_description = get_post_meta( $post->ID, 'bafg_slider_description', true );
		?>
		<tr class="bafg-row-before-after-description">
			<td class="bafg-option-label"><label><?php echo esc_html__( 'Slider Description', 'bafg' ); ?></label></td>
			<td class="bafg-option-content">
				<textarea type="text" class="regular-text" name="bafg_slider_description" id="bafg_slider_description" rows="6" placeholder="<?php echo esc_html__('Optional', 'bafg'); ?>"><?php echo esc_html($bafg_slider_description) ?></textarea>
			</td>
		</tr>
       
        <?php
        $bafg_readmore_link = get_post_meta( $post->ID, 'bafg_readmore_link', true );
		?>
		<tr class="bafg-row-before-after-read-more">
			<td class="bafg-option-label"><label><?php echo esc_html__('Read More Link', 'bafg'); ?></label></td>
			<td class="bafg-option-content">
				<input type="text" class="regular-text" name="bafg_readmore_link" id="bafg_readmore_link" value="<?php echo esc_url($bafg_readmore_link); ?>" placeholder="<?php echo esc_html__('Optional', 'bafg'); ?>">
			</td>
		</tr>
       
       <?php $bafg_readmore_link_target = get_post_meta( $post->ID, 'bafg_readmore_link_target', true ); ?>
       
		<tr class="bafg-row-before-after-read-more">
			<td class="bafg-option-label"><label><?php echo esc_html__('Read More Link Target', 'bafg'); ?></label></td>
			<td class="bafg-option-content">
				<select name="bafg_readmore_link_target" id="bafg_readmore_link_target">
					<option value="">Same page</option>
					<option value="new_tab" <?php selected('new_tab',$bafg_readmore_link_target, true); ?>>New tab</option>
				</select>
			</td>
		</tr>
      
      <?php
        ob_start();
        ?>
        <tr class="bafg-row-before-after-read-more-text">
			<td class="bafg-option-label"><label><?php echo esc_html__('Read More Text', 'bafg'); ?><div class="bafg-tooltip"><span>?</span>
                        <div class="bafg-tooltip-info">Pro feature!</div>
                    </div></label></td>
			<td class="bafg-option-content">
				<input type="text" name="bafg_readmore_text" id="bafg_readmore_text">
			</td>
		</tr>
        <?php
        $bafg_readmore_text_html = ob_get_clean();
        echo apply_filters( 'bafg_readmore_text_html', $bafg_readmore_text_html, $post );
        ?>
       
        <?php
        ob_start();
        ?>
        <tr class="bafg_filter_style" style="display: none">
            <td class="bafg-option-label"><label for="bafg_filter_style">Select Filter Effect <div class="bafg-tooltip"><span>?</span>
                        <div class="bafg-tooltip-info">Pro feature! <br>If you use one image to make a slider, then you can use an effect.</div>
                    </div></label></td>
            <td class="bafg-option-content">
                <ul>
                    <li><input type="radio" name="bafg_filter_style" id="bafg_filter_style1" value="none" disabled> <label for="bafg_filter_style1">None</label></li>

                    <li><input type="radio" name="bafg_filter_style" id="bafg_filter_style2" value="grayscale" disabled> <label for="bafg_filter_style2">Grayscale</label></li>

                    <li><input type="radio" name="bafg_filter_style" id="bafg_filter_style3" value="blur" disabled> <label for="bafg_filter_style3">Blur</label></li>

                    <li><input type="radio" name="bafg_filter_style" id="bafg_filter_style4" value="saturate" disabled> <label for="bafg_filter_style4">Saturate</label></li>

                    <li><input type="radio" name="bafg_filter_style" id="bafg_filter_style5" value="sepia" disabled> <label for="bafg_filter_style5">Sepia</label></li>
                </ul>
                <p>Select a filtering effect to use on the before or after image.</p>
            </td>
        </tr>
        <?php
        $bafg_filter_style_html = ob_get_clean();
        echo apply_filters( 'bafg_filter_style', $bafg_filter_style_html, $post );
        ?>
        <?php
        ob_start();
        ?>
        <tr class="bafg_filter_apply" style="display: none">
            <td class="bafg-option-label"><label for="bafg_filter_apply">Apply filter for <div class="bafg-tooltip"><span>?</span>
            <div class="bafg-tooltip-info">Pro feature!</div>
            </div></label></td>
            <td class="bafg-option-content">
                <ul class="cmb2-radio-list cmb2-list">
                    <li><input type="radio" name="bafg_filter_apply" id="bafg_filter_apply1" value="none" disabled> <label for="bafg_filter_apply1">None</label></li>

                    <li><input type="radio" name="bafg_filter_apply" id="bafg_filter_apply2" value="apply_before" checked="checked" disabled> <label for="bafg_filter_apply2">Before Image</label></li>

                    <li><input type="radio" name="bafg_filter_apply" id="bafg_filter_apply3" value="apply_after" disabled> <label for="bafg_filter_apply3">After Image</label></li>
                </ul>
                <p>Filtering will applicable on selected image.</p>
            </td>
        </tr>
        <?php 
        $bafg_filter_apply_html = ob_get_clean();
        echo apply_filters( 'bafg_filter_apply', $bafg_filter_apply_html, $post );
        ?>
        
        <tr class="bafg-row-orientation">
            <td class="bafg-option-label"><label><?php echo esc_html__('Orientation Style','bafg'); ?></label></td>
            <td class="bafg-option-content">
                <ul class="orientation-style">
                    <?php 
                    $orientation = trim(get_post_meta( $post->ID, 'bafg_image_styles', true )) != '' ? get_post_meta( $post->ID, 'bafg_image_styles', true ) : 'horizontal';
                    ?>
                    <li><input type="radio" name="bafg_image_styles" id="bafg_image_styles1" value="vertical" <?php checked( $orientation, 'vertical' ); ?>> <label for="bafg_image_styles1"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'../assets/image/v.jpg'); ?>" /></label></li>

                    <li><input type="radio" name="bafg_image_styles" id="bafg_image_styles2" value="horizontal" <?php checked( $orientation, 'horizontal' ); ?>> <label for="bafg_image_styles2"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'../assets/image/h.jpg'); ?>" /></label></li>
                </ul>
            </td>
        </tr>
        <?php
        ob_start();
        ?>
        <tr class="">
            <td class="bafg-option-label"><label for="bafg_before_after_style"><?php echo esc_html__('BEAF Template Style','bafg'); ?><div class="bafg-tooltip"><span>?</span><div class="bafg-tooltip-info">Pro feature!</div>
                    </div></label>
            </td>
            <td class="bafg_before_after_style">
                <ul class="bafg-before-after-style">
                    <?php 
                    $bafg_before_after_style = trim(get_post_meta( $post->ID, 'bafg_before_after_style', true )) != '' ? get_post_meta( $post->ID, 'bafg_before_after_style', true ) : 'default';
                    ?>
                    <li><input type="radio" checked name="bafg_before_after_style" id="bafg_before_after_style_default" value="default" <?php checked( $bafg_before_after_style, 'default' ); ?>> <label for="bafg_before_after_style_default"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'../assets/image/default.png'); ?>" /></label></li>
                    <li><input type="radio" name="bafg_before_after_style" id="bafg_before_after_style_1" value="" <?php checked( $bafg_before_after_style, '' ); ?>> <label for="bafg_before_after_style_1"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'../assets/image/style1.png'); ?>" /></label></li>
                    <li><input type="radio" name="bafg_before_after_style" id="bafg_before_after_style_2" value="" <?php checked( $bafg_before_after_style, '' ); ?>> <label for="bafg_before_after_style_2"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'../assets/image/style2.png'); ?>" /></label></li>
                    <li><input type="radio" name="bafg_before_after_style" id="bafg_before_after_style_3" value="" <?php checked( $bafg_before_after_style, '' ); ?>> <label for="bafg_before_after_style_3"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'../assets/image/style3.png'); ?>" /></label></li>
                    <li><input type="radio" name="bafg_before_after_style" id="bafg_before_after_style_4" value="" <?php checked( $bafg_before_after_style, '' ); ?>> <label for="bafg_before_after_style_4"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'../assets/image/style4.png'); ?>" /></label></li>
                    <li><input type="radio" name="bafg_before_after_style" id="bafg_before_after_style_5" value="" <?php checked( $bafg_before_after_style, '' ); ?>> <label for="bafg_before_after_style_5"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'../assets/image/style5.png'); ?>" /></label></li>
                    <li><input type="radio" name="bafg_before_after_style" id="bafg_before_after_style_6" value="" <?php checked( $bafg_before_after_style, '' ); ?>> <label for="bafg_before_after_style_6"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'../assets/image/style6.png'); ?>" /></label></li>
                    <li><input type="radio" name="bafg_before_after_style" id="bafg_before_after_style_7" value="" <?php checked( $bafg_before_after_style, '' ); ?>> <label for="bafg_before_after_style_7"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'../assets/image/style7.png'); ?>" /></label></li>
                    <li><input type="radio" name="bafg_before_after_style" id="bafg_before_after_style_8" value="" <?php checked( $bafg_before_after_style, '' ); ?>> <label for="bafg_before_after_style_8"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'../assets/image/style8.png'); ?>" /></label></li>
                    <li><input type="radio" name="bafg_before_after_style" id="bafg_before_after_style_9" value="" <?php checked( $bafg_before_after_style, '' ); ?>> <label for="bafg_before_after_style_9"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'../assets/image/style9.png'); ?>" /></label></li>
                </ul>
                <p><?php echo esc_html__('Select a style for the before and after label.','bafg'); ?></p>
            </td>
        </tr>
        <?php 
        $bafg_before_after_style_html = ob_get_clean();
        echo apply_filters( 'bafg_before_after_style', $bafg_before_after_style_html, $post );
        ?>
    </table>
</div>

<div id="bafg_gallery_options" class="bafg-tabcontent">
    <table class="bafg-option-table">
        <tr class="bafg-row-offset">
            <td class="bafg-option-label"><label for="bafg_default_offset"><?php echo esc_html__('Default Offset','bafg'); ?></label></td>
            <td class="bafg-option-content">
               <?php 
                $bafg_default_offset = !empty(get_post_meta( $post->ID, 'bafg_default_offset', true )) ? get_post_meta( $post->ID, 'bafg_default_offset', true ) : '0.5';
                
                ?>
                <input type="text" class="regular-text" name="bafg_default_offset" id="bafg_default_offset" value="<?php echo esc_attr($bafg_default_offset); ?>">
                <p><?php echo esc_html__('How much of the before image is visible when the page loads. (e.g: 0.7)','bafg'); ?></p>
            </td>
        </tr>
        <tr class="bafg-row-b-label">
            <td class="bafg-option-label"><label for="bafg_before_label"><?php echo esc_html__('Before Label','bafg'); ?></label></td>
            <td class="bafg-option-content">
               <?php 
                $bafg_before_label = !empty(get_post_meta( $post->ID, 'bafg_before_label', true )) ? get_post_meta( $post->ID, 'bafg_before_label', true ) : 'Before';
                ?>
                <input type="text" class="regular-text" name="bafg_before_label" id="bafg_before_label" value="<?php echo esc_html($bafg_before_label); ?>" >
                <p><?php echo esc_html__('Set a custom label for the before image.','bafg'); ?></p>
            </td>
        </tr>
        <!--Middle lavel html(pro)-->
        <?php ob_start(); ?>
        <tr class="bafg-row-mid-label">
            <td class="bafg-option-label"><label for="bafg_mid_label"><?php echo esc_html__('Middle Label','bafg'); ?><div class="bafg-tooltip"><span>?</span>
                    <div class="bafg-tooltip-info">Pro feature!</div>
                </div></label></td>
            <td class="bafg-option-content">
                <input type="text" class="regular-text" name="bafg_mid_label" id="bafg_mid_label" value="" >
                <p><?php echo esc_html__('Set a custom label for the middle image.','bafg'); ?></p>
            </td>
        </tr>
        <?php 
		$mid_label_html = ob_get_clean();
		echo apply_filters( 'bafg_middle_label_html', $mid_label_html, $post );
		?>
       
        <tr class="bafg-row-a-label">
            <td class="bafg-option-label"><label for="bafg_after_label"><?php echo esc_html__('After Label','bafg'); ?></label></td>
            <td class="bafg-option-content">
               <?php 
                $bafg_after_label = !empty(get_post_meta( $post->ID, 'bafg_after_label', true )) ? get_post_meta( $post->ID, 'bafg_after_label', true ) : 'After';
                ?>
                <input type="text" class="regular-text" name="bafg_after_label" id="bafg_after_label" value="<?php echo esc_html($bafg_after_label); ?>">
                <p><?php echo esc_html__('Set a custom label for the after image.','bafg'); ?></p>
            </td>
        </tr>
        <!--Label Outside of Image start-->
        <?php ob_start();?>
        <tr class="bafg_label_outside">
            <td class="bafg-option-label"><label for="bafg_label_outside1"><?php echo esc_html__('Show Label Outside Of Image','bafg'); ?>
                <div class="bafg-tooltip"><span>?</span>
                    <div class="bafg-tooltip-info">Pro feature!</div>
                </div></label>
            </td>
            <td class="bafg-option-content">
                <ul>
                    <li><input type="radio" name="bafg_label_outside" id="bafg_label_outside1" value="true"> <label for="bafg_label_outside1">Yes</label></li>
                    <li><input type="radio" name="bafg_label_outside" id="bafg_label_outside2" value="false" checked="checked"> <label for="bafg_label_outside2">No</label></li>
                </ul>
            </td>
        </tr>
        <?php 
            $show_label_outside_html = ob_get_clean();
            echo apply_filters( 'bafg_show_label_outside', $show_label_outside_html, $post );
        ?>
        <!--Label Outside of Image start-->

        <?php
        ob_start();
        ?>
        <tr class="bafg_auto_slide">
            <td class="bafg-option-label"><label for="bafg_auto_slide">Auto Slide <div class="bafg-tooltip"><span>?</span>
                        <div class="bafg-tooltip-info">Pro feature!</div>
                    </div></label></td>
            <td class="bafg-option-content">
                <ul>
                    <li><input type="radio" name="bafg_auto_slide" id="bafg_auto_slide1" value="true"> <label for="bafg_auto_slide1">Yes</label></li>
                    <li><input type="radio" name="bafg_auto_slide" id="bafg_auto_slide2" value="false" checked="checked"> <label for="bafg_auto_slide2">No</label></li>
                </ul>
                <p>The before and after image will slide automatically.</p>
            </td>
        </tr>
        <?php
        $bafg_auto_slide_html = ob_get_clean();
        echo apply_filters( 'bafg_auto_slide', $bafg_auto_slide_html, $post );
        ?>
        <?php
        ob_start();
        ?>
        <tr class="bafg_on_scroll_slide">
            <td class="bafg-option-label"><label for="bafg_on_scroll_slide">On scroll Slide <div class="bafg-tooltip"><span>?</span>
                        <div class="bafg-tooltip-info">Pro feature!</div>
                    </div></label></td>
            <td class="bafg-option-content">
                <ul>
                    <li><input type="radio" name="bafg_on_scroll_slide" id="bafg_on_scroll_slide1" value="true"> <label for="bafg_on_scroll_slide1">Yes</label></li>
                    <li><input type="radio" name="bafg_on_scroll_slide" id="bafg_on_scroll_slide2" value="false" checked="checked"> <label for="bafg_on_scroll_slide2">No</label></li>
                </ul>
                <p>The before and after image slider will slide on scroll automatically.</p>
            </td>
        </tr>
        <?php
        $bafg_on_scroll_slide_html = ob_get_clean();
        echo apply_filters( 'bafg_on_scroll_slide', $bafg_on_scroll_slide_html, $post );
        ?>
        <?php
        ob_start();
        ?>
        <tr class="bafg_slide_handle">
            <td class="bafg-option-label"><label for="bafg_slide_handle">Disable Handle <div class="bafg-tooltip"><span>?</span>
                        <div class="bafg-tooltip-info">Pro feature!</div>
                    </div></label></td>
            <td class="bafg-option-content">
                <ul>
                    <li><input type="radio" name="bafg_slide_handle" id="bafg_slide_handle1" value="yes" disabled> <label for="bafg_slide_handle1">Yes</label></li>
                    <li><input type="radio" name="bafg_slide_handle" id="bafg_slide_handle2" value="no" checked="checked" disabled> <label for="bafg_slide_handle2">No</label></li>
                </ul>
                <p>Disable the slider handle.</p>
            </td>
        </tr>
        <?php
        $bafg_slide_handle_html = ob_get_clean();
        echo apply_filters( 'bafg_slide_handle', $bafg_slide_handle_html, $post );
        ?>
        <!-- Popup Preview -->
        <?php
        ob_start();
        ?>
        <tr class="bafg_popup_preview">
            <td class="bafg-option-label"><label for="bafg_popup_preview">Full Screen View<div class="bafg-tooltip"><span>?</span>
                        <div class="bafg-tooltip-info">Pro feature!</div>
                    </div></label></td>
            <td class="bafg-option-content">
                <ul>
                    <li><input type="radio" name="bafg_popup_preview" id="bafg_popup_preview1" value="yes" disabled> <label for="bafg_popup_preview1">Yes</label></li>
                    <li><input type="radio" name="bafg_popup_preview" id="bafg_popup_preview2" value="no" checked="checked" disabled> <label for="bafg_popup_preview2">No</label></li>
                </ul>
                <p>Enable to display slider on full screen.</p>
            </td>
        </tr>
        <?php
        $bafg_popup_preview = ob_get_clean();
        echo apply_filters( 'bafg_popup_preview_meta', $bafg_popup_preview, $post );
        ?>
        
        <tr class="bafg_move_slider_on_hover" style="display: none">
            <td class="bafg-option-label"><label for="bafg_move_slider_on_hover"><?php echo esc_html__('Move slider on mouse hover?','bafg'); ?></label></td>
            <td class="bafg-option-content">
                <ul>
                   <?php 
                    $bafg_move_slider_on_hover = !empty(get_post_meta( $post->ID, 'bafg_move_slider_on_hover', true )) ? get_post_meta( $post->ID, 'bafg_move_slider_on_hover', true ) : 'no';
                    ?>
                    <li><input type="radio" name="bafg_move_slider_on_hover" id="bafg_move_slider_on_hover1" value="yes" <?php checked( $bafg_move_slider_on_hover, 'yes' ); ?>> <label for="bafg_move_slider_on_hover1"><?php echo esc_html__('Yes','bafg'); ?></label></li>
                    <li><input type="radio" name="bafg_move_slider_on_hover" id="bafg_move_slider_on_hover2" value="no" <?php checked( $bafg_move_slider_on_hover, 'no' ); ?>> <label for="bafg_move_slider_on_hover2"><?php echo esc_html__('No','bafg'); ?></label></li>
                </ul>
            </td>
        </tr>
        <tr class="bafg-row-click-to-move">
            <td class="bafg-option-label"><label for="bafg_click_to_move"><?php echo esc_html__('Click to Move','bafg'); ?></label></td>
            <td class="bafg-option-content">
                <ul>
                   <?php 
                    $bafg_click_to_move = !empty(get_post_meta( $post->ID, 'bafg_click_to_move', true )) ? get_post_meta( $post->ID, 'bafg_click_to_move', true ) : 'no';
                    ?>
                    <li><input type="radio" class="cmb2-option" name="bafg_click_to_move" id="bafg_click_to_move1" value="yes" <?php checked( $bafg_click_to_move, 'yes' ); ?>> <label for="bafg_click_to_move1"><?php echo esc_html__('Yes','bafg'); ?></label></li>
                    <li><input type="radio" class="cmb2-option" name="bafg_click_to_move" id="bafg_click_to_move2" value="no" <?php checked( $bafg_click_to_move, 'no' ); ?>> <label for="bafg_click_to_move2"><?php echo esc_html__('No','bafg'); ?></label></li>
                </ul>
                <p><?php echo esc_html__('Allow a user to click (or tap) anywhere on the image to move the slider to that location.','bafg'); ?></p>
            </td>
        </tr>
        <tr class="bafg-row-no-overlay" >
            <td class="bafg-option-label"><label for="bafg_no_overlay"><?php echo esc_html__('Show Overlay','bafg'); ?></label></td>
            <td class="bafg-option-content">
                <ul>
                   <?php 
                    $bafg_no_overlay = !empty(get_post_meta( $post->ID, 'bafg_no_overlay', true )) ? get_post_meta( $post->ID, 'bafg_no_overlay', true ) : 'yes';
                    ?>
                    <li><input type="radio" name="bafg_no_overlay" id="bafg_no_overlay1" value="yes" <?php checked( $bafg_no_overlay, 'yes' ); ?>> <label for="bafg_no_overlay1"><?php echo esc_html__('Yes','bafg'); ?></label></li>
                    <li><input type="radio" name="bafg_no_overlay" id="bafg_no_overlay2" value="no" <?php checked( $bafg_no_overlay, 'no' ); ?>> <label for="bafg_no_overlay2"><?php echo esc_html__('No','bafg'); ?></label></li>
                </ul>
                <p><?php echo esc_html__('Show overlay on the before and after image.','bafg'); ?></p>
            </td>
        </tr>
        <tr class="bafg-skip-lazy-load">
            <td class="bafg-option-label"><label for="skip_lazy_load"><?php echo esc_html__('Skip lazy load','bafg'); ?></label></td>
            <td class="bafg-option-content">
                <ul>
                   <?php 
                    $skip_lazy_load = !empty(get_post_meta( $post->ID, 'skip_lazy_load', true )) ? get_post_meta( $post->ID, 'skip_lazy_load', true ) : 'yes';
                    ?>
                    <li><input type="radio" name="skip_lazy_load" id="skip_lazy_load1" value="yes" <?php checked( $skip_lazy_load, 'yes' ); ?>> <label for="skip_lazy_load1"><?php echo esc_html__('Yes','bafg'); ?></label></li>
                    <li><input type="radio" name="skip_lazy_load" id="skip_lazy_load2" value="no" <?php checked( $skip_lazy_load, 'no' ); ?>> <label for="skip_lazy_load2"><?php echo esc_html__('No','bafg'); ?></label></li>
                </ul>
                <p><?php echo esc_html__('Conflicting with lazy load? Try to skip lazy load.','bafg'); ?></p>
            </td>
        </tr>
    </table>
</div>

<div id="bafg_gallery_style" class="bafg-tabcontent">
    <table class="bafg-option-table">
        <tr class="bafg-before-label-bg">
            <td class="bafg-option-label">
                <label for="bafg_before_label_background"><?php echo esc_html__('Before Label Background','bafg'); ?></label>
            </td>
            <?php 
            $bafg_before_label_background = !empty(get_post_meta( $post->ID, 'bafg_before_label_background', true )) ? get_post_meta( $post->ID, 'bafg_before_label_background', true ) : '';
            ?>
            <td class="bafg-option-content"><input id="bafg_before_label_background" class="bafg-color-field" type="text" name="bafg_before_label_background" value="<?php echo esc_attr($bafg_before_label_background); ?>" /></td>
        </tr>
        <tr class="bafg-before-label-color" >
            <td class="bafg-option-label">
                <label for="bafg_before_label_color"><?php echo esc_html__('Before Text Color','bafg'); ?></label>
            </td>
            <?php 
            $bafg_before_label_color = !empty(get_post_meta( $post->ID, 'bafg_before_label_color', true )) ? get_post_meta( $post->ID, 'bafg_before_label_color', true ) : '';
            ?>
            <td class="bafg-option-content"><input id="bafg_before_label_color" class="bafg-color-field" type="text" name="bafg_before_label_color" value="<?php echo esc_attr($bafg_before_label_color); ?>" /></td>
        </tr>
		
       <!--Middel label color html(pro)-->
        <?php ob_start(); ?>
        <tr class="bafg-mid-label-bg">
            <td class="bafg-option-label">
                <label for="bafg_mid_label_background"><?php echo esc_html__('Middle Label Background','bafg'); ?><div class="bafg-tooltip"><span>?</span><div class="bafg-tooltip-info">Pro feature!</div>
                </div></label>
            </td>
            <td class="bafg-option-content"><input id="bafg_mid_label_background" class="bafg-color-field" type="text" name="bafg_mid_label_background" value="" /></td>
        </tr>
        <tr class="bafg-mid-label-color" >
            <td class="bafg-option-label">
                <label for="bafg_mid_label_color"><?php echo esc_html__('Middle label Color','bafg'); ?><div class="bafg-tooltip"><span>?</span><div class="bafg-tooltip-info">Pro feature!</div>
                </div></label>
            </td>
            <td class="bafg-option-content"><input id="bafg_mid_label_color" class="bafg-color-field" type="text" name="bafg_mid_label_color" value="" /></td>
        </tr>
		<?php 
		$middle_label_colors = ob_get_clean();
		echo apply_filters( 'bafg_middle_label_colors', $middle_label_colors, $post );
		?>
        <tr class="bafg-after-label-bg">
            <td class="bafg-option-label">
                <label for="bafg_after_label_background"><?php echo esc_html__('After Label Background','bafg'); ?></label>
            </td>
            <?php 
            $bafg_after_label_background = !empty(get_post_meta( $post->ID, 'bafg_after_label_background', true )) ? get_post_meta( $post->ID, 'bafg_after_label_background', true ) : '';
            ?>
            <td class="bafg-option-content"><input id="bafg_after_label_background" class="bafg-color-field" type="text" name="bafg_after_label_background" value="<?php echo esc_attr($bafg_after_label_background); ?>" /></td>
        </tr>
        <tr class="bafg-after-label-color">
            <td class="bafg-option-label">
                <label for="bafg_after_label_color"><?php echo esc_html__('After Text Color','bafg'); ?></label>
            </td>
            <?php 
            $bafg_after_label_color = !empty(get_post_meta( $post->ID, 'bafg_after_label_color', true )) ? get_post_meta( $post->ID, 'bafg_after_label_color', true ) : '';
            ?>
            <td class="bafg-option-content"><input id="bafg_after_label_color" class="bafg-color-field" type="text" name="bafg_after_label_color" value="<?php echo esc_attr($bafg_after_label_color); ?>" /></td>
        </tr>
        
        <?php
        ob_start();
        ?>
        <tr class="bafg-handle-color">
            <td class="bafg-option-label"><label for="bafg_handle_color"><?php echo esc_html__('Slider Handle Color','bafg'); ?><div class="bafg-tooltip"><span>?</span><div class="bafg-tooltip-info">Pro feature!</div>
                </div></label>
            </td>
            <td class="bafg-option-content"><input id="bafg_handle_color" class="bafg-color-field" type="text" name="bafg_handle_color" value="" /></td>
        </tr>
        <?php 
        $bafg_handle_color = ob_get_clean();
        echo apply_filters( 'bafg_handle_color', $bafg_handle_color, $post );
        ?>

        <?php
        ob_start();
        ?>
        <tr class="bafg-overlay-color">
            <td class="bafg-option-label"><label for="bafg_overlay_color"><?php echo esc_html__('Slider Overlay Color','bafg'); ?><div class="bafg-tooltip"><span>?</span><div class="bafg-tooltip-info">Pro feature!</div>
                </div></label>
            </td>
            <td class="bafg-option-content">
                <input id="bafg_overlay_color" data-palette="true" data-alpha="true" data-default-color="rgba(0, 0, 0, 0.5);" class="bafg-color-field" type="text" name="bafg_overlay_color" value="" />
                <br>
            </td>
        </tr>
        <?php 
        $bafg_overlay_color = ob_get_clean();
        echo apply_filters( 'bafg_overlay_color', $bafg_overlay_color, $post );
        ?>
        
        <?php
        ob_start();
        ?>
        <tr class="bafg-slider-width">
            <td class="bafg-option-label"><label for="bafg_custom_height_width"><?php echo esc_html__('Custom Width_Height','bafg'); ?><div class="bafg-tooltip"><span>?</span><div class="bafg-tooltip-info">Pro feature!</div>
                    </div></label>
            </td>
            <td class="bafg-option-content">
                <label for="bafg_width"><input style="width:100px" type="text" id="bafg_width" name="bafg_width" placeholder="100%"> Width</label>
                <br>
                <br>
                <label for="bafg_height"><input style="width:100px" type="text" id="bafg_height" name="bafg_height" placeholder="auto"> Height</label>
                
                <p><?php echo esc_html__('Set a specific height and width for this slider. (e.g: 100% or 400px)','bafg'); ?></p>
            </td>
        </tr>
        <?php 
        $bafg_custom_height_width = ob_get_clean();
        echo apply_filters( 'bafg_custom_height_width', $bafg_custom_height_width, $post );
        ?>
        
        <?php
        ob_start();
        ?>
        <tr class="bafg-slider-alignment">
            <td class="bafg-option-label"><label for="bafg_slider_alignment"><?php echo esc_html__('Alignment','bafg'); ?><div class="bafg-tooltip"><span>?</span><div class="bafg-tooltip-info">Pro feature!</div>
            </div></label>
            </td>
            <td class="bafg-option-content">
                <select name="bafg_slider_alignment" id="bafg_slider_alignment">
                    <option value=""><?php echo __( 'Default','bafg' ); ?> </option>
                    <option value="left"><?php echo __( 'Left','bafg' ); ?></option>
                    <option value="right"><?php echo __( 'Right','bafg' ); ?></option>
                    <option value="center"><?php echo __( 'Center','bafg' ); ?></option>
                </select>
            </td>
        </tr>
        <?php 
        $bafg_slider_alignment = ob_get_clean();
        echo apply_filters( 'bafg_slider_alignment', $bafg_slider_alignment, $post );
        ?>
        
        <tr class="bafg-row-heading">
            <td class="bafg-option-label"><label for=""><?php echo esc_html__('Heading','bafg'); ?></label>
            </td>
            <td class="bafg-option-content">
                <label for="bafg_slider_info_heading_font_size">Font Size</label><br>
                <input type="text" id="bafg_slider_info_heading_font_size" name="bafg_slider_info_heading_font_size" value="<?php echo esc_attr(get_post_meta($post->ID, 'bafg_slider_info_heading_font_size', true)); ?>"> (E.g: 22px)
                <br>
                <br>
                <label for="bafg_slider_info_heading_font_color">Color</label><br>
                <input type="text" id="bafg_slider_info_heading_font_color" name="bafg_slider_info_heading_font_color" class="bafg-color-field" value="<?php echo esc_attr(get_post_meta($post->ID, 'bafg_slider_info_heading_font_color', true)); ?>">
                <br>
                <br>
                <label for="bafg_slider_info_heading_alignment">Alignment</label><br>
                <?php 
                $bafg_slider_info_heading_alignment = !empty(get_post_meta( $post->ID, 'bafg_slider_info_heading_alignment', true )) ? get_post_meta( $post->ID, 'bafg_slider_info_heading_alignment', true ) : '';
                ?>
                <select id="bafg_slider_info_heading_alignment" name="bafg_slider_info_heading_alignment">
                    <option value="">Default</option>
                    <option value="left" <?php selected( 'left', $bafg_slider_info_heading_alignment ); ?>>Left</option>
                    <option value="right" <?php selected( 'right', $bafg_slider_info_heading_alignment ); ?>>Right</option>
                    <option value="center" <?php selected( 'center', $bafg_slider_info_heading_alignment ); ?>>Center</option>
                </select>
            </td>
        </tr>

	<tr class="bafg-row-desc">
		<td class="bafg-option-label"><label for=""><?php echo esc_html__('Description','bafg'); ?></label>
		</td>
		<td class="bafg-option-content">
			<label for="bafg_slider_info_desc_font_size">Font Size</label><br>
			<input type="text" id="bafg_slider_info_desc_font_size" name="bafg_slider_info_desc_font_size" value="<?php echo esc_attr(get_post_meta($post->ID, 'bafg_slider_info_desc_font_size', true)); ?>"> (E.g: 15px)
			<br>
			<br>
			<label for="bafg_slider_info_desc_font_color">Color</label><br>
			<input type="text" id="bafg_slider_info_desc_font_color" name="bafg_slider_info_desc_font_color" class="bafg-color-field" value="<?php echo esc_attr(get_post_meta($post->ID, 'bafg_slider_info_desc_font_color', true)); ?>">
			<br>
            <br>
            <label for="bafg_slider_info_desc_alignment">Alignment</label><br>
            <?php 
            $bafg_slider_info_desc_alignment = !empty(get_post_meta( $post->ID, 'bafg_slider_info_desc_alignment', true )) ? get_post_meta( $post->ID, 'bafg_slider_info_desc_alignment', true ) : '';
            ?>
            <select id="bafg_slider_info_desc_alignment" name="bafg_slider_info_desc_alignment">
                <option value="">Default</option>
                <option value="left" <?php selected( 'left', $bafg_slider_info_desc_alignment ); ?>>Left</option>
                <option value="right" <?php selected( 'right', $bafg_slider_info_desc_alignment ); ?>>Right</option>
                <option value="center" <?php selected( 'center', $bafg_slider_info_desc_alignment ); ?>>Center</option>
            </select>
		</td>
	</tr>

	<tr class="bafg-row-read-more">
		<td class="bafg-option-label"><label for=""><?php echo esc_html__('Read More Button','bafg'); ?></label>
		</td>
		<td class="bafg-option-content">
			<label for="bafg_slider_info_readmore_font_size">Font Size</label><br>
			<input type="text" id="bafg_slider_info_readmore_font_size" name="bafg_slider_info_readmore_font_size" value="<?php echo esc_attr(get_post_meta($post->ID, 'bafg_slider_info_readmore_font_size', true)); ?>"> (E.g: 15px)
			<br>
			<br>
			<label for="bafg_slider_info_readmore_font_color">Color</label><br>
			<input type="text" id="bafg_slider_info_readmore_font_color" name="bafg_slider_info_readmore_font_color" value="<?php echo esc_attr(get_post_meta($post->ID, 'bafg_slider_info_readmore_font_color', true)); ?>" class="bafg-color-field">
			<br>
			<br>
			<label for="bafg_slider_info_readmore_bg_color">Background Color</label><br>
			<input type="text" class="bafg-color-field" id="bafg_slider_info_readmore_bg_color" name="bafg_slider_info_readmore_bg_color" value="<?php echo esc_attr(get_post_meta($post->ID, 'bafg_slider_info_readmore_bg_color', true)); ?>">
			<br>
			<br>
			<label for="bafg_slider_info_readmore_hover_font_color">Hover Color</label><br>
			<input type="text" class="bafg-color-field" id="bafg_slider_info_readmore_hover_font_color" name="bafg_slider_info_readmore_hover_font_color" value="<?php echo esc_attr(get_post_meta($post->ID, 'bafg_slider_info_readmore_hover_font_color', true)); ?>">
			
			<br>
			<br>
			<label for="bafg_slider_info_readmore_hover_bg_color">Hover Background Color</label><br>
			<input type="text" class="bafg-color-field" id="bafg_slider_info_readmore_hover_bg_color" name="bafg_slider_info_readmore_hover_bg_color" value="<?php echo esc_attr(get_post_meta($post->ID, 'bafg_slider_info_readmore_hover_bg_color', true)); ?>">
			
			<br>
			<br>
			<label for="bafg_slider_info_readmore_button_padding_top_bottom">Padding Top Bottom</label><br>
			<?php 
            $bafg_slider_info_readmore_button_padding_top_bottom = !empty(get_post_meta( $post->ID, 'bafg_slider_info_readmore_button_padding_top_bottom', true )) ? get_post_meta( $post->ID, 'bafg_slider_info_readmore_button_padding_top_bottom', true ) : '';
            ?>
            <input id="bafg_slider_info_readmore_button_padding_top_bottom" type="text" name="bafg_slider_info_readmore_button_padding_top_bottom" value="<?php echo esc_attr($bafg_slider_info_readmore_button_padding_top_bottom); ?>"> (E.g: 15px) 
            
            <br>
			<br>
			<label for="bafg_slider_info_readmore_button_padding_left_right">Padding Left Right</label><br>
			<?php 
            $bafg_slider_info_readmore_button_padding_left_right = !empty(get_post_meta( $post->ID, 'bafg_slider_info_readmore_button_padding_left_right', true )) ? get_post_meta( $post->ID, 'bafg_slider_info_readmore_button_padding_left_right', true ) : '';
            ?>
            <input id="bafg_slider_info_readmore_button_padding_left_right" type="text" name="bafg_slider_info_readmore_button_padding_left_right" value="<?php echo esc_attr($bafg_slider_info_readmore_button_padding_left_right); ?>"> (E.g: 15px) 
            
            <br>
			<br>
			<label for="bafg_slider_info_readmore_border_radius">Border Radius</label><br>
			<?php 
            $bafg_slider_info_readmore_border_radius = !empty(get_post_meta( $post->ID, 'bafg_slider_info_readmore_border_radius', true )) ? get_post_meta( $post->ID, 'bafg_slider_info_readmore_border_radius', true ) : '';
            ?>
            <input id="bafg_slider_info_readmore_border_radius" type="text" name="bafg_slider_info_readmore_border_radius" value="<?php echo esc_attr($bafg_slider_info_readmore_border_radius); ?>"> (E.g: 15px) 
			
			<br>
			<br>
			<label for="bafg_slider_info_readmore_button_width">Button Width</label><br>
			<?php 
            $bafg_slider_info_readmore_button_width = !empty(get_post_meta( $post->ID, 'bafg_slider_info_readmore_button_width', true )) ? get_post_meta( $post->ID, 'bafg_slider_info_readmore_button_width', true ) : '';
            ?>
            <select id="bafg_slider_info_readmore_button_width" name="bafg_slider_info_readmore_button_width">
                <option value="">Default</option>
                <option value="full-width" <?php selected( 'full-width', $bafg_slider_info_readmore_button_width ); ?>>Full Width</option>
            </select>
			
			<br>
            <br>
            <div class="bafg_slider_info_readmore_alignment">
				<label for="bafg_slider_info_readmore_alignment">Alignment</label><br>
				<?php 
				$bafg_slider_info_readmore_alignment = !empty(get_post_meta( $post->ID, 'bafg_slider_info_readmore_alignment', true )) ? get_post_meta( $post->ID, 'bafg_slider_info_readmore_alignment', true ) : '';
				?>
				<select id="bafg_slider_info_readmore_alignment" name="bafg_slider_info_readmore_alignment">
					<option value="">Default</option>
					<option value="left" <?php selected( 'left', $bafg_slider_info_readmore_alignment ); ?>>Left</option>
					<option value="right" <?php selected( 'right', $bafg_slider_info_readmore_alignment ); ?>>Right</option>
					<option value="center" <?php selected( 'center', $bafg_slider_info_readmore_alignment ); ?>>Center</option>
				</select>
			</div>
		</td>
	</tr>
        
    </table>
</div>
<?php
	// Noncename needed to verify where the data originated
	wp_nonce_field( 'bafg_meta_box_nonce', 'bafg_meta_box_noncename' );
?>
<?php

$contents = ob_get_clean();

echo apply_filters( 'bafg_meta_fields', $contents, $post );

}
}

//Metabox shortcode
function bafg_shortcode_callback(){
    $bafg_scode = isset($_GET['post']) ? '[bafg id="'.$_GET['post'].'"]' : '';
    ?>
    <input type="text" name="bafg_display_shortcode" class="bafg_display_shortcode" value="<?php echo esc_attr($bafg_scode); ?>" readonly onclick="bafgCopyShortcode()">
    <?php
}

//Metabox shortcode
function bafg_blackfriday(){
    ?>
    <style>
	#bafg_blackfriday .postbox-header {
		display: none;
	}	
	</style>
    <a target="_blank" href="https://themefic.com/black-friday/"><img style="max-width: 100%;" src="<?php echo plugin_dir_url( __FILE__ ); ?>../BLACK_FRIDAY_BACKGROUND_GRUNGE.png" alt=""></a>
    <?php
}

//save meta value with save post hook
add_action('save_post', 'save_post');
function save_post ( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( ! isset( $_POST[ 'bafg_meta_box_noncename' ] ) || ! wp_verify_nonce( $_POST['bafg_meta_box_noncename'], 'bafg_meta_box_nonce' ) )
        return;

    if ( ! current_user_can( 'edit_posts' ) )
        return;

    if( isset($_POST['bafg_before_image']) ){
        update_post_meta( $post_id, 'bafg_before_image', esc_url_raw( $_POST['bafg_before_image'] ) );
    }
    if( isset($_POST['bafg_after_image']) ){
        update_post_meta( $post_id, 'bafg_after_image', esc_url_raw( $_POST['bafg_after_image'] ) );
    }

    if( isset($_POST['bafg_before_after_style']) ){
        update_post_meta( $post_id, 'bafg_before_after_style', sanitize_text_field( $_POST['bafg_before_after_style'] ) );
    }
	
    if( isset($_POST['bafg_slider_title']) ){
        update_post_meta( $post_id, 'bafg_slider_title', sanitize_text_field( $_POST['bafg_slider_title'] ) );
    }
	
    if( isset($_POST['bafg_slider_description']) ){
        update_post_meta( $post_id, 'bafg_slider_description', sanitize_text_field( $_POST['bafg_slider_description'] ) );
    }
	
    if( isset($_POST['bafg_readmore_link']) ){
        update_post_meta( $post_id, 'bafg_readmore_link', esc_url_raw( $_POST['bafg_readmore_link'] ) );
    }
	
    if( isset($_POST['bafg_readmore_link_target']) ){
        update_post_meta( $post_id, 'bafg_readmore_link_target', esc_attr( $_POST['bafg_readmore_link_target'] ) );
    }
	
    if( isset($_POST['bafg_image_styles']) ){
        update_post_meta( $post_id, 'bafg_image_styles', esc_attr( $_POST['bafg_image_styles'] ) );
    }
    if( isset($_POST['bafg_default_offset']) ){
        update_post_meta( $post_id, 'bafg_default_offset', esc_attr( $_POST['bafg_default_offset'] ) );
    }
    
    if( isset($_POST['bafg_before_label']) ){
        update_post_meta( $post_id, 'bafg_before_label', esc_attr( $_POST['bafg_before_label'] ) );
    }
    
    if( isset($_POST['bafg_mid_label']) ){
        update_post_meta( $post_id, 'bafg_mid_label', esc_attr( $_POST['bafg_mid_label'] ) );
    }
    
    if( isset($_POST['bafg_after_label']) ){
        update_post_meta( $post_id, 'bafg_after_label', esc_attr( $_POST['bafg_after_label'] ) );
    }
    if( isset($_POST['bafg_move_slider_on_hover']) ){
        update_post_meta( $post_id, 'bafg_move_slider_on_hover', esc_attr( $_POST['bafg_move_slider_on_hover'] ) );
    }
    
    if( isset($_POST['bafg_click_to_move']) ){
        update_post_meta( $post_id, 'bafg_click_to_move', esc_attr( $_POST['bafg_click_to_move'] ) );
    }
    
    if( isset($_POST['bafg_no_overlay']) ){
        update_post_meta( $post_id, 'bafg_no_overlay', esc_attr( $_POST['bafg_no_overlay'] ) );
    }
    
    if( isset($_POST['bafg_before_label_background']) ){
        update_post_meta( $post_id, 'bafg_before_label_background', esc_attr( $_POST['bafg_before_label_background'] ) );
    }
    
    if( isset($_POST['bafg_before_label_color']) ){
        update_post_meta( $post_id, 'bafg_before_label_color', esc_attr( $_POST['bafg_before_label_color'] ) );
    }
    
    if( isset($_POST['bafg_after_label_background']) ){
        update_post_meta( $post_id, 'bafg_after_label_background', esc_attr( $_POST['bafg_after_label_background'] ) );
    }
    
    if( isset($_POST['bafg_after_label_color']) ){
        update_post_meta( $post_id, 'bafg_after_label_color', esc_attr( $_POST['bafg_after_label_color'] ) );
    }
	
	if( isset($_POST['bafg_slider_info_heading_font_size']) ){
        update_post_meta( $post_id, 'bafg_slider_info_heading_font_size', esc_attr( $_POST['bafg_slider_info_heading_font_size'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_heading_font_color']) ){
        update_post_meta( $post_id, 'bafg_slider_info_heading_font_color', esc_attr( $_POST['bafg_slider_info_heading_font_color'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_heading_alignment']) ){
        update_post_meta( $post_id, 'bafg_slider_info_heading_alignment', esc_attr( $_POST['bafg_slider_info_heading_alignment'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_desc_font_size']) ){
        update_post_meta( $post_id, 'bafg_slider_info_desc_font_size', esc_attr( $_POST['bafg_slider_info_desc_font_size'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_desc_font_color']) ){
        update_post_meta( $post_id, 'bafg_slider_info_desc_font_color', esc_attr( $_POST['bafg_slider_info_desc_font_color'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_desc_alignment']) ){
        update_post_meta( $post_id, 'bafg_slider_info_desc_alignment', esc_attr( $_POST['bafg_slider_info_desc_alignment'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_readmore_font_size']) ){
        update_post_meta( $post_id, 'bafg_slider_info_readmore_font_size', esc_attr( $_POST['bafg_slider_info_readmore_font_size'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_readmore_font_color']) ){
        update_post_meta( $post_id, 'bafg_slider_info_readmore_font_color', esc_attr( $_POST['bafg_slider_info_readmore_font_color'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_readmore_bg_color']) ){
        update_post_meta( $post_id, 'bafg_slider_info_readmore_bg_color', esc_attr( $_POST['bafg_slider_info_readmore_bg_color'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_readmore_hover_font_color']) ){
        update_post_meta( $post_id, 'bafg_slider_info_readmore_hover_font_color', esc_attr( $_POST['bafg_slider_info_readmore_hover_font_color'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_readmore_hover_bg_color']) ){
        update_post_meta( $post_id, 'bafg_slider_info_readmore_hover_bg_color', esc_attr( $_POST['bafg_slider_info_readmore_hover_bg_color'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_readmore_button_width']) ){
        update_post_meta( $post_id, 'bafg_slider_info_readmore_button_width', esc_attr( $_POST['bafg_slider_info_readmore_button_width'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_readmore_button_padding_top_bottom']) ){
        update_post_meta( $post_id, 'bafg_slider_info_readmore_button_padding_top_bottom', esc_attr( $_POST['bafg_slider_info_readmore_button_padding_top_bottom'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_readmore_border_radius']) ){
        update_post_meta( $post_id, 'bafg_slider_info_readmore_border_radius', esc_attr( $_POST['bafg_slider_info_readmore_border_radius'] ) );
    }
	
    if( isset($_POST['bafg_slider_info_readmore_button_padding_left_right']) ){
        update_post_meta( $post_id, 'bafg_slider_info_readmore_button_padding_left_right', esc_attr( $_POST['bafg_slider_info_readmore_button_padding_left_right'] ) );
    }
    
    if( isset($_POST['bafg_slider_info_readmore_alignment']) ){
        update_post_meta( $post_id, 'bafg_slider_info_readmore_alignment', esc_attr( $_POST['bafg_slider_info_readmore_alignment'] ) );
    }
    
    if( isset($_POST['skip_lazy_load']) ){
        update_post_meta( $post_id, 'skip_lazy_load', esc_attr( $_POST['skip_lazy_load'] ) );
    }
    if( isset($_POST['before_img_alt']) ){
        update_post_meta( $post_id, 'before_img_alt', esc_attr( $_POST['before_img_alt'] ) );
    }
    if( isset($_POST['after_img_alt']) ){
        update_post_meta( $post_id, 'after_img_alt', esc_attr( $_POST['after_img_alt'] ) );
    }
    
    do_action( 'bafg_save_post_meta', $post_id );

}
