<?php
/**
 * The HTML5 Upload view.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}
?>

<div id="adsanity-invalid-html5-type" class="error notice is-dismissible" style="display:none;">
	<p><strong><?php printf( esc_html__( 'Only files of type %s may be uploaded.', 'adsanity' ), '<code>zip</code>' ); ?></strong></p>
</div>

<div id="adsanity-html5-upload" class="error notice is-dismissible" style="display:none;">
	<p><strong><?php esc_html_e( 'Oops! Something went wrong with the file upload.', 'adsanity' ); ?></strong>&nbsp;</p>
</div>

<h3><?php printf( esc_html__( '%s File Upload', 'adsanity' ), 'HTML5' ); ?></h3>

<p><?php printf( esc_html__( 'If you have an %s ad in a %s format, you may install it by uploading it here.' ), 'HTML5', '.zip' ); ?></p>

<?php wp_nonce_field( 'adsanity-html5-upload', 'html5nonce' ); ?>
<label class="screen-reader-text" for="html5zip"><?php printf( esc_html__( '%s ad zip file', 'adsanity' ), 'HTML5' ); ?></label>
<input type="file" id="html5zip" name="html5zip" />

<button class="button button-primary" id="install-html5-upload" disabled>
	<?php esc_html_e( 'Upload Now', 'adsanity' ) ?>
</button>

<span class="spinner"></span>

<br/>

<?php
// Get ad src
global $post;
$ad_src = Adsanity\Meta_Data::get( 'post', $post->ID, 'ad_src', true );
if ( $ad_src ) { ?>
	<iframe src="<?php echo esc_attr( trailingslashit( $ad_src ) ); ?>" scrolling="no" frameborder="0"></iframe>
<?php } ?>
