<?php
/**
 * REST API handlers and related functionality.
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
 * Handles REST API modifications
 */

class AdSanity_Rest_API {

	function __construct() {}

	/**
	 * The hooks for this class
	 */
	public static function hooks() {

		add_action( 'pre_get_posts', array( __CLASS__, 'remove_expired_ads' ), 10, 1 );
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_fields' ) );

	}

	/**
	 * Add a meta_query to ads in REST queries
	 */
	public static function remove_expired_ads( $query ) {

		// Only run on REST API Requests.
		if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
			return;
		}

		if (
			! isset( $query->query, $query->query['post_type'] ) ||
			'ads' !== $query->query['post_type']
		) {
			return;
		}

		$now = time();

		$query->set(
			'meta_query',
			array(
				array(
					'key' => '_end_date',
					'value' => $now,
					'type' => 'numeric',
					'compare' => '>=',
				),
			)
		);

	}


	/**
	 * Add ad rendered field to REST
	 *
	 * @return void
	 */
	public static function register_rest_fields() {

		register_rest_field(
			'ads',
			'rendered_ad',
			array(
				'get_callback' => array( __CLASS__, 'rendered_rest_field_cb' ),
				'schema'       => null,
			)
		);

		register_rest_field(
			'ads',
			'ad_type',
			array(
				'get_callback' => array( __CLASS__, 'ad_type_rest_field_cb' ),
				'schema'       => null,
			)
		);

		register_rest_field(
			'ads',
			'ad_size',
			array(
				'get_callback' => array( __CLASS__, 'ad_size_rest_field_cb' ),
				'schema'       => null,
			)
		);

		register_rest_field(
			'ad-group',
			'ad_ids',
			array(
				'get_callback' => array( __CLASS__, 'ad_group_ids_rest_field_cb' ),
				'schema'       => null,
			)
		);

	}

	/**
	 * Callback for rendered ad rest field
	 *
	 * @param object $object a post object
	 * @return string a rendered single ad
	 */
	public static function rendered_rest_field_cb( $object ) {

		$track_this = isset( $_REQUEST['track_this'] ) && $_REQUEST['track_this'] ? true : false;

		return adsanity_show_ad( array(
			'return'     => true,
			'post_id'    => intval( $object['id'] ),
			'track_this' => $track_this,
		) );

	}

	/**
	 * Callback for ad type rest field
	 *
	 * @param object $object a post object
	 * @return string the ad type (external or self-hosted)
	 */
	public static function ad_type_rest_field_cb( $object ) {

		$post_id = $object['id'];
		$ad_code = Adsanity\Meta_Data::get( 'post', $post_id, '_code', true );

		if ( $ad_code && '' !== $ad_code ) {
			return 'external';
		}

		return 'self-hosted';

	}

	/**
	 * Callback for ad size rest field
	 *
	 * @param object $object a post object
	 * @return string the ad type (size or 'none')
	 */
	public static function ad_size_rest_field_cb( $object ) {

		$post_id = $object['id'];
		$size = Adsanity\Meta_Data::get( 'post', $post_id, '_size', true );

		if ( $size && $size !== '' ) {
			return $size;
		}

		return 'none';

	}

	/**
	 * Return the ids of ads in a group
	 *
	 * @param object $object ad group object
	 * @return array array of ad ids in this ad group
	 */
	public static function ad_group_ids_rest_field_cb( $object ) {

		$group_id = $object['id'];

		$per_page = -1;
		if ( array_key_exists( 'per_page', $_GET ) ) {
			$per_page = $_GET['per_page'];
		}

		$now = time();

		$ad_ids = get_posts(
			array(
				'posts_per_page' => $per_page,
				'post_type'      => 'ads',
				'fields'         => 'ids',
				'orderby'        => 'rand',
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery
					array(
						'taxonomy' => 'ad-group',
						'field'    => 'term_id',
						'terms'    => $group_id,
					),
				),
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery
					array(
						'key'     => '_start_date',
						'value'   => $now,
						'type'    => 'numeric',
						'compare' => '<=',
					),
					array(
						'key'     => '_end_date',
						'value'   => $now,
						'type'    => 'numeric',
						'compare' => '>=',
					),
				),
			)
		);
		return $ad_ids;

	}

}

AdSanity_Rest_API::hooks();
