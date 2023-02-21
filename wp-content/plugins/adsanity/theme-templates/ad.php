<?php
/**
 * The ad theme template.
 *
 * @package WordPress
 * @subpackage AdSanity
 * @since 1.9
 * @version 1.0
 * @uses $ad a WP_Post object for the ad.
 * @uses $size the size of the ad.
 * @uses $values array of values passed into the template tag.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this file directly.' );
}

// Check if $ad is a WP_Post object and if it isn't get the post.
if ( ! isset( $ad ) || ! is_a( $ad, '\WP_Post' ) ) {

	global $post;
	if ( 'ads' === $post->post_type ) {

		// Ordered Ad Group Widget.
		$ad = get_post( get_the_ID() );

	}

}

$ad_code = Adsanity\Meta_Data::get( 'post', $ad->ID, '_code', true );
$ad_text = Adsanity\Meta_Data::get( 'post', $ad->ID, '_text', true );

$ad_permalink = get_permalink( $ad->ID );
$classes      = array(
	sprintf( 'ad-%s', $size ),
	sprintf( 'adsanity-%s', $size ),
);

// Set by the wrapper function that includes this file
// Do not include it if this is an ad group with columns.
if ( isset( $values['align'] ) && ! isset( $num_columns ) ) {

	$classes[] = esc_attr( $values['align'] );
	$classes[] = esc_attr( sprintf( 'adsanity-%s', $values['align'] ) );

}

$adsanity_post_class = apply_filters( 'adsanity_post_class', $classes, $ad, $ad->ID );
$adsanity_post_id    = apply_filters( 'adsanity_post_id', sprintf( 'ad-%s', $ad->ID ) );

// Trigger custom code before the ad wrapper.
do_action( 'adsanity_before_ad_wrapper', $ad, $ad->ID );
?>

<div id="<?php echo esc_attr( $adsanity_post_id ); ?>" class="<?php echo esc_attr( implode( ' ', $adsanity_post_class ) ); ?>"
<?php
if ( isset( $values['max_width'] ) && $values['max_width'] ) {
	printf( 'style="max-width:%dpx"', intval( $values['max_width'] ) );
}
?>
><div class="adsanity-inner">

<?php

// Trigger custom code inside the wrapper, before the ad.
do_action( 'adsanity_before_ad', $ad, $ad->ID );

// Hosted Ad.
if ( has_post_thumbnail( $ad->ID ) ) {

	echo sprintf(
		'<a rel="nofollow" href="%s" %s>%s</a>',
		esc_attr( esc_url( $ad_permalink ) ),
		boolval( Adsanity\Meta_Data::get( 'post', $ad->ID, '_target', true ) ) ? ' target="_blank"' : '', // phpcs:ignore WordPress.Security.EscapeOutput
		get_the_post_thumbnail(
			$ad->ID,
			'full',
			array(
				'loading' => false,
				'class'   => 'no-lazy-load',
			)
		) // phpcs:ignore WordPress.Security.EscapeOutput
	);

} elseif ( boolval( $ad_code ) ) {

	$ad_code = str_replace(
		array(
			'%link%',
			'[link]',
			'[timestamp]',
		),
		array(
			esc_attr( esc_url( $ad_permalink ) ),
			esc_attr( esc_url( add_query_arg( 'r', '', $ad_permalink ) ) ),
			esc_attr( time() ),
		),
		$ad_code
	);
	echo $ad_code; // phpcs:ignore WordPress.Security.EscapeOutput

} elseif ( boolval( $ad_text ) ) {

	$background_color = Adsanity\Meta_Data::get( 'post', $ad->ID, '_text_bg', true );
	$border_color     = Adsanity\Meta_Data::get( 'post', $ad->ID, '_text_border', true );
	$border_width     = Adsanity\Meta_Data::get( 'post', $ad->ID, '_text_border_width', true );

	$style = '';

	if ( boolval( $background_color ) && '' !== $background_color ) {
		$style .= sprintf(
			'background-color:%s;',
			$background_color
		);
	}

	if ( boolval( $border_width ) && '' !== $border_width ) {
		$style .= sprintf(
			'border:1px solid %s;',
			$border_color
		);
	}

	if ( boolval( $border_color ) && '' !== $border_color ) {
		$style .= sprintf(
			'border-width:%spx;',
			$border_width
		);
	}

	printf(
		'<div class="adsanity-text%s" style="%s;"><div>%s</div></div>',
		Adsanity\Meta_Data::get( 'post', $ad->ID, '_text_vertical', true ) ? ' adsanity-vertical-text' : '',
		esc_attr( $style ),
		do_shortcode( wpautop( $ad_text ) )
	);

} else {

	// HTML5 Ad.
	$ad_src = Adsanity\Meta_Data::get( 'post', $ad->ID, 'ad_src', true );
	printf(
		'<iframe src="%s" frameborder="0" scrolling="no"></iframe>',
		esc_attr( trailingslashit( $ad_src ) )
	);

}

// Trigger custom code inside the wrapper, after the ad.
do_action( 'adsanity_after_ad', $ad, $ad->ID );
?>

</div></div>

<?php
// Trigger custom code after the ad wrapper.
do_action( 'adsanity_after_ad_wrapper', $ad, $ad->ID );

// Clear the ad post object.
unset( $ad );
