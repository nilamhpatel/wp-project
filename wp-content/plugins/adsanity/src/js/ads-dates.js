const $ = window.jQuery;

import moment from 'moment-timezone';

//
// Print dates based on timestamp
//
document.addEventListener(
	'DOMContentLoaded',
	function handleDOMContentLoaded() {

		printIntialDate( 'start' );
		printIntialDate( 'end' );

	}
);

//
// Print the intial dates based on hidden input
//
// type - 'start' or 'end'
//
function printIntialDate( type ) {

	const timestamp = $( `#${ type }_date` ).val();
	let date;

	//
	// Prefer timezone string over gmt offset
	// timezone string helps with daylight savings
	//
	if ( ! window.adsanity.timezone_string ) {
		const offset = Number.parseInt( window.adsanity.gmt_offset );
		date = moment( timestamp * 1000 ).utcOffset( offset );
	} else {
		date = moment( timestamp * 1000 ).tz( window.adsanity.timezone_string );
	}

	// Initialize the datepicker
	const datepicker = $( `#${ type }-date` ).datepicker( {
		changeMonth : true,
		changeYear  : true,
		dateFormat  : 'MM dd, yy',
		onSelect    : function( selectedDate, instance ) {
			handleDateChange( selectedDate, instance, type );
		},
	} );

	// Set the date of the datepicker
	datepicker.datepicker( 'setDate', date.toDate() );

	// Set the initial hour
	$( `.${ type }-hour` ).val( date.format( 'HH' ) );

	// Set the initial minute
	$( `.${ type }-minute` ).val( date.format( 'mm' ) );

	if ( type === 'end' ) {

		const monthNum = date.format( 'M' )
		const monthText = adsanity.monthNames[ monthNum - 1 ];

		let dateText = `${ window.adsanity.expires_text } <strong>${ monthText } ${ date.format( 'D, YYYY' ) }</strong>`;

		if ( window.adsanity.adsanity_eol === $( '#end_date' ).val() ) {
			dateText = window.adsanity.forever_text;
		}

		$( '.expires-text' ).html( dateText );
	}

}

//
// Handle time input changes
//
$( 'body' )
	.on(
		'change',
		'.start-date, .start-hour, .start-minute',
		function handleHourChange() {

			const instance = $.datepicker._getInst( $( '#start-date' )[0] );
			handleDateChange( null, instance, 'start' );

		}
	)
	.on(
		'change',
		'.end-date, .end-hour, .end-minute',
		function handleHourChange() {

			const instance = $.datepicker._getInst( $( '#end-date' )[0] );
			handleDateChange( null, instance, 'end' );

		}
	);

//
// Handle a date or time change
//
function handleDateChange( selectedDate, instance, type ) {

	let day      = instance.selectedDay.toString();
	let month    = ( instance.selectedMonth + 1 ).toString();
	const year   = instance.selectedYear;
	const hour   = $( `.${ type }-hour` ).val();
	const minute = $( `.${ type }-minute` ).val();

	if ( day.length === 1 ) {
		day = '0' + day;
	}

	if ( month.length === 1 ) {
		month = '0' + month;
	}

	let timestamp;

	//
	// Prefer timezone string over offset
	//
	if ( ! window.adsanity.timezone_string ) {
		// Create a new moment object with UTC
		const time = moment( `${ year }-${ month }-${ day } ${ hour }:${ minute }Z` );
		// Offset the timestamp x number of hours
		timestamp = Number.parseInt( time.format( 'X' ) ) + ( adsanity.gmt_offset * 3600 );
	} else {
		// Create a new moment object according to timezone
		const time = moment.tz( `${ year }-${ month }-${ day } ${ hour }:${ minute }`, window.adsanity.timezone_string );
		// Get the timestamp
		timestamp = time.format( 'X' );
	}

	// Save the timestamp to a hidden input
	$( `#${ type }_date` ).val( timestamp );

	// Set the expires text to expiration date
	if ( 'end' === type ) {
		let expiresText = `${ window.adsanity.expires_text } <strong>${ $( '#end-date' ).val() }</strong>`;
		if ( window.adsanity.adsanity_eol === $( '#end_date' ).val() ) {
			expiresText = window.adsanity.forever_text;
		}

		$( '.expires-text' ).html( expiresText );
	}

}

//
// Handle date reset
//
$( 'body' ).on( 'click', '#ad_scheduler_reset', function handleDateReset() {

	$( '#end_date' ).val( window.adsanity.adsanity_eol );
	const instance = $.datepicker._getInst( $( '#end-date' )[0] );
	let eol;

	if ( ! window.adsanity.timezone_string ) {
		eol = moment( window.adsanity.adsanity_eol * 1000 + ( 3600 * window.adsanity.gmt_offset ) );
	} else {
		eol = moment( window.adsanity.adsanity_eol * 1000 ).tz( window.adsanity.timezone_string );
	}

	$( '#end-date' ).datepicker( 'setDate', eol.toDate() );

	$( '.end-hour' ).val( eol.format( 'HH' ) );
	$( '.end-minute' ).val( eol.format( 'mm' ) );

} );
