<?php
/**
 * Functionality related to HTML5 Ads.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

/**
 * AdSanityHTML5
 *
 * Contains all helper methods for AdSanity HTML5 Ads.
 *
 * @pkg   AdSanity
 * @since 1.6
 */
class AdSanityHTML5 {

	/**
	 * Kicks off all actions and filters
	 */
	public static function hooks() {

		add_action( 'add_meta_boxes_ads', array( __CLASS__, 'meta_box' ), 1 );
		add_action( 'wp_ajax_adsanity_html5_upload', array( __CLASS__, 'ajax_upload' ) );

	}

	/**
	 * Adds a metabox for HTML5 Ad Upload
	 */
	public static function meta_box( $post ) {

		add_meta_box(
			'ad-upload',
			__( 'Ad Upload', 'adsanity' ),
			array( __CLASS__, 'ad_upload_metabox' ),
			'ads',
			'normal',
			'high'
		);

	}

	/**
	 * The view for ad upload metabox
	 */
	public static function ad_upload_metabox( $post ) {

		require_once( ADSANITY_VIEWS . 'html5-upload.php' );

	}

	/**
	 * Handles the zip file upload via ajax
	 */
	public static function ajax_upload() {

		// Check the nonce
		if ( ! current_user_can( 'edit_posts' ) ||
			 ! array_key_exists( 'security', $_POST ) ||
			 ! wp_verify_nonce( $_POST['security'], 'adsanity-html5-upload' ) ) {
			wp_send_json_error( 'Security' );
			return;
		}

		// Check that all of the parts are here
		if ( ! array_key_exists( 'file_upload', $_FILES ) ||
			 ! array_key_exists( 'tmp_name', $_FILES['file_upload'] ) ||
			 ! array_key_exists( 'type', $_FILES['file_upload'] ) ||
			 ! array_key_exists( 'ad_id', $_POST ) ) {
			wp_send_json_error( 'Request is missing parts' );
			return;
		}

		// Possible mime type for a zip file
		$allowed_mime_types = array(
			'application/zip',
			'application/octet-stream',
			'application/x-zip-compressed',
			'multipart/x-zip',
		);

		if ( ! in_array( $_FILES['file_upload']['type'], $allowed_mime_types ) ) {
			wp_send_json_error( [
				'message' => esc_js( __( 'Invalid filetype.', 'adsanity' ) ),
			], 400 );
			return;
		}

		$upload_dir = wp_upload_dir();
		$upload_base_dir = $upload_dir['basedir'];
		$adsanity_dir = trailingslashit( $upload_base_dir ) . 'adsanity/';
		$ad_id = $_POST['ad_id'];
		$ad_dir = $adsanity_dir . $ad_id;

		// Create the ad src for use in meta
		$ad_src = trailingslashit( trailingslashit( $upload_dir['baseurl'] ) . 'adsanity/' . $ad_id );

		// Get the path to this ad
		$ad_path = trailingslashit( trailingslashit( $upload_dir['basedir'] ) . 'adsanity/' . $ad_id );

		/**
		 * Initialize WP_Filesystem
		 * Used for unzip_file and rmdir
		 */
		global $wp_filesystem;
		WP_Filesystem();

		// Check to see if there has already been an upload to this folder
		if ( is_dir( $ad_path ) ) {
			// Remove the folder
			$wp_filesystem->rmdir( $ad_path, true );
		}

		// Unzip the files
		$unzipped = unzip_file( $_FILES['file_upload']['tmp_name'], $ad_dir );

		if ( ! $unzipped ) {
			wp_send_json_error( 'File upload error', 500 );
			return;
		}

		// Scan the directory for this ad
		$scanned = scandir( $ad_path );

		/**
		 * Check to see if this only contains a directory
		 * HTML5 ads can be uploaded as a zip file
		 * of only files, or a zipped folder
		 */
		while (
			3 === count( $scanned ) &&
			is_dir( $ad_path . trailingslashit( $scanned[2] ) ) &&
			! in_array( 'index.html', $scanned )
		) {
			// Update the path and src with the new inner folder added
			$ad_path .= trailingslashit( $scanned[2] );
			$ad_src .= trailingslashit( $scanned[2] );
			// Re-scan
			$scanned = scandir( $ad_path );
		}

		// Check for the existence of an index.html file
		if ( ! in_array( 'index.html', $scanned ) ) {
			wp_send_json_error( [
				'message' => esc_js( __( 'Zip file must contain an index.html file.', 'adsanity' ) ),
			], 400 );
		}

		Adsanity\Meta_Data::update( 'post', $ad_id, 'ad_src', $ad_src );

		wp_send_json_success( array( 'src' => $ad_src ) );

	}

}

if ( is_admin() ) {
	AdSanityHTML5::hooks();
}
