<?php

/**
 * Custom Post Type add Subpage to Custom Post Menu
 */

add_action( 'admin_menu', 'bafg_submenu_settings_page' );

//admin_menu callback function

function bafg_submenu_settings_page() {

    add_submenu_page(
        'edit.php?post_type=bafg', //$parent_slug
        __( 'Settings', 'bafg-pro' ), //$page_title
        __( 'Settings', 'bafg-pro' ), //$menu_title
        'manage_options', //$capability
        'bafg_settings', //$menu_slug
        'bafg_settings_page_callback', //$function
        3
    );

}

//add_submenu_page callback function
function bafg_settings_page_callback() {
    ?>
<div class="wrap">
    <h1><?php _e( 'Before After Global Settings','bafg' ); ?></h1>
    <?php settings_errors();?>
    <!--Tab buttons start-->
    <div class="bafg-setting-tab">
        <a class="bafg-tablinks active" onclick="bafg_option_tab(event, 'bafg-watermark')"><?php _e( 'Watermark', 'bafg' );?></a>
        <a class="bafg-tablinks" onclick="bafg_option_tab(event, 'bafg-tools')"><?php _e( 'Tools', 'bafg' );?></a>
        <a class="bafg-tablinks" onclick="bafg_option_tab(event, 'bafg-doc')"><?php _e( 'Documentation', 'bafg' );?></a>
        <?php do_action( 'bafg_admin_tab' );?>
    </div>
    <!--Tab buttons end-->
    <?php ob_start(); ?>
    <div id="bafg-watermark" class="bafg-tabcontent" style="display: block;">
        <form method="post" action="options.php">
            <?php
                settings_fields( 'bafg-global-settings' );
                do_settings_sections( 'bafg_settings' );
                submit_button();
            ?>
        </form>
    </div>
    <?php
    $demo_html = ob_get_clean();
    echo apply_filters( 'bafg_watermark_options_tab_watermark', $demo_html );
    ?>
    <div id="bafg-tools" class="bafg-tabcontent">
        <?php
        echo get_option('bafg_debug_mode');
        ?>
        <form method="post" action="options.php">
            <?php
                settings_fields( 'bafg-global-settings-tools' );
                do_settings_sections( 'bafg_settings_tools' );
                submit_button();
            ?>
        </form>
    </div>
    <div id="bafg-doc" class="bafg-tabcontent" style="padding:20px 10px">
        <a href="https://themefic.com/docs/beaf" target="_blank"><?php echo esc_html__( 'Please click here to visit the Documentation page.', 'bafg' ); ?></a>
    </div>
</div>

<?php
}

function bafg_register_settings() {
    register_setting(
        'bafg-global-settings', // $option_group
        'bafg_watermark', // $option_name
        'bafg_sanitize_global_options' //sanitize callback
    );
    register_setting(
        'bafg-global-settings-tools', // $option_group
        'bafg_tools', // $option_name
        'bafg_sanitize_global_option_tools' //sanitize callback
    );
    add_settings_section(
        'bafg_global_option_header', // Section $id
        __( 'General', 'bafg' ),
        'bafg_general_sections_callback', // Callback
        'bafg_settings' // Settings Page Slug
    );
    add_settings_section(
        'bafg_global_option_tools', // Section $id
        __( 'Debugging', 'bafg' ),
        'bafg_tools_sections_callback', // Callback
        'bafg_settings_tools' // Settings Page Slug
    );

    add_settings_field(
        'enable_watermark', // Field $id
        __( 'Enable Watermark', 'bafg' ), // Setting $title
        'bafg_enable_watermark_callback',
        'bafg_settings', // Settings Page Slug
        'bafg_global_option_header' // Section $id
    );
    add_settings_field(
        'enable_popup', // Field $id
        __( 'Enable Full Screen Popup', 'bafg' ), // Setting $title
        'bafg_enable_popup_preview_callback',
        'bafg_settings', // Settings Page Slug
        'bafg_global_option_header' // Section $id
    );
    add_settings_field(
        'path', // Field $id
        __( 'Watermark Image Upload', 'bafg' ), // Setting $title
        'bafg_watermark_upload_callback',
        'bafg_settings', // Settings Page Slug
        'bafg_global_option_header' // Section $id
    );
    add_settings_field(
        'prev', // Field $id
        __( 'Watermark Image', 'bafg' ), // Setting $title
        'bafg_watermark_prev_callback',
        'bafg_settings', // Settings Page Slug
        'bafg_global_option_header' // Section $id
    );
    add_settings_field(
        'bafg_attachment_id',
        "",
        'bafg_attachment_id_callback',
        'bafg_settings',
        'bafg_global_option_header'
    );
    add_settings_field(
        'wm_opacity_enable',
        __( 'Enable Opacity', 'bafg' ),
        'bafg_enable_wm_opacity_callback',
        'bafg_settings',
        'bafg_global_option_header'
    );
    add_settings_field(
        'wm_opacity',
        __( 'Watermark Opacity (Required PNG-8 image)', 'bafg' ),
        'bafg_wm_opacity_callback',
        'bafg_settings',
        'bafg_global_option_header'
    );
    add_settings_field(
        'bafg_debug_mode',
        __( 'Enable Debug Mode', 'bafg' ),
        'bafg_debug_mode_callback',
        'bafg_settings_tools',
        'bafg_global_option_tools'
    );

}
add_action( 'admin_init', 'bafg_register_settings' );

function bafg_sanitize_global_options( $input ){

    $sanitary_values = array();

    if ( isset( $input['enable_watermark'] ) ) {
        $sanitary_values['enable_watermark'] = $input['enable_watermark'];
    }

    if ( isset( $input['bafg_attachment_id'] ) ) {
        $sanitary_values['bafg_attachment_id'] = $input['bafg_attachment_id'];
    }
    if ( isset( $input['prev'] ) ) {
        $sanitary_values['prev'] = $input['prev'];
    }

    if ( isset( $input['path'] ) ) {
        $sanitary_values['path'] = $input['path'];
    }

    if ( isset( $input['wm_opacity'] ) ) {
        $sanitary_values['wm_opacity'] = $input['wm_opacity'];
    }
    if ( isset( $input['wm_opacity_enable'] ) ) {
        $sanitary_values['wm_opacity_enable'] = $input['wm_opacity_enable'];
    }

    return apply_filters( 'bafg_save_global_option', $sanitary_values, $input );
}

function bafg_sanitize_global_option_tools( $input ){

    $sanitary_values = array();

    if ( isset( $input['enable_debug_mode'] ) ) {
        $sanitary_values['enable_debug_mode'] = $input['enable_debug_mode'];
    }

    return apply_filters( 'bafg_save_global_option_tools', $sanitary_values, $input );
}

//callback functions for options
function bafg_general_sections_callback() {
    echo '';

}
function bafg_tools_sections_callback() {
    //
}
function bafg_watermark_upload_callback() {
    
    $attach_id = bafg_option_value('bafg_attachment_id');
    echo '
    <input class="bafg-watermark-path" type="text" placeholder="Image URL" value="' . get_attached_file( $attach_id )  . '" name="bafg_watermark[path]">
    <input type="button" class="button button-primary bafg-watermark-upload" value="Add/Upload"> '
    ;

}

function bafg_enable_watermark_callback(){

    ob_start();
    printf(
        '<input type="checkbox" disabled name="" id="bafg_enable_watermark" checked ><span style="color:red;font-weight:bold" class="bafg-pro-tt">Pro addon<span>'
    );
    $enable_watermark_image = ob_get_clean();
    echo apply_filters('bafg_enable_watermark_image',$enable_watermark_image);

}
function bafg_enable_popup_preview_callback(){
    ob_start();
    printf(
        '<input type="checkbox" disabled name="" id="bafg_popup_preview" checked ><span style="color:red;font-weight:bold" class="bafg-pro-tt">Pro addon<span>'
    );
    $enable_popup_preview = ob_get_clean();
    echo apply_filters('bafg_popup_preview',$enable_popup_preview);

    
}

function bafg_attachment_id_callback(){
    ob_start();
    printf(
        '<input type="hidden" class="bafg-attachment-id" value="/var/root/...." name="bafg_watermark[bafg_attachment_id]">'
    );
    $bafg_wm_image_path = ob_get_clean();
    echo apply_filters('bafg_watermark_image_path',$bafg_wm_image_path);
}
function bafg_watermark_prev_callback() {
    echo '
    <input type="hidden" class="bafg-watermark-prev-url"  name="bafg_watermark[prev]" value="' . bafg_option_value( "prev" ) . '">
    <img src="' .  bafg_option_value( "prev" ) . '" class="bafg-watermark-prev" type="text">';

}

function bafg_enable_wm_opacity_callback(){

    ob_start();
    printf(
        '<input type="checkbox" disabled name="" id="bafg_enable_wm_opacity" checked >'
    );
    $enable_wm_img_opacity = ob_get_clean();
    echo apply_filters( 'bafg_enable_wm_opacity',$enable_wm_img_opacity );

    
}
function bafg_wm_opacity_callback(){

    printf(
        '<input type="range" min="1" max="100" class="bafg-wm-range" id="bafg-wm-opacity" value="'. bafg_option_value('wm_opacity') .'" name="bafg_watermark[wm_opacity]">
         <span class="bafg-wm-range-val">'. bafg_option_value('wm_opacity') .'</span>'
    );
}
function bafg_debug_mode_callback(){
    $debug_mode = is_array(get_option('bafg_tools')) ? get_option('bafg_tools')['enable_debug_mode'] : '';
    $checked = '';
    if( !empty($debug_mode) ){
        $checked = 'checked';
    }
    printf(
        '<input type="checkbox" class="bafg-debug_mode" id="bafg-debug_mode" name="bafg_tools[enable_debug_mode]" %s>
        <span>'.esc_html__('Debug mode allows you to troubleshoot conflicts with the theme or other plugins.','bafg').'</span>', $checked
    );
}