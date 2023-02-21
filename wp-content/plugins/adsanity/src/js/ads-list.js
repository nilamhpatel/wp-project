const $ = window.jQuery;

import moment from 'moment-timezone';

jQuery(document).ready(function($) {

	var clipboard = new Clipboard('.adsanity-clipboard');
	clipboard.on('success', function(e) {
		alert( 'Copied to the clipboard.' );

		e.clearSelection();
	});

	clipboard.on('error', function(e) {});
	$('.adsanity-clipboard').on('click', function(e) {
		e.preventDefault();
	});

	// Print ad expiration according to timezone settings
	$( '.adsanity-start span' ).each( printLocalTime );
	$( '.adsanity-expires span' ).each( printLocalTime );

	function printLocalTime() {

		const timestamp = $( this ).data( 'timestamp' );
		let date;

		//
		// Prefer timezone string over gmt offset
		// timezone string helps with daylight savings
		//
		if ( ! window.adsanity.timezone_string ) {
			date = moment( timestamp * 1000 + ( 3600 * window.adsanity.gmt_offset ) );
		} else {
			date = moment( timestamp * 1000 ).tz( window.adsanity.timezone_string );
		}

		const monthNum = date.format( 'M' )
		const monthText = adsanity.monthNames[ monthNum - 1 ];

		const dateString = `${ monthText } ${ date.format( 'D, YYYY HH:mm' ) }`;

		$( this ).text( dateString );

	}

});
