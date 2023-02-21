<?php
/**
 * The ads.text class
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
 * Handles all of the Ads.txt functionality
 */
class AdSanity_AdsTxt {

	/**
	 * Kicks off WordPress integrations.
	 *
	 * @return void
	 */
	public static function hooks() {

		add_action( 'init', array( __CLASS__, 'request' ) );
		add_action( 'admin_init', array( __CLASS__, 'check_for_adstxt_file' ) );

	}

	/**
	 * Display the contents of ads.txt when requested.
	 *
	 * @return void
	 */
	public static function request() {

		$request = esc_url_raw( $_SERVER['REQUEST_URI'] );
		$home_url = home_url();
		$adstxt_url = home_url( '/ads.txt' );
		$uri = str_replace( $home_url, '', $adstxt_url );

		if ( $uri === $request ) {

			$options = wp_parse_args(
				get_option( ADSANITY_ADMIN_OPTIONS, array() ),
				array(
					'adstxt' => '',
				)
			);

			if ( ! empty( $options['adstxt'] ) ) {

				header( 'Content-Type: text/plain' );
				echo esc_html( $options['adstxt'] );
				die();

			}
		}

	}

	/**
	 * Scans through ad units looking for Google ads so we can automatically add the google ads.txt entry
	 *
	 * @param mixed $adstxt bool, int, or string
	 * @return void
	 */
	public static function check_for_google_adstxt( $adstxt = false ) {

		if ( doing_action( 'save_post' ) ) {

			// autosaves kill post meta.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $adstxt;
			}

			// user has permission.
			if ( ! current_user_can( 'edit_post', $adstxt ) ) {
				return $adstxt;
			}

			// verify intent.
			if (
				! isset( $_POST['ads_nonce'] ) ||
				! wp_verify_nonce( wp_unslash( $_POST['ads_nonce'] ), 'ads-save_postmeta' )
			) {
				return $adstxt;
			}
		}

		$valid = array();
		// save_post = 1, update = '', settings = 'text to check'
		if ( false === $adstxt || doing_action( 'save_post' ) ) {

			$options = wp_parse_args(
				get_option( ADSANITY_ADMIN_OPTIONS, array() ),
				array(
					'adstxt' => '',
				)
			);
			$adstxt = $options['adstxt'];

		}

		$entries = preg_split( '/\r\n|\r|\n/', $adstxt );
		foreach ( $entries as $i => $entry ) {

			$line = $i + 1;
			$result = self::validate_line( $entry, $line );
			$valid[] = $result['sanitized'];

		}

		global $wpdb;
		$google_pub_ids = array();
		$google_ads = $wpdb->get_results("
			SELECT meta_value as code
			FROM {$wpdb->postmeta}
			WHERE meta_key = '_code'
			AND meta_value LIKE '%googlesyndication.com%'
		");

		if ( $google_ads ) {

			foreach ( $google_ads as $ad ) {

				// Test if its google asyncron ad.
				if ( preg_match( '/data-ad-client=/', $ad->code ) ) {

					// GOOGLE ASYNCRON
					// Get g_data_ad_client.
					$explode_ad_code = explode( 'data-ad-client', $ad->code );
					preg_match( '#"([a-zA-Z0-9-\s]+)"#', $explode_ad_code[1], $matches_add_client );
					$publisher_id = str_replace( array( '"', ' ' ), array( '' ), $matches_add_client[1] );

				} else {

					// GOOGLE SYNCRON
					// Get g_data_ad_client.
					$explode_ad_code = explode( 'google_ad_client', $ad->code );
					preg_match( '#"([a-zA-Z0-9-\s]+)"#', $explode_ad_code[1], $matches_add_client );
					$publisher_id = str_replace( array( '"', ' ' ), array( '' ), $matches_add_client[1] );

				}
				$valid[] = sprintf(
					'google.com, %s, DIRECT, f08c47fec0942fa0 #adsanity-google',
					str_replace( 'ca-', '', $publisher_id )
				);
			}
		}

		$options['adstxt'] = implode( PHP_EOL, array_unique( $valid ) );

		// save_post = 1, update = '', settings = 'text to check'
		if ( doing_action( 'save_post' ) ) {

			update_option( ADSANITY_ADMIN_OPTIONS, $options );

		} else {

			return $options['adstxt'];

		}

	}

	/**
	 * Checks to see if there's a file in the root of the site called ads.txt
	 *
	 * @return void
	 */
	public static function check_for_adstxt_file() {

		$adstxt_file = trailingslashit( get_home_path() ) . 'ads.txt';
		if ( is_file( $adstxt_file ) ) {

			add_action( 'admin_notices', array( __CLASS__, 'existing_adstxt_file_notice' ) );

		}

	}

	/**
	 * Puts up a notice if there's a static file called ads.txt in the root.
	 *
	 * @return void
	 */
	public static function existing_adstxt_file_notice() {
		echo sprintf(
			'<div id="adsanity-error-existing_adstxt" class="error notice"><p>%s</p></div>',
			sprintf(
				'%s (%s)! %s <a href="%s">%s</a> %s',
				esc_html__( 'Ads.txt file detected', 'adsanity' ),
				esc_html( trailingslashit( get_home_path() ) . 'ads.txt' ),
				esc_html__( 'Please copy the contents of this file into the ', 'adsanity' ),
				esc_url(
					add_query_arg(
						array(
							'post_type' => 'ads',
							'page' => 'adsanity-settings'
						),
						admin_url( 'edit.php' )
					)
				),
				esc_html__( 'ads.txt settings', 'adsanity' ),
				esc_html__( 'and delete the file.', 'adsanity' )
			)
		);
	}

	/**
	 * Validate a single line while standing on the shoulders of giants
	 *
	 * @param string $line        The line to validate.
	 * @param string $line_number The line number being evaluated.
	 *
	 * @return array {
	 *     @type string $sanitized Sanitized version of the original line.
	 *     @type array  $errors    Array of errors associated with the line.
	 * }
	 */
	public static function validate_line( $line, $line_number ) { // adsanity_validate_adstxt_line

		$domain_regex = '/^((?=[a-z0-9-]{1,63}\.)(xn--)?[a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,63}$/i';
		$errors       = array();

		if ( empty( $line ) ) {

			$sanitized = '';

		} elseif ( 0 === strpos( $line, '#' ) ) { // This is a full-line comment.

			$sanitized = wp_strip_all_tags( $line );

		} elseif ( 1 < strpos( $line, '=' ) ) { // This is a variable declaration.

			// The spec currently supports CONTACT and SUBDOMAIN.
			if ( ! preg_match( '/^(CONTACT|SUBDOMAIN)=/i', $line ) ) {

				$errors[] = array(
					'line'    => $line_number,
					'type'    => 'warning',
					'message' => __( 'Unrecognized variable', 'adsanity' ),
				);

			} elseif ( 0 === stripos( $line, 'subdomain=' ) ) { // Subdomains should be, well, subdomains.

				// Disregard any comments.
				$subdomain = explode( '#', $line );
				$subdomain = $subdomain[0];

				$subdomain = explode( '=', $subdomain );
				array_shift( $subdomain );

				// If there's anything other than one piece left something's not right.
				if ( 1 !== count( $subdomain ) || ! preg_match( $domain_regex, $subdomain[0] ) ) {

					$subdomain = implode( '', $subdomain );
					$errors[] = array(
						'line'    => $line_number,
						'type'    => 'warning',
						'message' => sprintf(
							/* translators: %s: Subdomain */
							__( '"%s" does not appear to be a valid subdomain', 'adsanity' ),
							esc_html( $subdomain )
						),
					);

				}
			}

			$sanitized = wp_strip_all_tags( $line );
			unset( $subdomain );

		} else { // Data records: the most common.

			// Disregard any comments.
			$record = explode( '#', $line );
			$record = $record[0];

			// Record format: example.exchange.com,pub-id123456789,RESELLER|DIRECT,tagidhash123(optional).
			$fields = explode( ',', $record );

			if ( 3 <= count( $fields ) ) {

				$exchange     = trim( $fields[0] );
				$pub_id       = trim( $fields[1] );
				$account_type = trim( $fields[2] );

				if ( ! preg_match( $domain_regex, $exchange ) ) {

					$errors[] = array(
						'line'    => $line_number,
						'type'    => 'warning',
						'message' => sprintf(
							/* translators: %s: Exchange domain */
							__( '"%s" does not appear to be a valid exchange domain', 'adsanity' ),
							esc_html( $exchange )
						),
					);

				}

				if ( ! preg_match( '/^(RESELLER|DIRECT)$/i', $account_type ) ) {

					$errors[] = array(
						'line'    => $line_number,
						'type'    => 'error',
						'message' => __( 'Third field should be RESELLER or DIRECT', 'adsanity' ),
					);

				}

				if ( isset( $fields[3] ) ) {

					$tag_id = trim( $fields[3] );

					// TAG-IDs appear to be 16 character hashes.
					// TAG-IDs are meant to be checked against their DB - perhaps good for a service or the future.
					if ( ! empty( $tag_id ) && ! preg_match( '/^[a-f0-9]{16}$/', $tag_id ) ) {

						$errors[] = array(
							'line'    => $line_number,
							'type'    => 'warning',
							'message' => sprintf(
								/* translators: %s: TAG-ID */
								__( '"%s" does not appear to be a valid TAG-ID', 'adsanity' ),
								esc_html( $fields[3] )
							),
						);

					}
				}
				$sanitized = wp_strip_all_tags( $line );

			} else {

				// Not a comment, variable declaration, or data record; therefore, invalid.
				// Early on we commented the line out for safety but it's kind of a weird thing to do with a JS AYS.
				$sanitized = wp_strip_all_tags( $line );

				$errors[] = array(
					'line'    => $line_number,
					'type'    => 'error',
					'message' => __( 'Invalid record', 'adsanity' ),
				);

			}

			unset( $record, $fields );

		}

		return array(
			'sanitized' => $sanitized,
			'errors'    => $errors,
		);
	}

}

AdSanity_AdsTxt::hooks();
