<?php
/**
 * Utility functions for accessing meta data in a standardized way.
 *
 * @package WordPress
 * @subpackage AdSanity
 */

namespace Adsanity;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

/**
 * Access Meta Data in a common way across the application.
 * This is essentially a wrapper for WordPress Core's get_post_meta,
 * get_user_meta, and get_term_meta.
 */
class Meta_Data {

	/**
	 * Retrieve meta data about an object.
	 *
	 * @param string  $object_type The type of object the meta data is related to. Acceptable values are comment, post, term, and user.
	 * @param integer $object_id The ID of the object that this relates to.
	 * @param string  $meta_key The key for the data we're saving.
	 * @param boolean $single Determines if we're expecting a single result or multiple results.
	 * @return mixed
	 */
	public static function get( $object_type = 'post', $object_id = null, $meta_key = '', $single = true ) {

		// Default value is empty string or array depending on the value of $single.
		$value = ( $single ) ? '' : [];

		// Allow other add-ons and systems to filter the data before the data has been retrieved from the database.
		$custom_meta = apply_filters( 'adsanity_pre_get_meta', $value, $object_type, $object_id, $meta_key, $single );

		// Allow other add-ons and systems to filter the data before the data has been retrieved from the database.
		$custom_meta = apply_filters( "adsanity_pre_get_meta_{$meta_key}", $custom_meta, $object_type, $object_id, $single );

		// Return early if there is a filter.
		if (
			apply_filters( 'adsanity_disable_local_get_meta', false, $value, $object_type, $object_id, $meta_key, $single ) ||
			apply_filters( "adsanity_disable_local_get_{$meta_key}_meta", false, $value, $object_type, $object_id, $single )
		) {
			return $custom_meta;
		}

		// Generic WordPress meta data lookup by object type.
		$postmeta = get_metadata( $object_type, $object_id, $meta_key, $single );

		// Allow other add-ons and systems to filter the data after the data has been retrieved from the database.
		$postmeta = apply_filters( 'adsanity_after_get_meta', $postmeta, $object_type, $object_id, $meta_key, $single );

		// Allow other add-ons and systems to filter the data after the data has been retrieved from the database.
		$postmeta = apply_filters( "adsanity_after_get_meta_{$meta_key}", $postmeta, $object_type, $object_id, $single );

		// Merge the custom data and the standard data together.
		if ( is_array( $postmeta ) && is_array( $custom_meta ) ) {

			$value = array_filter( array_merge( $custom_meta, $postmeta ) );

		} elseif ( ! empty( $custom_meta ) ) {

			$value = $custom_meta;

		} else {

			$value = $postmeta;

		}

		return $value;

	}

	/**
	 * Add or update meta data about an object
	 *
	 * @param string  $object_type The type of object the meta data is related to. Acceptable values are comment, post, term, and user.
	 * @param integer $object_id The ID of the object that this relates to.
	 * @param string  $meta_key The key for the data we're saving.
	 * @param mixed   $meta_value The value we want to store.
	 * @param mixed   $prev_value The old value if we want to update a particular record in the database.
	 * @return mixed
	 */
	public static function update( $object_type = 'post', $object_id = null, $meta_key = '', $meta_value = '', $prev_value = '' ) {

		// Trigger some actions before we update the data.
		do_action( 'adsanity_pre_update_meta', $object_type, $object_id, $meta_key, $meta_value, $prev_value );

		// Trigger some actions before we update the data.
		do_action( "adsanity_pre_update_meta_{$meta_key}", $object_type, $object_id, $meta_value, $prev_value );

		// Return early if there is a filter.
		if (
			apply_filters( 'adsanity_disable_local_update_meta', false, $object_type, $object_id, $meta_key, $meta_value, $prev_value ) ||
			apply_filters( "adsanity_disable_local_update_{$meta_key}_meta", false, $object_type, $object_id, $meta_value, $prev_value )
		) {
			return true;
		}

		if ( '++' === $meta_value ) {

			global $wpdb;

			$table_name      = "{$wpdb->prefix}{$object_type}meta";
			$object_field_id = "{$object_type}_id";

			$result = $wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->prepare(
					"UPDATE {$table_name} SET meta_value = meta_value + 1 WHERE meta_key = %s AND {$object_field_id} = %d", // phpcs:ignore WordPress.DB.PreparedSQL
					$meta_key,
					$object_id
				)
			);

		} elseif ( is_string( $meta_value ) && '+=' === substr( $meta_value, 0, 2 ) ) {

			global $wpdb;

			$table_name      = "{$wpdb->prefix}{$object_type}meta";
			$object_field_id = "{$object_type}_id";

			$result = $wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->prepare(
					"UPDATE {$table_name} SET meta_value = meta_value + %f WHERE meta_key = %s AND {$object_field_id} = %d", // phpcs:ignore WordPress.DB.PreparedSQL
					substr( $meta_value, 2 ),
					$meta_key,
					$object_id
				)
			);

		} else {

			// Generic WordPress meta data lookup by object type.
			$result = update_metadata( $object_type, $object_id, $meta_key, $meta_value, $prev_value );

		}

		// Trigger some actions after we have updated the data.
		do_action( 'adsanity_after_update_meta', $object_type, $object_id, $meta_key, $meta_value, $prev_value );

		// Trigger some actions after we have updated the data.
		do_action( "adsanity_after_update_meta_{$meta_key}", $object_type, $object_id, $meta_value, $prev_value );

		return $result;

	}

	/**
	 * Delete meta data about an object
	 *
	 * @param string  $object_type The type of object the meta data is related to. Acceptable values are comment, post, term, and user.
	 * @param integer $object_id The ID of the object that this relates to.
	 * @param string  $meta_key The key for the data we're saving.
	 * @param mixed   $meta_value The value we want to store.
	 * @param bool    $delete_all Deletes all matching metadata across all objects ignoring object_id.
	 * @return bool
	 */
	public static function delete( $object_type = 'post', $object_id = null, $meta_key = '', $meta_value = '', $delete_all = false ) {

		// Trigger some actions before we update the data.
		do_action( 'adsanity_pre_delete_meta', $object_type, $object_id, $meta_key, $meta_value, $delete_all );

		// Trigger some actions before we update the data.
		do_action( "adsanity_pre_delete_meta_{$meta_key}", $object_type, $object_id, $meta_value, $delete_all );

		// Return early if there is a filter.
		if (
			apply_filters( 'adsanity_disable_local_delete_meta', false, $object_type, $object_id, $meta_key, $meta_value, $delete_all ) ||
			apply_filters( "adsanity_disable_local_delete_{$meta_key}_meta", false, $object_type, $object_id, $meta_value, $delete_all )
		) {
			return true;
		}

		// Generic WordPress meta data lookup by object type.
		$result = delete_metadata( $object_type, $object_id, $meta_key, $meta_value, $delete_all );

		// Trigger some actions after we have updated the data.
		do_action( 'adsanity_after_delete_meta', $object_type, $object_id, $meta_key, $meta_value, $delete_all );

		// Trigger some actions after we have updated the data.
		do_action( "adsanity_after_delete_meta_{$meta_key}", $object_type, $object_id, $meta_value, $delete_all );

		return $result;

	}

}
