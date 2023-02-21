<?php
/**
 * Ad Model
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

/**
 * AdSanity_Ads_CPT
 * This class extends the base CPT base class with specific customizations for ads.
 */
class AdSanity_Ads_CPT {

	/**
	 * The CPT slug.
	 *
	 * @since 1.0
	 * @var string
	 */
	public static $type = 'ads';

	/**
	 * The singular UI name.
	 *
	 * @since 1.0
	 * @var string
	 */
	public static $singular = '';

	/**
	 * The plural UI name.
	 *
	 * @since 1.0
	 * @var string
	 */
	public static $plural = '';

	/**
	 * CPT Meta fields
	 *
	 * @since 1.0
	 * @var array
	 */
	public static $metafields;

	/**
	 * Kicks off all the hooks required to make this class run.
	 */
	public function __construct() {

		self::$singular = __( 'Ad', 'adsanity' );
		self::$plural   = __( 'Ads', 'adsanity' );

		// Possible Types: embed, str, float, int, date, file, path, editor, color.
		self::$metafields = array(
			'target'            => array( '_target', 'bool' ),
			'url'               => array( '_url', 'url' ),
			'notes'             => array( '_notes', 'str' ),
			'size'              => array( '_size', 'str' ),
			'code'              => array( '_code', 'raw' ),
			'text'              => array( '_text', 'editor' ),
			'text_bg'           => array( '_text_bg', 'color' ),
			'text_border'       => array( '_text_border', 'color' ),
			'text_border_width' => array( '_text_border_width', 'int' ),
			'text_vertical'     => array( '_text_vertical', 'bool' ),
			'start_date'        => array( '_start_date', 'date' ),
			'end_date'          => array( '_end_date', 'date' ),
		);

		self::hooks();

	}

	/**
	 * Hooks into WordPress.
	 *
	 * @return void
	 */
	public static function hooks() {

		global $wp_version;

		add_action( 'init', array( __CLASS__, 'init' ), 0 );
		add_action( 'save_post', array( __CLASS__, 'save_post' ) );
		add_action( 'save_post', array( 'AdSanity_AdsTxt', 'check_for_google_adstxt' ), 20 );

		// Post UI.
		add_action( 'admin_print_styles-post-new.php', array( __CLASS__, 'admin_print_styles' ) );
		add_action( 'admin_print_styles-post.php', array( __CLASS__, 'admin_print_styles' ) );
		add_action( 'admin_print_styles-edit.php', array( __CLASS__, 'admin_print_styles' ) );

		// Taxonomy UI.
		add_action( 'admin_print_styles-edit-tags.php', array( __CLASS__, 'admin_print_styles' ) );

		// Post Type Order UI.
		add_action( sprintf( 'admin_print_styles-%1$s_page_order-post-types-%1$s', static::$type ), array( __CLASS__, 'admin_print_styles' ) );

		add_action( 'after_setup_theme', array( __CLASS__, 'after_setup_theme' ), 99 );
		add_action( 'ads_init', array( __CLASS__, 'taxonomies' ) );
		add_filter( 'enter_title_here', array( __CLASS__, 'enter_title_here' ) );
		add_action( 'edit_form_after_title', array( __CLASS__, 'edit_form_advanced' ) );
		add_action( 'edit_form_advanced', array( __CLASS__, 'edit_form_advanced' ) );
		add_action( 'admin_menu', array( __CLASS__, 'remove_meta_boxes' ) );
		add_action( 'add_meta_boxes_ads', array( __CLASS__, 'add_meta_boxes' ), 1 );
		add_action( 'template_include', array( __CLASS__, 'template_include' ), 1000 );
		add_filter( 'manage_ads_posts_columns', array( __CLASS__, 'columns' ), 99 );
		add_filter( 'manage_ads_posts_custom_column', array( __CLASS__, 'column_values' ), 10, 2 );
		add_filter( 'manage_edit-ads_sortable_columns', array( __CLASS__, 'sortable_columns' ) );
		add_filter( 'manage_edit-ad-group_columns', array( __CLASS__, 'taxonomy_columns' ) );
		add_filter( 'manage_edit-ad-group_sortable_columns', array( __CLASS__, 'taxonomy_sortable_columns' ), 10, 1 );
		add_filter( 'manage_ad-group_custom_column', array( __CLASS__, 'taxonomy_column_values' ), 10, 3 );
		add_filter( 'terms_clauses', array( __CLASS__, 'order_ad_groups_by_active_ad_count' ), 10, 3 );
		add_filter( 'request', array( __CLASS__, 'request' ) );
		add_filter( 'admin_post_thumbnail_html', array( __CLASS__, 'admin_post_thumbnail_html' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'ad_list_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'ad_new_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'ad_edit_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'ad_group_scripts' ) );
		add_action( 'post_submitbox_start', array( __CLASS__, 'post_submitbox_start' ) );

		if ( version_compare( $wp_version, '3.8' ) >= 0 ) {

			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'global_styles' ) );

		}

		if ( is_admin() ) {

			add_filter( 'post_class', array( __CLASS__, 'post_class' ) );
			add_action( 'ads_init', array( __CLASS__, 'flush_permalinks' ), 25 );

		}

	}

	/**
	 * Initializes the custom post type
	 *
	 * @return void
	 */
	public static function init() {

		/**
		 * Register post the post type
		 */
		$label_defaults = array(
			'name'                  => 'AdSanity: ' . __( 'Ads', 'adsanity' ),
			'all_items'             => __( 'Manage Ads', 'adsanity' ),
			'singular_name'         => static::$singular,
			'add_new'               => __( 'Create Ad', 'adsanity' ),
			'add_new_item'          => 'AdSanity: ' . __( 'Create Ad', 'adsanity' ),
			'edit_item'             => 'AdSanity: ' . __( 'Edit Ad', 'adsanity' ),
			'new_item'              => 'AdSanity: ' . __( 'Create Ad', 'adsanity' ),
			/* Translators: The Singular name of the post type. */
			'view_item'             => sprintf( __( 'View %s' ), static::$singular ),
			/* Translators: The Plural name of the post type. */
			'search_items'          => sprintf( __( 'Search %s' ), static::$plural ),
			/* Translators: The Plural name of the post type. */
			'not_found'             => sprintf( __( 'No %s found' ), strtolower( static::$plural ) ),
			/* Translators: The Plural name of the post type. */
			'not_found_in_trash'    => sprintf( __( 'No %s found in Trash' ), strtolower( static::$plural ) ),
			'parent_item_colon'     => '',
			'menu_name'             => 'AdSanity',
			'featured_image'        => __( 'Ad Image', 'adsanity' ),
			'set_featured_image'    => __( 'Set ad image', 'adsanity' ),
			'remove_featured_image' => __( 'Remove ad image', 'adsanity' ),
			'use_featured_image'    => __( 'Set ad image', 'adsanity' ),
		);

		$setup_defaults = array(
			'labels'              => apply_filters(
				sprintf( 'pj_%s_labels', static::$type ), // phpcs:ignore WordPress.NamingConventions.ValidHookName
				$label_defaults
			),
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'capability_type'     => 'post',
			'has_archive'         => false,
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => array( 'title', 'thumbnail' ),
			'has_archive'         => false,
			'rewrite'             => array(
				'with_front' => false,
				'slug'       => 'ads',
			),
			'exclude_from_search' => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
		);

		register_post_type(
			static::$type,
			apply_filters( 'ads_setup', $setup_defaults )
		);

		// For hooking in custom taxonomies.
		do_action( 'ads_init' );

	}

	/**
	 * Checks for post-thumbnail support in the theme and enables it if it's not available.
	 */
	public static function after_setup_theme() {

		if ( ! current_theme_supports( 'post-thumbnails', array( self::$type ) ) ) {

			add_theme_support( 'post-thumbnails', array( self::$type ) );

		}

	}

	/**
	 * Hooks into the base class to add a taxonomy for ad groups.
	 *
	 * @uses PJ_CPT_Base::init
	 */
	public static function taxonomies() {

		$labels = array(
			'name'              => 'AdSanity: ' . _x( 'Ad Groups', 'taxonomy general name', 'adsanity' ),
			'singular_name'     => _x( 'Group', 'taxonomy singular name', 'adsanity' ),
			'search_items'      => __( 'Search Ad Groups', 'adsanity' ),
			'all_items'         => __( 'All Groups', 'adsanity' ),
			'parent_item'       => __( 'Parent Group', 'adsanity' ),
			'parent_item_colon' => __( 'Parent Group: ', 'adsanity' ),
			'edit_item'         => 'AdSanity: ' . __( 'Edit Group', 'adsanity' ),
			'update_item'       => __( 'Update Group', 'adsanity' ),
			'add_new_item'      => __( 'Add New Group', 'adsanity' ),
			'new_item_name'     => __( 'New Group Name', 'adsanity' ),
			'menu_name'         => __( 'Ad Groups', 'adsanity' ),
		);

		register_taxonomy(
			'ad-group',
			array( self::$type ),
			array(
				'hierarchical' => true,
				'labels'       => $labels,
				'show_ui'      => true,
				'show_in_rest' => true,
				'query_var'    => true,
				'rewrite'      => array(
					'with_front' => false,
					'slug'       => 'ad-group',
				),
			)
		);

	}

	/**
	 * Adds the taxonomy ID in the ad group list.
	 *
	 * @param array $columns An associative array of slugs and names for the taxonomy table.
	 * @return array the modified array of columns for the taxonomy table.
	 */
	public static function taxonomy_columns( $columns = array() ) {

		$columns = [
			'cb'                 => '<input type="checkbox" />',
			'name'               => __( 'Name' ),
			'adsanity-shortcode' => __( 'Shortcode', 'adsanity' ),
			'adsanity-template'  => __( 'Template Tag', 'adsanity' ),
			'posts'              => __( 'Ads', 'adsanity' ),
			'adsanity-active'    => __( 'Active', 'adsanity' ),
		];
		return $columns;

	}

	/**
	 * Adds the taxonomy ID in the ad group list.
	 *
	 * @param array $columns An array array of slugs for the taxonomy table.
	 * @return array the modified array of columns for the taxonomy table.
	 */
	public static function taxonomy_sortable_columns( $columns = [] ) {

		$columns['adsanity-active'] = 'active_ad_count';
		return $columns;

	}

	/**
	 * Outputs the values of each column for each row in the taxonomy list.
	 *
	 * @param string $value the output for the column value.
	 * @param string $column_name the slug of the column.
	 * @param int    $term_id the term_id of the row.
	 * @return string the final output for the column value.
	 */
	public static function taxonomy_column_values( $value, $column_name, $term_id ) {

		if ( 'adsanity-shortcode' === $column_name ) {

			$shortcode = sprintf(
				'<a title="%s" class="adsanity-clipboard" data-clipboard-text="[adsanity_group align=\'alignnone\' num_ads=1 num_columns=1 group_ids=%d]">[shortcode]</a>',
				__( 'Copy to clipboard', 'adsanity' ),
				intval( $term_id )
			);
			$value     = $shortcode;

		} elseif ( 'adsanity-template' === $column_name ) {

			$template_tag = sprintf(
				'<a title="%s" class="adsanity-clipboard" data-clipboard-text="adsanity_show_ad_group(array( \'align\' => \'alignnone\', \'num_ads\' => 1, \'num_columns\' => 1, \'group_ids\' => array(%d), \'return\' => false));">&lt;?php</a>',
				__( 'Copy to clipboard', 'adsanity' ),
				intval( $term_id )
			);
			$value        = $template_tag;

		} elseif ( 'adsanity-active' === $column_name ) {

			$now = time();

			$active_ads = \get_posts(
				[
					'numberposts' => -1,
					'post_type'   => 'ads',
					'fields'      => 'ids',
					'tax_query'   => [ // phpcs:ignore WordPress.DB.SlowDBQuery
						[
							'taxonomy' => 'ad-group',
							'field'    => 'term_id',
							'terms'    => $term_id,
						],
					],
					'meta_query'  => [ // phpcs:ignore WordPress.DB.SlowDBQuery
						[
							'key'     => '_start_date',
							'value'   => $now,
							'type'    => 'numeric',
							'compare' => '<=',
						],
						[
							'key'     => '_end_date',
							'value'   => $now,
							'type'    => 'numeric',
							'compare' => '>=',
						],
					],
				]
			);

			$value = count( $active_ads );

		}

		return $value;

	}

	/**
	 * Allow ad groups in the admin to be ordered by active ad count
	 *
	 * @param array $pieces Array of query SQL clauses.
	 * @param array $taxonomies An array of taxonomy names.
	 * @param array $args An array of term query arguments.
	 * @return array $pieces Array of pieces of the query
	 */
	public static function order_ad_groups_by_active_ad_count( $pieces, $taxonomies, $args ) {

		if ( ! is_admin() ) {
			return $pieces;
		}

		global $pagenow;

		// Check for edit taxonomy page.
		if ( 'edit-tags.php' !== $pagenow ) {
			return $pieces;
		}

		// Check that the taxonomy is ad group.
		if ( 'ad-group' !== $taxonomies[0] ) {
			return $pieces;
		}

		// Check for request params.
		if ( ! isset( $_REQUEST['orderby'] ) || 'active_ad_count' !== sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return $pieces;
		}

		// Check that this is the main query for display.
		if ( 't.*, tt.*' !== $pieces['fields'] ) {
			return $pieces;
		}

		// Current timestamp.
		$now = time();

		global $wpdb;

		// Select active ad count.
		$pieces['fields'] .= ', COUNT(*) as adsanity_ad_count';

		// Get ads from term relationships.
		$pieces['join'] .= " INNER JOIN {$wpdb->term_relationships} as adsanity_term_relationships ON adsanity_term_relationships.term_taxonomy_id = tt.term_id";

		// Join start date.
		$pieces['join'] .= " INNER JOIN {$wpdb->postmeta} as adsanity_start_date ON adsanity_start_date.post_id = adsanity_term_relationships.object_id AND adsanity_start_date.meta_key = '_start_date'";

		// Join end date.
		$pieces['join'] .= " INNER JOIN {$wpdb->postmeta} as adsanity_end_date ON adsanity_end_date.post_id = adsanity_term_relationships.object_id AND adsanity_end_date.meta_key = '_end_date'";

		// Only currently active ads.
		$pieces['where'] .= " AND adsanity_start_date.meta_value <= {$now} AND adsanity_end_date.meta_value >= {$now}";

		// Group by term_id.
		$pieces['orderby'] = 'GROUP BY tt.term_id';

		// Order by active count.
		$pieces['orderby'] .= ' ORDER BY adsanity_ad_count';

		return $pieces;

	}

	/**
	 * During the activation phase, we set an option in the database to let us know that we need to
	 * flush permalinks to pick up the new custom post type permalink structure
	 */
	public static function flush_permalinks() {

		$adsanity_options = get_option( ADSANITY_ADMIN_OPTIONS, array() );
		if ( isset( $adsanity_options['update-permalinks'] ) && 1 === $adsanity_options['update-permalinks'] ) {

			flush_rewrite_rules();
			unset( $adsanity_options['update-permalinks'] );
			update_option( ADSANITY_ADMIN_OPTIONS, $adsanity_options );

		}

	}

	/**
	 * Sets up the columns that show in the ad post list
	 *
	 * @param array $columns An associative array of slugs and names for the posts table.
	 * @return array the modified array of columns for the posts table.
	 */
	public static function columns( $columns = array() ) {

		$columns = array(
			'cb'                 => '<input type = "checkbox" />',
			'ad-title'           => __( 'Ad Title', 'adsanity' ),
			'adsanity-size'      => __( 'Dimensions', 'adsanity' ),
			'adsanity-shortcode' => __( 'Shortcode', 'adsanity' ),
			'adsanity-template'  => __( 'Template Tag', 'adsanity' ),
			'adsanity-stats'     => __( "Today's Stats", 'adsanity' ),
			'adsanity-start'     => __( 'Display From', 'adsanity' ),
			'adsanity-expires'   => __( 'Until', 'adsanity' ),
		);

		return apply_filters( 'adsanity_ads_posts_columns', $columns );
	}

	/**
	 * A definition of columns that are deemed sortable.
	 *
	 * @param array $columns the default columns that are sortable.
	 * @return array the modified array of columns that are sortable.
	 */
	public static function sortable_columns( $columns = array() ) {

		$columns = array(
			'ad-title'         => 'title',
			'adsanity-size'    => 'size',
			'adsanity-start'   => 'start_date',
			'adsanity-expires' => 'end_date',
		);

		return apply_filters( 'adsanity_ads_sortable_posts_columns', $columns );

	}

	/**
	 * Outputs the values of each column for each row in the post list.
	 *
	 * @param string $column the slug of the column.
	 * @param int    $post_id the post_id of the row.
	 */
	public static function column_values( $column = '', $post_id = 1 ) {

		$value = '';

		if ( 'ad-title' === $column ) {

			$title_format = '<strong>
				<a class="row-title" href="%s">%s</a>
			</strong>
			<code class="ad-id">#ad-%s</code>';

			$value = sprintf(
				$title_format,
				get_edit_post_link( $post_id ),
				get_the_title( $post_id ),
				esc_html( $post_id )
			);

			$size = Adsanity\Meta_Data::get( 'post', $post_id, '_size', true );

			if ( $size ) {

				$value .= sprintf(
					'<code>.ad-%s</code>',
					esc_html( $size )
				);

			}

			$value .= get_inline_data( get_post( $post_id ) );

		} elseif ( 'adsanity-size' === $column ) {

			$options = get_option( ADSANITY_ADMIN_OPTIONS, array( 'sizes' => array() ) );
			$sizes   = apply_filters( 'adsanity_ad_sizes', $options['sizes'] );
			$size    = Adsanity\Meta_Data::get( 'post', $post_id, '_size', true );

			if ( ! $size ) {

				$value = __( '- not set -', 'adsanity' );

			} elseif ( ! isset( $sizes[ $size ] ) ) {

				$value = __( '- invalid size -', 'adsanity' );

			} else {

				$value = $sizes[ $size ];

			}
		} elseif ( 'adsanity-shortcode' === $column ) {

			$shortcode = sprintf(
				'<a title="%s" class="adsanity-clipboard" data-clipboard-text="[adsanity align=\'alignnone\' id=%s]">[shortcode]</a>',
				__( 'Copy to clipboard', 'adsanity' ),
				intval( $post_id )
			);
			$value     = $shortcode;

		} elseif ( 'adsanity-template' === $column ) {

			$template_tag = sprintf(
				'<a title="%s" class="adsanity-clipboard" data-clipboard-text="adsanity_show_ad( array( \'align\' => \'alignnone\', \'post_id\' => %d, \'return\' => false, ) );">&lt;?php</a>',
				__( 'Copy to clipboard', 'adsanity' ),
				intval( $post_id )
			);
			$value        = $template_tag;

		} elseif ( 'adsanity-stats' === $column ) {

			// Views.
			$view_key = adsanity_get_meta_key( 'views' );
			$views    = Adsanity\Meta_Data::get( 'post', $post_id, $view_key, true );
			$views    = ( ! $views ? 0 : intval( $views ) );
			/* Translators: The number of views. */
			$views_output = sprintf( __( '%s views', 'adsanity' ), $views );

			// Clicks.
			$click_key = adsanity_get_meta_key( 'clicks' );
			$clicks    = Adsanity\Meta_Data::get( 'post', $post_id, $click_key, true );
			$clicks    = ( ! $clicks ? 0 : intval( $clicks ) );
			/* Translators: The number of clicks. */
			$clicks_output = sprintf( __( '%s clicks', 'adsanity' ), $clicks );

			// Percentage.
			$percentage = 0;
			if ( intval( $clicks ) > 0 && intval( $views ) > 0 ) {

				$percentage = ( intval( $clicks ) / intval( $views ) * 100 );

			}
			$percentage_output = round( $percentage ) . '%';

			$value = sprintf( '%s / %s / %s', $clicks_output, $views_output, $percentage_output );

		} elseif ( 'adsanity-start' === $column ) {

			$start_date = Adsanity\Meta_Data::get( 'post', $post_id, '_start_date', true );

			if ( ! $start_date ) {
				$value = __( '- not set -', 'adsanity' );
			} else {
				$value = sprintf(
					'<span data-timestamp="%s">%s</span>',
					esc_attr( $start_date ),
					esc_html( date_i18n( 'F d, Y', $start_date ) )
				);
			}

		} elseif ( 'adsanity-expires' === $column ) {

			$end_date = Adsanity\Meta_Data::get( 'post', $post_id, '_end_date', true );
			if ( ! $end_date ) {

				$value = __( '- not set -', 'adsanity' );

			} else {

				if ( ADSANITY_EOL !== $end_date ) {

					$value = sprintf(
						'<span data-timestamp="%s">%s</span>',
						esc_attr( $end_date ),
						esc_html( date_i18n( 'F d, Y', $end_date ) )
					);

				} else {

					$value = __( 'Forever', 'adsanity' );

				}
			}
		}

		echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput
			sprintf( 'adsanity_ads_posts_columns_%s_value', $column ), // phpcs:ignore WordPress.NamingConventions.ValidHookName
			$value,
			$column,
			$post_id
		);

	}

	/**
	 * Provides sorting functionality the for ads post list table
	 *
	 * @param array $vars query vars.
	 * @return array modified query vars with new sorting options.
	 */
	public static function request( $vars = array() ) {

		// Only show when we're looking at ads.
		if ( ! is_admin() || ! isset( $vars['post_type'] ) || $vars['post_type'] !== self::$type ) {

			return $vars;

		}

		$vars = wp_parse_args( $vars, array( 'orderby' => 'id' ) );

		if ( isset( $vars['orderby'] ) && 'id' === $vars['orderby'] ) {

			// Order by post ID.
			$vars = array_merge(
				$vars,
				array(
					'orderby' => 'ID',
				)
			);

		} elseif ( isset( $vars['orderby'] ) && 'size' === $vars['orderby'] ) {

			// Order by size custom meta field.
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => '_size', // phpcs:ignore WordPress.DB.SlowDBQuery
					'orderby'  => 'meta_value',
				)
			);

		} elseif ( isset( $vars['orderby'] ) && 'start_date' === $vars['orderby'] ) {

			// Order by the start date custom meta field.
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => '_start_date', // phpcs:ignore WordPress.DB.SlowDBQuery
					'orderby'  => 'meta_value_num',
				)
			);

		} elseif ( isset( $vars['orderby'] ) && 'end_date' === $vars['orderby'] ) {

			// Order by the expiring date.
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => '_end_date', // phpcs:ignore WordPress.DB.SlowDBQuery
					'orderby'  => 'meta_value_num',
				)
			);

		}

		return apply_filters(
			sprintf( 'adsanity_ads_posts_sortable_by_%s', $vars['orderby'] ), // phpcs:ignore WordPress.NamingConventions.ValidHookName
			$vars
		);
	}

	/**
	 * Automatically registers admin styles for the custom post type.
	 */
	public static function admin_print_styles() {

		$screen = get_current_screen();

		if ( static::$type === $screen->post_type ) {

			wp_enqueue_style(
				sprintf( '%s-admin', static::$type ),
				plugin_dir_url( __FILE__ ) . '../dist/css/' . static::$type . '.css',
				array(),
				ADSANITY_VERSION,
				'screen'
			);

		}

	}

	/**
	 * Enqueues global admin styles
	 */
	public static function global_styles() {

		wp_enqueue_style( 'adsanity-admin-global' );

	}

	/**
	 * Enqueues admin scripts and styles for the ad list view. This is only necessary for WordPress
	 * prior to version 3.3. After version 3.3 a new hook was introduced to handle post classes
	 *
	 * @param string $hook_prefix the page hook in the admin.
	 */
	public static function ad_list_scripts( $hook_prefix = '' ) {

		// Only run on the edit screen.
		if ( 'edit.php' !== $hook_prefix ) {

			return false;

		}

		// Only run for the ad custom post type.
		if ( get_post_type() === self::$type || get_query_var( 'post_type' ) === self::$type ) {

			global $wp_locale;

			// Enqueue scripts to highlight ads for pre-WP3.3.
			wp_enqueue_script( 'adsanity-list' );
			wp_localize_script(
				'adsanity-list',
				'adsanity',
				array(
					'gmt_offset'      => get_option( 'gmt_offset' ),
					'timezone_string' => get_option( 'timezone_string' ),
					'monthNames'      => array_values( $wp_locale->month ),
				)
			);

		}
	}

	/**
	 * Enqueues admin scripts and styles for the ad list view. This is only necessary for WordPress
	 * prior to version 3.3. After version 3.3 a new hook was introduced to handle post classes
	 *
	 * @param string $hook_prefix the page hook in the admin.
	 */
	public static function ad_group_scripts( $hook_prefix = '' ) {

		// Only run on the edit screen.
		if ( 'edit-tags.php' !== $hook_prefix && 'term.php' !== $hook_prefix ) {

			return false;

		}

		$screen = get_current_screen();

		// Only run for the ad group taxonomy.
		if ( 'ad-group' === $screen->taxonomy ) {

			wp_enqueue_script( 'adsanity-groups' );

		}

	}

	/**
	 * Enqueue jQuert UI and custom scripts and styles for the new ad page.
	 *
	 * @param string $hook_prefix the page hook in the admin.
	 */
	public static function ad_new_scripts( $hook_prefix = '' ) {

		// Only run on the new post screen.
		if ( 'post-new.php' !== $hook_prefix ) {

			return false;

		}

		// Only run for the ad custom post type.
		if ( get_post_type() === self::$type || get_query_var( 'post_type' ) === self::$type ) {

			global $wp_locale;
			global $post;

			wp_enqueue_style( 'adsanity-jqueryui-datepicker' );
			wp_enqueue_script( 'adsanity-post-new' );
			wp_enqueue_style( 'adsanity-default-css' );
			wp_enqueue_style( 'adsanity-post' );
			wp_localize_script(
				'adsanity-post-new',
				'adsanity',
				array(
					'ad_id'           => $post->ID,
					'adsanity_eol'    => ADSANITY_EOL,
					'gmt_offset'      => get_option( 'gmt_offset' ),
					'timezone_string' => get_option( 'timezone_string' ),
					'monthNames'      => array_values( $wp_locale->month ),
					'months'          => $wp_locale->month,
					'months_01'       => $wp_locale->month['01'],
					'months_02'       => $wp_locale->month['02'],
					'months_03'       => $wp_locale->month['03'],
					'months_04'       => $wp_locale->month['04'],
					'months_05'       => $wp_locale->month['05'],
					'months_06'       => $wp_locale->month['06'],
					'months_07'       => $wp_locale->month['07'],
					'months_08'       => $wp_locale->month['08'],
					'months_09'       => $wp_locale->month['09'],
					'months_10'       => $wp_locale->month['10'],
					'months_11'       => $wp_locale->month['11'],
					'months_12'       => $wp_locale->month['12'],
					'expires_text'    => __( 'Ad Expires on ', 'adsanity' ),
					'forever_text'    => sprintf(
						'%s <strong class = "expiring-date">%s</strong> ',
						__( 'Publish', 'adsanity' ),
						__( 'forever', 'adsanity' )
					),
				)
			);

		}

	}

	/**
	 * Enqueue jQuery UI and custom scripts and styles for the edit ad page.
	 *
	 * @param string $hook_prefix the page hook in the admin.
	 */
	public static function ad_edit_scripts( $hook_prefix = '' ) {

		if ( 'post.php' !== $hook_prefix ) {

			return false;

		}

		// Only run for the ad custom post type.
		if ( get_post_type() === self::$type || get_query_var( 'post_type' ) === self::$type ) {

			global $wp_locale, $post;

			$script = ( 'publish' === $post->post_status ) ? 'adsanity-post' : 'adsanity-post-new';

			wp_enqueue_style( 'adsanity-jqueryui-datepicker' );
			wp_enqueue_style( 'adsanity-default-css' );
			wp_enqueue_style( 'adsanity-post' );
			wp_enqueue_script( $script );
			wp_localize_script(
				$script,
				'adsanity',
				array(
					'ad_id'           => $post->ID,
					'adsanity_eol'    => ADSANITY_EOL,
					'gmt_offset'      => get_option( 'gmt_offset' ),
					'timezone_string' => get_option( 'timezone_string' ),
					'monthNames'      => array_values( $wp_locale->month ),
					'months'          => $wp_locale->month,
					'months_01'       => $wp_locale->month['01'],
					'months_02'       => $wp_locale->month['02'],
					'months_03'       => $wp_locale->month['03'],
					'months_04'       => $wp_locale->month['04'],
					'months_05'       => $wp_locale->month['05'],
					'months_06'       => $wp_locale->month['06'],
					'months_07'       => $wp_locale->month['07'],
					'months_08'       => $wp_locale->month['08'],
					'months_09'       => $wp_locale->month['09'],
					'months_10'       => $wp_locale->month['10'],
					'months_11'       => $wp_locale->month['11'],
					'months_12'       => $wp_locale->month['12'],
					'expires_text'    => __( 'Ad Expires on ', 'adsanity' ),
					'forever_text'    => sprintf(
						'%s <strong class="expiring-date">%s</strong> ',
						__( 'Publish', 'adsanity' ),
						__( 'forever', 'adsanity' )
					),
				)
			);

		}

	}

	/**
	 * Enqueue's the CSS for the datepicker.
	 *
	 * @param string $hook_prefix hook name for the page.
	 * @return void
	 */
	public static function datepicker_css( $hook_prefix = '' ) {

		if ( 'post-new.php' !== $hook_prefix && 'post.php' !== $hook_prefix ) {

			return;

		}

		// Only run for the ad custom post type.
		if ( get_post_type() === self::$type || get_query_var( 'post_type' ) === self::$type ) {

			wp_enqueue_style( 'adsanity-jqueryui-datepicker' );

		}

	}

	/**
	 * Filters the title field placeholder text for ad post types.
	 *
	 * @param  string $placeholder the default placeholder text.
	 * @return string the modified placeholder text.
	 */
	public static function enter_title_here( $placeholder = '' ) {

		if ( get_post_type() === self::$type || get_query_var( 'post_type' ) === self::$type ) {

			return __( 'Give this ad a title', 'adsanity' );

		}

		return $placeholder;

	}

	/**
	 * Removes the featured image metabox rom the sidebar
	 */
	public static function remove_meta_boxes() {

		remove_meta_box( 'postimagediv', self::$type, 'side' );

	}

	/**
	 * Adds all of our custom meta boxes.
	 *
	 * @param WP_Post $post the post object that is being edited.
	 */
	public static function add_meta_boxes( $post ) {

		/* COMMON */

		// Ad size.
		add_meta_box(
			'ad-size',
			__( 'Ad Size', 'adsanity' ),
			array( __CLASS__, 'ad_size_metabox' ),
			self::$type,
			'normal',
			'high'
		);

		// Notes.
		add_meta_box(
			'adsanity-notes',
			__( 'Notes', 'adsanity' ),
			array( __CLASS__, 'ad_notes_metabox' ),
			self::$type,
			'normal',
			'low'
		);

		// Tracking url.
		add_meta_box(
			'internal-ad-details',
			__( 'Ad Details', 'adsanity' ),
			array( __CLASS__, 'internal_ad_details_metabox' ),
			self::$type,
			'normal',
			'high'
		);

		// Embed codes.
		if ( 'publish' === $post->post_status ) {

			add_meta_box(
				'non-widget-embeds',
				__( 'Non-Widget Embeds', 'adsanity' ),
				array( __CLASS__, 'non_widget_metabox' ),
				self::$type,
				'side',
				'low'
			);

		}

		/* INTERNAL ADS */

		// Post thumbnail.
		add_meta_box(
			'postimagediv',
			__( 'Ad Image', 'adsanity' ),
			'post_thumbnail_meta_box',
			null,
			'normal',
			'high'
		);

		/* EXTERNAL ADS */

		// Ad code.
		add_meta_box(
			'ad-code',
			__( 'Ad Code', 'adsanity' ),
			array( __CLASS__, 'ad_code_metabox' ),
			self::$type,
			'normal',
			'high'
		);

		/* TEXT ADS */

		// Ad text.
		add_meta_box(
			'ad-text',
			__( 'Ad Text', 'adsanity' ),
			array( __CLASS__, 'ad_text_metabox' ),
			self::$type,
			'normal',
			'high'
		);

		// Chart.
		if ( 'publish' === $post->post_status ) {

			add_meta_box(
				'ad-chart',
				__( 'Ad Stats', 'adsanity' ),
				array( __CLASS__, 'ad_chart_metabox' ),
				self::$type,
				'normal',
				'high'
			);

		}

	}

	/**
	 * Changes the link text in the featured image box.
	 *
	 * @param string $content the link text before modification.
	 * @return string the link text after modfication.
	 */
	public static function admin_post_thumbnail_html( $content = '' ) {

		global $post;
		if (
			get_post_type() === self::$type ||
			get_query_var( 'post_type' ) === self::$type ||
			( isset( $post->post_type ) && $post->post_type === self::$type )
		) {

			$content = str_replace(
				__( 'Set featured image' ),
				__( 'Set ad image', 'adsanity' ),
				$content
			);
			$content = str_replace(
				__( 'Remove featured image' ),
				__( 'Remove ad image', 'adsanity' ),
				$content
			);

		}

		return $content;

	}

	/**
	 * Allows the user to copy the shortcode or template tag for the ad
	 *
	 * @param WP_Post $post the post object that is being edited.
	 * @return void
	 */
	public static function non_widget_metabox( $post ) {

		// Shortcode.
		echo '<div>';

			echo sprintf(
				'<p class="description">%s</p>',
				esc_html__( 'Clicking this button to copy the shortcode for this ad to your clipboard for use in content areas.', 'adsanity' )
			);

			$shortcode = sprintf(
				'<a title="%s" class="adsanity-clipboard" data-clipboard-text="[adsanity align=\'alignnone\' id=%s]">[shortcode]</a>',
				esc_html__( 'Copy to clipboard', 'adsanity' ),
				intval( $post->ID )
			);
			// @codingStandardsIgnoreLine
			echo $shortcode;

		echo '</div>';

		echo '<div>';

			echo sprintf(
				'<p class="description">%s</p>',
				esc_html__( 'Click this button to copy the php code for this ad to your clipboard for use in theme templates.', 'adsanity' )
			);

			$template_tag = sprintf(
				'<a title="%s" class="adsanity-clipboard" data-clipboard-text="adsanity_show_ad( array( \'align\' => \'alignnone\', \'post_id\' => %d, \'return\' => false, ) );">&lt;?php</a>',
				esc_html__( 'Copy to clipboard', 'adsanity' ),
				intval( $post->ID )
			);
			// @codingStandardsIgnoreLine
			echo $template_tag;

		echo '</div>';

	}

	/**
	 * Produces the metabox for external ads ad code.
	 *
	 * @param WP_Post $post the post object for the post being edited.
	 * @return void
	 */
	public static function ad_code_metabox( $post ) {

		wp_nonce_field(
			sprintf( '%s-save_postmeta', self::$type ),
			sprintf( '%s_nonce', self::$type )
		);

		echo sprintf(
			'<p>%s</p>',
			wp_kses_post(
				__( 'If you have code from an advertising partner, paste that code here. If you\'d like to create your own text based ads, you can write custom HTML in the field below. To track clicks on links in the HTML, use the placeholder <code>%link%</code> as your href values. For example, <code>&lt;a href="%link%"&gt;Link Text&lt;/a&gt;</code>. Be sure to enter your destination URL in the Tracking URL field above.', 'adsanity' )
			)
		);
		echo '</p>';

		echo sprintf(
			'<textarea name="code" id="ad-code">%s</textarea>',
			esc_textarea( Adsanity\Meta_Data::get( 'post', $post->ID, '_code', true ) )
		);
	}

	/**
	 * Produces the metabox for text ads
	 *
	 * @param WP_Post $post the post object for the post being edited.
	 * @return void
	 */
	public static function ad_text_metabox( $post ) {

		wp_nonce_field(
			sprintf( '%s-save_postmeta', self::$type ),
			sprintf( '%s_nonce', self::$type )
		);

		printf(
			'<p>%s</p>',
			esc_html__( 'Use this field to create a custom text ad. In order to track clicks in your text ad, you must set all links to the permalink for this ad.', 'adsanity' )
		);

		/**
		 * TinyMCE editor
		 */
		wp_editor(
			Adsanity\Meta_Data::get( 'post', $post->ID, '_text', true ),
			'adtexteditor',
			array(
				'media_buttons' => false,
				'textarea_name' => 'text',
				'textarea_rows' => 8,
			)
		);

		/**
		 * Background color
		 */
		printf(
			'<label for="%2$s">%1$s</label>
			<input id="%2$s" class="adsanity-colorpicker" type="text" name="text_bg" value="%3$s"/>',
			esc_html__( 'Background Color (optional)', 'adsanity' ),
			esc_attr( 'ad-text-bg' ),
			esc_attr( Adsanity\Meta_Data::get( 'post', $post->ID, '_text_bg', true ) )
		);

		echo '<hr>';

		/**
		 * Border color
		 */
		printf(
			'<label for="%2$s">%1$s</label>
			<input id="%2$s" class="adsanity-colorpicker" type="text" name="text_border" value="%3$s"/>',
			esc_html__( 'Border Color (optional)', 'adsanity' ),
			esc_attr( 'ad-text-border' ),
			esc_attr( Adsanity\Meta_Data::get( 'post', $post->ID, '_text_border', true ) )
		);

		echo '<hr>';

		/**
		 * Border width
		 */
		printf(
			'<div class="adsanity-border-width"><label>
				<span>%s</span>
				<input type="number" name="text_border_width" value="%s"/>
			</label></div>',
			esc_html__( 'Border width (pixels, optional)', 'adsanity' ),
			esc_attr( Adsanity\Meta_Data::get( 'post', $post->ID, '_text_border_width', true ) )
		);

		echo '<hr>';

		/**
		 * Vertical align checkbox
		 */
		printf(
			'<div class="adsanity-vertical-align"><label>
				<span>%s</span>
				<input id="%s" type="checkbox" name="text_vertical" %s/>
			</label></div>',
			esc_html__( 'Vertically align text?', 'adsanity' ),
			esc_attr( 'ad-text-vertical' ),
			checked( Adsanity\Meta_Data::get( 'post', $post->ID, '_text_vertical', true ), true, false )
		);

	}

	/**
	 * Outputs additional fields to the publish box for an ad unit.
	 */
	public static function post_submitbox_start() {

		global $post;

		// Only add functionality for Ads.
		if ( get_post_type() === self::$type || get_query_var( 'post_type' ) === self::$type ) {

			// SCHEDULE CHECKBOX.
			$start_date = Adsanity\Meta_Data::get( 'post', $post->ID, '_start_date', true );
			$end_date   = Adsanity\Meta_Data::get( 'post', $post->ID, '_end_date', true );

			// AD SCHEDULING.
			echo '<div id="ad-scheduling" class="misc-pub-section curtime">';
			echo '<span id="timestamp" class="expires-text"></span>';

			// SCHEDULE CHECKBOX.
			echo sprintf( ' <a href="#" id="is_scheduled">%s</a>', esc_html__( 'Edit', 'adsanity' ) );

			// SCHEDULE FIELDS.
			echo '<div id="for_scheduled_only" style="display: none;">';

			// START DATE.
			echo sprintf( '<label for="start-date">%s</label>', esc_html__( 'Display From', 'adsanity' ) );

			echo '<input id="start-date" type="text" class="start-date"/>';

			echo '<div class="time">';
			echo '<select class="start-hour">';
			for ( $i = 0; $i < 24; ++$i ) {
				$value = str_pad( $i, 2, '0', STR_PAD_LEFT );
				printf(
					'<option value="%1$s">%2$s</option>',
					esc_attr( $value ),
					esc_html( $value )
				);
			}
			echo '</select>';

			echo '<span>:</span>';

			echo '<select class="start-minute">';
			for ( $i = 0; $i < 60; ++$i ) {
				$value = str_pad( $i, 2, '0', STR_PAD_LEFT );
				printf(
					'<option value="%1$s">%2$s</option>',
					esc_attr( $value ),
					esc_html( $value )
				);
			}
			echo '</select>';

			echo '</div>';

			$start_value = ! $start_date ? time() : $start_date;
			printf(
				'<input type="hidden" name="start_date" id="start_date" value="%s">',
				esc_attr( $start_value )
			);

			// END DATE.
			echo sprintf( '<label for="end-date">%s</label>', esc_html__( 'Until', 'adsanity' ) );

			echo '<input type="text" class="end-date" id="end-date"/>';

			echo '<div class="time">';

			echo '<select class="end-hour">';
			for ( $i = 0; $i < 24; ++$i ) {
				$value = str_pad( $i, 2, '0', STR_PAD_LEFT );
				printf(
					'<option value="%1$s">%2$s</option>',
					esc_attr( $value ),
					esc_html( $value )
				);
			}
			echo '</select>';

			echo '<span>:</span>';

			echo '<select class="end-minute">';
			for ( $i = 0; $i < 60; ++$i ) {
				$value = str_pad( $i, 2, '0', STR_PAD_LEFT );
				printf(
					'<option value="%1$s">%1$s</option>',
					esc_attr( $value ),
					esc_html( $value )
				);
			}
			echo '</select>';

			echo '</div>';

			$end_value = ! $end_date ? ADSANITY_EOL : $end_date;

			printf(
				'<input type="hidden" name="end_date" id="end_date" value="%s">',
				esc_attr( $end_value )
			);

			// Accept/Cancel Buttons.
			echo '<p>';
			echo sprintf(
				'<a href="#" id="accept_schedule_change" class="button">%s</a> ',
				esc_html__( 'OK', 'adsanity' )
			);
			printf(
				'<a href="#" id="ad_scheduler_reset" class="button">%s</a>',
				esc_html__( 'Publish Forever', 'adsanity' )
			);
			echo sprintf(
				'<a href="#" id="cancel_schedule_change">%s</a>',
				esc_html__( 'Cancel', 'adsanity' )
			);
			echo '</p>';
			echo '</div>';
			echo '</div>';

		}

	}

	/**
	 * Renders the metabox to select a size for an Ad.
	 *
	 * @param WP_Post $post the complete post object for the ad.
	 */
	public static function ad_size_metabox( $post ) {

		// SIZE.
		$options      = get_option( ADSANITY_ADMIN_OPTIONS, array() );
		$sizes        = apply_filters( 'adsanity_ad_sizes', $options['sizes'] );
		$default_size = apply_filters( 'adsanity_ad_size_default', '300x250' );
		$size         = Adsanity\Meta_Data::get( 'post', $post->ID, '_size', true );

		echo sprintf( '<label for="size">%s</label> ', esc_html__( 'Ad Size', 'adsanity' ) );
		echo '<select name="size" id="size" size="1">';
		foreach ( $sizes as $val => $name ) {

			echo sprintf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $val ),
				( empty( $size ) && $val === $default_size ? 'selected="selected"' : selected( $size, $val ) ),
				esc_html( $name )
			);

		}
		echo '</select>';

		printf(
			'<div class="ad-placeholder ad-%s">
				<div class="adsanity-inner">
					<p><strong>%s</strong></p>
				</div>
			</div>',
			! empty( $size ) ? esc_attr( $size ) : esc_attr( $default_size ),
			esc_html__( 'Size Reference', 'adsanity' )
		);

	}

	/**
	 * Notes about the Ad or Advertiser.
	 *
	 * @param WP_Post $post the post object that's being edited.
	 * @return void
	 */
	public static function ad_notes_metabox( $post ) {

		echo sprintf(
			'<p>%s</p>',
			esc_html__( "If you'd like to store internal notes about this ad, enter them here. Anything entered in the field below is only visible on this screen." )
		);
		echo sprintf(
			'<textarea name="notes" id="ad-notes">%s</textarea>',
			esc_textarea( Adsanity\Meta_Data::get( 'post', $post->ID, '_notes', true ) )
		);

	}

	/**
	 * Renders the metabox with details specific to hosted ads.
	 *
	 * @param WP_Post $post the complete post object for the ad.
	 */
	public static function internal_ad_details_metabox( $post ) {

		// OPEN IN A NEW WINDOW?
		echo sprintf(
			'<label for="target"><input name="target" type="checkbox" value="1" id="target" %s> %s</label>',
			checked( '1', Adsanity\Meta_Data::get( 'post', $post->ID, '_target', true ), false ),
			esc_html__( 'Open in a new window?', 'adsanity' )
		);

		// URL.
		echo sprintf(
			'<label for="url">%s</label>',
			esc_html__( 'Tracking URL', 'adsanity' )
		);
		echo sprintf(
			'<input type="text" name="url" value="%s" id="url">',
			esc_attr( Adsanity\Meta_Data::get( 'post', $post->ID, '_url', true ) )
		);

	}

	/**
	 * Renders the stats metabox for hosted ads. Shows views, clicks, and click through rates.
	 *
	 * @param WP_Post $post the complete post object for the ad.
	 */
	public static function ad_chart_metabox( $post ) {

		$today = adsanity_get_timestamp();
		$start = strtotime( '-6 days', $today );

		echo '<script type="text/javascript">' . PHP_EOL;

		// Views data array.
		$views_past_7 = 0;
		$views        = array();
		for ( $i = $start; $i <= $today; $i += DAY_IN_SECONDS ) {

			$viewcount     = Adsanity\Meta_Data::get( 'post', $post->ID, sprintf( '_views-%s', $i ), true );
			$views[]       = sprintf( '[%d,%d]', ( $i * 1000 ), ( ! $viewcount ? 0 : $viewcount ) );
			$views_past_7 += intval( $viewcount );

		}
		echo sprintf(
			'var views = { data: [%s], label: "Views", color: "#e9275d" };' . PHP_EOL,
			esc_js( implode( ',', $views ) )
		);

		// Clicks data array.
		$clicks_past_7 = 0;
		$clicks        = array();
		for ( $i = $start; $i <= $today; $i += DAY_IN_SECONDS ) {

			$clickcount     = Adsanity\Meta_Data::get( 'post', $post->ID, sprintf( '_clicks-%s', $i ), true );
			$clicks[]       = sprintf( '[%d,%d]', ( $i * 1000 ), ( ! $clickcount ? 0 : $clickcount ) );
			$clicks_past_7 += intval( $clickcount );

		}
		echo sprintf(
			'var clicks = { data: [%s], label: "Clicks", color: "#4fb5d2" };' . PHP_EOL,
			esc_js( implode( ',', $clicks ) )
		);

		echo '</script>' . PHP_EOL;

		$custom     = \AdSanity\Meta_Data::get( 'post', $post->ID );
		$viewcount  = 0;
		$clickcount = 0;
		foreach ( $custom as $key => $arr ) {

			if ( strpos( $key, '_views' ) !== false ) {

				$viewcount += intval( $arr[0] );

			} elseif ( strpos( $key, '_clicks' ) !== false ) {

				$clickcount += intval( $arr[0] );

			}
		}
		$ctr = 0;
		if ( intval( $clickcount ) > 0 && intval( $viewcount ) > 0 ) {

			$ctr = intval( $clickcount ) / intval( $viewcount ) * 100;

		}

		echo sprintf(
			'<h4>%s</h4>',
			esc_html__( 'Stats Summary', 'adsanity' )
		);

		// Summary.
		echo '<ul class="subsubsub" style="float: none;">';

		// View Count.
		echo sprintf(
			'<li>%s %s</li>',
			esc_html( number_format_i18n( $viewcount ) ),
			esc_html__( 'total views', 'adsanity' )
		);

		// Click Count.
		echo sprintf(
			'<li class="click-only">&nbsp;| %s %s |&nbsp;</li>',
			esc_html( number_format_i18n( $clickcount ) ),
			esc_html__( 'total clicks', 'adsanity' )
		);

		// Click Through Rate.
		echo sprintf(
			'<li class="click-only">%s%% %s</li>',
			esc_html( number_format_i18n( $ctr ) ),
			esc_html__( 'total CTR', 'adsanity' )
		);

		echo '</ul>';

		// View Chart.
		echo sprintf(
			'<h4 class="clear">%s %s</h4>',
			esc_html( number_format_i18n( intval( $views_past_7 ) ) ),
			esc_html__( 'Views in the past 7 days', 'adsanity' )
		);
		echo '<canvas id="ad-chart-views-canvas"></canvas>';

		// Click Chart.
		echo sprintf(
			'<h4 class="clear">%s %s</h4>',
			esc_html( number_format_i18n( intval( $clicks_past_7 ) ) ),
			esc_html__( 'Clicks in the past 7 days', 'adsanity' )
		);
		echo '<canvas class="click-only" id="ad-chart-clicks-canvas"></canvas>';

	}

	/**
	 * Hooks into the edit form for the Ads CPT and injects the Ad Type tab bar
	 */
	public static function edit_form_advanced() {

		global $post;
		if ( get_post_type() === self::$type || get_query_var( 'post_type' ) === self::$type ) {

			require_once ADSANITY_VIEWS . 'tabs.php';

		}

	}

	/**
	 * Include custom tracking template for Single Ad CPT on the front end.
	 *
	 * @param  string $template The full path to the template file.
	 * @return string           The full path to the template file.
	 */
	public static function template_include( $template = null ) {

		if ( is_single() && ( get_post_type() === self::$type || get_query_var( 'post_type' ) === self::$type ) ) {

			return ADSANITY_THEME . 'single-ads.php';

		}

		return $template;

	}

	/**
	 * Adds expiration status to the post list in the WordPress dashboard for styling.
	 *
	 * @param  array $classes An array of class names.
	 * @return array          An array of class names.
	 */
	public static function post_class( $classes ) {

		global $post, $current_screen;
		if (
			is_object( $current_screen ) &&
			property_exists( $current_screen, 'id' ) &&
			'edit-ads' === $current_screen->id
		) {

			$start_date = Adsanity\Meta_Data::get( 'post', $post->ID, '_start_date', true );
			$end_date   = Adsanity\Meta_Data::get( 'post', $post->ID, '_end_date', true );

			if ( $start_date && $end_date ) {

				if ( time() > $end_date ) {

					$classes[] = 'expired';

				} elseif ( time() >= $end_date - WEEK_IN_SECONDS ) {

					$classes[] = 'expiring';

				}
			}
		}

		return $classes;

	}

	/**
	 * Automatically hooks into the save post action to capture and sanitize meta date.
	 *
	 * @param  integer $post_id The ID of the post that's being saved.
	 * @return integer          The ID of the post that's being saved.
	 */
	public static function save_post( $post_id ) {

		// autosaves kill post meta.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// user has permission.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// verify intent.
		$nonce_field = sprintf( '%s_nonce', static::$type );
		if (
			! isset( $_POST[ $nonce_field ] ) ||
			! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce_field ] ) ), sprintf( '%s-save_postmeta', static::$type ) )
		) {
			return $post_id;
		}

		$dirty       = $_POST;
		$dirty_files = $_FILES;

		// loop through the post and sanitize the data.
		foreach ( static::$metafields as $name => $meta ) {

			$clean = false;

			// field was submitted and not empty.
			if (
				isset( $dirty[ $name ] ) ||
				( isset( $dirty_files[ $name ] ) && 'file' === $meta[1] ) ||
				'date' === $meta[1]
			) {

				// SANITIZE DATA.
				switch ( $meta[1] ) {

					case 'embed':
						$dirty[ $name ] = stripslashes_deep( $dirty[ $name ] );
						$clean          = wp_kses(
							$dirty[ $name ],
							array(
								'iframe' => array(
									'src'             => array(),
									'frameborder'     => array(),
									'allowfullscreen' => array(),
								),
								'object' => array(),
								'param'  => array(
									'name'  => array(),
									'value' => array(),
								),
								'embed'  => array(
									'src'               => array(),
									'type'              => array(),
									'allowscriptaccess' => array(),
									'allowfullscreen'   => array(),
								),
							)
						);
						break;

					case 'str':
						$clean = wp_kses_data( $dirty[ $name ] );
						break;

					case 'url':
						$clean = esc_url_raw( $dirty[ $name ] );
						break;

					case 'phone':
						$matches = array();
						preg_match( '/^\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/', $dirty[ $name ], $matches );
						$clean = sprintf( '%s.%s.%s', $matches[1], $matches[2], $matches[3] );
						break;

					case 'email':
						$email_pattern = '/^(([a-zA-Z0-9_.\-+!#$&\'*+=?^`{|}~])+\@((([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+|localhost) *,? *)+$/';
						if ( preg_match( $email_pattern, $dirty[ $name ] ) ) {
							$clean = $dirty[ $name ];
						}
						break;

					case 'float':
						$clean = floatval( $dirty[ $name ] );
						break;

					case 'int':
						$clean = intval( $dirty[ $name ] );
						break;

					case 'date':
						if (
							isset( $dirty[ $name . '_day' ] ) &&
							isset( $dirty[ $name . '_month' ] ) &&
							isset( $dirty[ $name . '_year' ] )
						) {
							$clean = strtotime( $dirty[ $name . '_day' ] . ' ' . $dirty[ $name . '_month' ] . ' ' . $dirty[ $name . '_year' ] );
						} else {
							$clean = intval( $dirty[ $name ] );
						}
						break;

					case 'bool':
						$clean = isset( $dirty[ $name ] ) ? (bool) $dirty[ $name ] : 0;
						break;

					case 'path':
						if ( is_file( $dirty[ $name ] ) ) {
							$clean = stripslashes_deep( $dirty[ $name ] );
						} else {
							$clean = wp_json_encode( array( 'error' => 'File does not exist.' ) );
						}
						break;

					case 'file':
						if ( empty( $dirty_files[ $name ]['error'] ) ) {
							/* Require WordPress utility functions for handling media uploads */
							require_once ABSPATH . '/wp-admin/includes/media.php';
							require_once ABSPATH . '/wp-admin/includes/image.php';
							require_once ABSPATH . '/wp-admin/includes/file.php';
							$clean = media_handle_upload( $name, $post_id );
							if ( is_wp_error( $clean ) ) {
								$clean = false;
								return false;
							}
						}
						break;

					case 'raw':
						$clean = $dirty[ $name ];
						break;

					case 'editor':
						$clean = wp_kses_post( $dirty[ $name ] );
						break;

					case 'color':
						$clean = sanitize_hex_color( $dirty[ $name ] );
						break;

				}

				if ( true === (bool) $clean ) {

					Adsanity\Meta_Data::update( 'post', $post_id, $meta[0], $clean );

				} elseif ( empty( $clean ) && 'file' !== $meta[1] ) {

					Adsanity\Meta_Data::delete( 'post', $post_id, $meta[0] );

				}
			} elseif ( ! isset( $dirty[ $name ] ) && 'bool' === $meta[1] ) {

				Adsanity\Meta_Data::delete( 'post', $post_id, $meta[0] );

			}
		}

	}

}
new AdSanity_Ads_CPT();
