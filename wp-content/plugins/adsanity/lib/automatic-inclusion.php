<?php
/**
 * Automatic inclusion related functionality.
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
 * AdSanity_Automatic_Inclusion
 *
 * @pkg   AdSanity
 * @since 1.6
 */
class AdSanity_Automatic_Inclusion {

	static $block_count = 0;

	/**
	 * Kicks off all actions and filters
	 */
	public static function hooks() {

		add_action( 'admin_init', array( __CLASS__, 'admin_init' ), 20 );
		add_filter( 'the_content', array( __CLASS__, 'include_ad' ), PHP_INT_MAX, 1 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'gutenberg_boolean' ) );
		add_filter( 'render_block', array( __CLASS__, 'block_ads' ), 10, 2 );

	}

	/**
	 * Add settings section and field for show in content
	 */
	public static function admin_init() {

		add_settings_section(
			'adsanity_autodisplay_options',
			__( 'Display Options', 'adsanity' ),
			array( __CLASS__, 'adsanity_autodisplay_options' ),
			ADSANITY_ADMIN_OPTIONS
		);
		add_settings_field(
			'adsanity_show_in_content',
			__( 'Automatic Inclusion', 'adsanity' ),
			array( __CLASS__, 'adsanity_show_in_content' ),
			ADSANITY_ADMIN_OPTIONS,
			'adsanity_autodisplay_options'
		);

	}

	/**
	 * Outputs the description for autodisplay options
	 *
	 * @return void
	 */
	public static function adsanity_autodisplay_options() {}

	/**
	 * The view for the settings
	 */
	public static function adsanity_show_in_content() {

		require_once( ADSANITY_VIEWS . 'automatic-inclusion.php' );

	}

	/**
	 * Insert ad to the content.
	 *
	 * @param string $content The post content.
	 */
	public static function include_ad( $content ) {

		$options = get_option( ADSANITY_ADMIN_OPTIONS );

		if ( ! array_key_exists( 'adsanity_show_in_content', $options ) ||
			 ! $options['adsanity_show_in_content'] ||
			 ! array_key_exists( 'adsanity_in_content_rules', $options ) ||
			 ! $options['adsanity_in_content_rules'] ) {

			return $content;

		}

		// Don't do this in the Gutenberg editor.
		global $editing;
		if ( $editing || is_admin() ) {
			return $content;
		}

		foreach ( $options['adsanity_in_content_rules'] as $key => $rule ) {

			global $post;

			// Don't include ads in this post type.
			if ( ! in_array( $post->post_type, $rule['post_types'] ) ) {
				continue;
			}

			/**
			 * Allow arguments for template tags to be modified.
			 *
			 * @param string $template_tag Template tag callable.
			 * @param array $rule The automatic inclusion rule.
			 */
			$template_tag = apply_filters(
				'adsanity_automatic_inclusion_template_tag',
				'adsanity_show_ad_group',
				$rule
			);

			/**
			 * Allow arguments for template tags to be modified.
			 *
			 * @param array $args The args to pass into a template tag.
			 * @param array $rule The automatic inclusion rule.
			 */
			$args = [
				'group_ids'   => [ $rule['ad_group'] ],
				'num_ads'     => 1,
				'num_columns' => 1,
				'return'      => true,
				'align'       => 'align' . $rule['alignment'],
			];
			if ( isset( $rule['max_width'] ) ) {
				$args['max_width'] = $rule['max_width'];
			}

			$args = apply_filters( 'adsanity_automatic_inclusion_args', $args, $rule );

			$ad = call_user_func( $template_tag, $args );

			switch ( $rule['position'] ) {

				// Before position.
				case 'before':
					$content = $ad . $content;
					break;

				// After position.
				case 'after':
					$content = $content . $ad;
					break;

				// Dynamic position.
				case 'dynamic':
					if ( 'block' === $rule['position_element'] ) {
						break;
					}

					$num      = $rule['position_num'];
					$elements = $rule['position_element'];

					$paragraph_markup = '<p';
					$ol_markup        = '<ol';
					$ul_markup        = '<ul';

					if ( 'after' === $rule['position_dynamic'] ) {

						$paragraph_markup = '</p>';
						$ol_markup        = '</ol>';
						$ul_markup        = '</ul>';

					}

					// Start an array of positions of paragraph elements.
					$last_pos  = 0;
					$positions = array();
					while ( ( $last_pos = strpos( $content, $paragraph_markup, $last_pos ) ) !== false ) {

						$positions[] = $last_pos;
						$last_pos    = $last_pos + strlen( $paragraph_markup );

					}

					// Only after Paragraphs.
					if ( 'paragraph' === $elements ) {

						// Make sure there are enough occurences of the element.
						if ( count( $positions ) <= $num - 1 ) {
							break;
						}

						if ( 'after' === $rule['position_dynamic'] ) {

							// Place the ad after '</p>'.
							$content = substr_replace( $content, $ad, $positions[ intval( $num - 1 ) ] + 4, 0 );

						} else {

							// Place the ad before '<p>'.
							$content = substr_replace( $content, $ad, $positions[ intval( $num - 1 ) ], 0 );

						}
					} else {

						// After paragraphs or lists.
						// Add ordered lists to positions.
						while ( ( $last_pos = strpos( $content, $ol_markup, $last_pos ) ) !== false ) {

							$positions[] = $last_pos;
							$last_pos = $last_pos + strlen( $ol_markup );

						}

						// Add unordered lists to positions.
						while ( ( $last_pos = strpos( $content, $ul_markup, $last_pos ) ) !== false ) {

							$positions[] = $last_pos;
							$last_pos = $last_pos + strlen( $ul_markup );

						}

						// Sort the array.
						sort( $positions );

						if ( count( $positions ) <= $num - 1 ) {
							break;
						}

						// The offset if 4 or 5 if the str is </p>, </ol>, or </ul>.
						$offset = substr(
							$content,
							$positions[ intval( $num - 1 ) ],
							strlen( $paragraph_markup )
						) === $paragraph_markup ? strlen( $paragraph_markup ) : strlen( $paragraph_markup ) + 1;

						if ( 'after' === $rule['position_dynamic'] ) {

							// Place the ad after '</p>', '</ul>', or '</ol>'.
							$content = substr_replace( $content, $ad, $positions[ intval( $num - 1 ) ] + $offset, 0 );

						} else {

							// Place the ad before '<p>', '<ul>', or '<ol>'.
							$content = substr_replace( $content, $ad, $positions[ intval( $num - 1 ) ], 0 );

						}
					}

					break;
			}
		}

		return $content;

	}

	/**
	 * Include a variable with the Gutenberg scripts on
	 * whether or not a post has automatically include ads
	 */
	public static function gutenberg_boolean() {

		$automatic_ads = false;

		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			$post_type = $screen->post_type;

			$options = get_option( ADSANITY_ADMIN_OPTIONS );

			if ( isset( $options['adsanity_show_in_content'] ) && $options['adsanity_show_in_content'] ) {
				foreach ( $options['adsanity_in_content_rules'] as $key => $rule ) {

					if ( in_array( $post_type, $rule['post_types'] ) ) {
						$automatic_ads = true;
					}
				}
			}
		}

		wp_localize_script(
			'adsanity-blocks',
			'ADSANITY_AUTOMATIC_ADS',
			array(
				'automatic_ads' => $automatic_ads,
				'settings_url'  => admin_url( 'edit.php?post_type=ads&page=adsanity-settings&tab=general' ),
			)
		);

	}

	/**
	 * Place ad before or after a specified number of blocks
	 */
	public static function block_ads( $block_content, $block ) {

		if ( empty( $block['blockName'] ) ) {
			return $block_content;
		}

		$ignored_blocks = apply_filters( 'adsanity_ignore_blocks_in_count', array( 'core/column', 'core/columns' ) );

		if ( in_array( $block['blockName'], $ignored_blocks ) ) {
			return $block_content;
		}

		++self::$block_count;

		$options = get_option( ADSANITY_ADMIN_OPTIONS );

		if ( ! array_key_exists( 'adsanity_show_in_content', $options ) ||
			 ! $options['adsanity_show_in_content'] ||
			 ! array_key_exists( 'adsanity_in_content_rules', $options ) ||
			 ! $options['adsanity_in_content_rules'] ) {

			return $block_content;

		}

		foreach ( $options['adsanity_in_content_rules'] as $key => $rule ) {

			if ( ! isset( $rule['position_element'] ) || 'block' !== $rule['position_element'] ) {
				continue;
			}

			$ad_args = array(
				'group_ids'   => array( $rule['ad_group'] ),
				'num_ads'     => 1,
				'num_columns' => 1,
				'return'      => true,
				'align'       => 'align' . $rule['alignment'],
			);

			if ( intval( $rule['position_num'] ) === self::$block_count && 'before' === $rule['position_dynamic'] ) {
				$ad = adsanity_show_ad_group( $ad_args );
				$block_content = $ad . $block_content;
				continue;
			}

			if ( intval( $rule['position_num'] ) === self::$block_count && 'after' === $rule['position_dynamic'] ) {
				$ad = adsanity_show_ad_group( $ad_args );
				$block_content = $block_content . $ad;
				continue;
			}

		}

		return $block_content;

	}

}

AdSanity_Automatic_Inclusion::hooks();
