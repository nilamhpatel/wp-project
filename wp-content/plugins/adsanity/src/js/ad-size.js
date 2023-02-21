//
// Toggle the ad size preview
//

const $ = window.jQuery;

$( '#ad-size select#size' ).change( function() {

	const adSizeClass = document.querySelector( '#ad-size .ad-placeholder' ).classList[1];
	$( '#ad-size .ad-placeholder' ).removeClass( adSizeClass );
	$( '#ad-size .ad-placeholder' ).addClass( `ad-${ $( this ).val() }` );

} );
