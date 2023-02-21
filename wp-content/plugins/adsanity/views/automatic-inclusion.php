<?php
/**
 * The automatic inclusion view.
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
 * Separates the markup into smaller, more readable sections
 */
class AdSanity_Automatic_Inclusion_View {

	/**
	 * The options for automatic inclusion.
	 *
	 * @var array $options
	 */
	private static $options;

	/**
	 * Initialize the class and draw.
	 *
	 * @return void
	 */
	public static function init() {

		self::$options = wp_parse_args(
			get_option( ADSANITY_ADMIN_OPTIONS, array() ),
			array(
				'adsanity_show_in_content' => 0,
				'adsanity_in_content_rules' => array(
					array(
						'post_types'       => array(),
						'position'         => '',
						'alignment'        => '',
						'ad_group'         => null,
						'position_dynamic' => '',
						'position_num'     => 1,
						'position_element' => '',
					),
				),
			)
		);

		self::draw();

	}

	/**
	 * Draws the markup
	 */
	public static function draw() {

		echo '<div class="automatic-inclusion-rules">';
		echo '<p><label for="adsanity_show_in_content">';
		echo sprintf(
			'<input type="checkbox" name="%s[adsanity_show_in_content]" id="adsanity_show_in_content" value="1" %s /> ',
			esc_attr( ADSANITY_ADMIN_OPTIONS ),
			checked( self::$options['adsanity_show_in_content'], '1', false )
		);
		echo esc_html__( 'Automatically place ads in content?', 'adsanity' );
		echo '</label></p>';

		foreach ( self::$options['adsanity_in_content_rules'] as $key => $rule ) {
			echo '<div class="ruleset">';
				self::post_types( $key, $rule );
				echo '<hr>';
				self::position( $key, $rule );
				echo '<hr>';
				self::alignment( $key, $rule );
				echo '<hr>';
				self::ad_group( $key, $rule );
				echo '<hr>';
				self::max_width( $key, $rule );
				self::type( $key, $rule );
				echo '<hr>';

				/**
				 * After fields, but before buttons for automatic inclusion.
				 *
				 * @param integer $key The index of this rule.
				 * @param array   $rule The specific rule.
				 */
				do_action( 'adsanity_automatic_inclusion_after_fields', $key, $rule );

				self::buttons( $key, $rule );
			echo '</div>';
		}

	}

	/**
	 * Post type multiselect.
	 *
	 * @param integer $key The index of this rule.
	 * @param array   $rule The specific rule.
	 * @return void
	 */
	public static function post_types( $key, $rule ) {

		$post_types = get_post_types( array( 'show_ui' => true ) );
		$options_markup = '';
		$ignored_post_types = apply_filters(
			'adsanity_automatic_inclusion_ignored_post_types',
			[
				'ads',
				'attachment',
				'wp_block',
			]
		);
		foreach ( $post_types as $post_type ) {
			if ( in_array( $post_type, $ignored_post_types, true ) ) {
				continue;
			}
			$options_markup .= sprintf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $post_type ),
				selected( in_array( $post_type, $rule['post_types'] ), true, false ),
				esc_html( $post_type )
			);
		}

		printf(
			'<label>
				<span>%s</span>
				<select name="%s[]" class="post-types" multiple>%s</select>
			</label>',
			esc_html__( 'Post Types: ', 'adsanity' ),
			self::create_name( $key, 'post_types' ),
			$options_markup
		);

	}

	/**
	 * Position radio buttons and dynamic inputs.
	 *
	 * @param integer $key The index of this rule.
	 * @param array   $rule The specific rule.
	 * @return void
	 */
	public static function position( $key, $rule ) {
		printf(
			'<fieldset class="position">
				<legend>%s</legend>
			',
			esc_html__( 'Position: ', 'adsanity' )
		);

			$label_format = '<label class="before">
				<input type="radio" name="%s[adsanity_in_content_rules][%s][position]" value="%s" %s>
				%s
			</label>';

			if ( empty( $rule['position'] ) ) {
				$position_value = 'before';
			} else {
				$position_value = $rule['position'];
			}

			// Before
			printf(
				$label_format,
				esc_attr( ADSANITY_ADMIN_OPTIONS ),
				esc_attr( $key ),
				'before',
				checked( 'before' === $position_value, true, false ),
				esc_html( 'Before Content', 'adsanity' )
			);

			// After
			printf(
				$label_format,
				esc_attr( ADSANITY_ADMIN_OPTIONS ),
				esc_attr( $key ),
				'after',
				checked( 'after' === $position_value, true, false ),
				esc_html( 'After Content', 'adsanity' )
			);

			// Dynamic inner fields
			$dynamic_inner = sprintf(
				'<select class="dynamic" name="%s[adsanity_in_content_rules][%s][position_dynamic]"%s>
					<option value="%s" %s>%s</option>
					<option value="%s" %s>%s</option>
				</select>',
				esc_attr( ADSANITY_ADMIN_OPTIONS ),
				esc_attr( $key ),
				'dynamic' !== $position_value ? ' disabled' : '',
				'before',
				selected( 'before' === $rule['position_dynamic'], true, false ),
				esc_html__( 'Before', 'adsanity' ),
				'after',
				selected( 'after' === $rule['position_dynamic'], true, false ),
				esc_html__( 'After', 'adsanity' )
			);

			$dynamic_inner .= sprintf(
				'<input class="dynamic" name="%s[adsanity_in_content_rules][%s][position_num]" type="number" value="%s" min="1"%s>',
				esc_attr( ADSANITY_ADMIN_OPTIONS ),
				esc_attr( $key ),
				$rule['position_num'],
				'dynamic' !== $position_value ? ' disabled' : ''
			);

			$dynamic_inner .= sprintf(
				'<select class="dynamic" name="%s[adsanity_in_content_rules][%s][position_element]"%s>
					<option value="paragraph" %s>%s</option>
					<option value="paragraph-list" %s>%s</option>
					<option value="block" %s>%s</option>
				</select>',
				esc_attr( ADSANITY_ADMIN_OPTIONS ),
				esc_attr( $key ),
				'dynamic' !== $position_value ? ' disabled' : '',
				selected( 'paragraph' === $rule['position_element'], true, false ),
				esc_html__( 'paragraphs(p)', 'adsanity' ),
				selected( 'paragraph-list' === $rule['position_element'], true, false ),
				esc_html__( 'paragraphs and lists (p, ul, ol)', 'adsanity' ),
				selected( 'block' === $rule['position_element'], true, false ),
				esc_html__( 'blocks', 'adsanity' )
			);

			// Dynamic
			printf(
				// phpcs:disable
				$label_format,
				// phpcs:enable
				esc_attr( ADSANITY_ADMIN_OPTIONS ),
				esc_attr( $key ),
				'dynamic',
				checked( 'dynamic' === $position_value, true, false ),
				// phpcs:disable
				$dynamic_inner
				// phpcs:enable
			);
		echo '</fieldset>';
	}

	/**
	 * Alignment radio buttons.
	 *
	 * @param integer $key The index of this rule.
	 * @param array   $rule The specific rule.
	 * @return void
	 */
	public static function alignment( $key, $rule ) {
		$input_markup = '';
		$input_format = '<input class="%1$s" id="%1$s-%2$s" type="radio" name="%3$s[adsanity_in_content_rules][%2$s][alignment]" value="%1$s" %4$s/><label class="%1$s" for="%1$s-%2$s">%5$s</label>';

		$input_markup .= sprintf(
			$input_format,
			'none',
			esc_attr( $key ),
			esc_attr( ADSANITY_ADMIN_OPTIONS ),
			checked( 'none' === $rule['alignment'], true, false ),
			esc_html__( 'None', 'adsanity' )
		);

		$input_markup .= sprintf(
			$input_format,
			'left',
			esc_attr( $key ),
			esc_attr( ADSANITY_ADMIN_OPTIONS ),
			checked( 'left' === $rule['alignment'], true, false ),
			esc_html__( 'Left', 'adsanity' )
		);

		$input_markup .= sprintf(
			$input_format,
			'center',
			esc_attr( $key ),
			esc_attr( ADSANITY_ADMIN_OPTIONS ),
			checked( 'center' === $rule['alignment'], true, false ),
			esc_html__( 'Center', 'adsanity' )
		);

		$input_markup .= sprintf(
			$input_format,
			'right',
			esc_attr( $key ),
			esc_attr( ADSANITY_ADMIN_OPTIONS ),
			checked( 'right' === $rule['alignment'], true, false ),
			esc_html__( 'Right', 'adsanity' )
		);

		printf(
			'<fieldset class="alignment">
				<legend>%s</legend>
				%s
			</fieldset>',
			esc_html__( 'Alignment: ', 'adsanity' ),
			// phpcs:disable
			$input_markup
			// phpcs:enable
		);
	}

	/**
	 * Ad Group select.
	 *
	 * @param integer $key The index of this rule.
	 * @param array   $rule The specific rule.
	 * @return void
	 */
	public static function ad_group( $key, $rule ) {

		$options_markup = '';
		$groups         = get_terms( array( 'taxonomy' => 'ad-group' ) );
		if ( count( $groups ) > 0 ) {

			foreach ( $groups as $group ) {
				$options_markup .= sprintf(
					'<option value="%s" %s>%s</option>',
					esc_attr( $group->term_id ),
					selected( $group->term_id === $rule['ad_group'], true, false ),
					esc_html( $group->name )
				);
			}
		} else {

			printf(
				'<p>%s <a href="%s" class="button-secondary">%s</a></p>',
				esc_html__( 'Before using this feature, you must first create an Ad Group and assign ads to the group.', 'adsanity' ),
				esc_url(
					add_query_arg(
						array( 'taxonomy' => 'ad-group', 'post_type' => 'ads' ),
						admin_url( 'edit-tags.php' )
					)
				),
				esc_html__( 'Create an Ad Group Now.', 'adsanity' )
			);
			return;

		}

		printf(
			'<label>
				<span>%s</span>
				<select name="%s" class="ad-groups">
					%s
				</select>
			</label>',
			esc_html__( 'Ad Group: ', 'adsanity' ),
			// phpcs:disable
			self::create_name( $key, 'ad_group' ),
			$options_markup
			// phpcs:enable
		);

	}

	/**
	 * Max width fields.
	 *
	 * @param integer $key The index of this rule.
	 * @param array   $rule The specific rule.
	 * @return void
	 */
	public static function max_width( $key, $rule ) {

		$max_width_enabled = isset( $rule['max_width'] ) && $rule['max_width'];
		$max_width = 100;
		if ( $max_width_enabled ) {
			$max_width = intval( $rule['max_width'] );
		}

		printf(
			'<div class="adsanity-automatic-inclusion-max-width-enabled-container">
				<label for="%1$s">%2$s</label>
				<input id="%1$s" name="%1$s" %3$s type="checkbox">
			</div>',
			\esc_attr( self::create_name( $key, 'max-width-enabled' ) ),
			\esc_html__( 'Max Width Enabled?', 'adsanity' ),
			\checked( $max_width_enabled, true, false )
		);

		printf(
			'
			<div class="adsanity-automatic-inclusion-max-width-container">
				<label for="%1$s">%2$s</label>
				<input id="%1$s" name="%1$s" %3$s value="%4$s" type="number">
			</div>',
			\esc_attr( self::create_name( $key, 'max-width' ) ),
			\esc_html__( 'Max Width (px):', 'adsanity' ),
			$max_width_enabled ? '' : 'disabled="disabled"',
			esc_attr( $max_width )
		);

	}

	/**
	 * Print the type selector.
	 *
	 * @param integer $key The index of this rule.
	 * @param array   $rule The specific rule.
	 * @return void
	 */
	public static function type( $key, $rule ) {

		/**
		 * Modify automatic inclusion types.
		 *
		 * @param array $type The types available to choose.
		 */
		$types = apply_filters(
			'adsanity_automatic_inclusion_types',
			[
				'random_ad' => __( 'Random Ad', 'adsanity' ),
			]
		);

		// Only show this field if there is more than one option.
		if ( count( $types ) < 2 ) {
			return;
		}

		echo '<hr>';

		$options_markup = '';
		foreach ( $types as $type_key => $type_value ) {
			$options_markup .= sprintf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $type_key ),
				selected( isset( $rule['type'] ) && $type_key === $rule['type'], true, false ),
				esc_html( $type_value )
			);
		}

		printf(
			'<label>
				<span>%s:</span>
				<select name="%s" class="adsanity-automatic-inclusion-type-control">%s</select>
			</label>',
			esc_html__( 'Type', 'adsanity' ),
			// phpcs:disable
			self::create_name( $key, 'type' ),
			$options_markup
			// phpcs:enable
		);

	}

	/**
	 * Buttons for adding/deleting rules.
	 *
	 * @param integer $key The index of this rule.
	 * @param array   $rule The specific rule.
	 * @return void
	 */
	public static function buttons( $key, $rule ) {
		?>
		<div class="buttons">
			<button type="button" class="add-rule button"><?php esc_html_e( 'Add Rule', 'adsanity' ); ?></button>
			<button type="button" class="remove-rule button" disabled><?php esc_html_e( 'Remove Rule', 'adsanity' ); ?></button>
		</div>
		<?php
	}

	/**
	 * Creates content for the name attribute.
	 *
	 * @param integer $key The index of this rule.
	 * @param string  $type The type to use in the name.
	 * @return string The content for the name attribute
	 */
	private static function create_name( $key, $type ) {

		return sprintf(
			'%s[adsanity_in_content_rules][%s][%s]',
			esc_attr( ADSANITY_ADMIN_OPTIONS ),
			esc_attr( $key ),
			esc_attr( $type )
		);

	}

}

AdSanity_Automatic_Inclusion_View::init();
