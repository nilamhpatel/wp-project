const $ = window.jQuery;

// Import html5
import html5 from './html5';

// Import dates
import './ads-dates';

// Import ad size
import './ad-size';

// Import ad text handling
import textAds from './ads-text';

// Import tab handler
import HandleTabs from './ads-tab-handling';

jQuery(document).ready(function($) {

	//
	// Execute tab handling
	//
	new HandleTabs();

	//
	// Execute html5 functionality
	//
	html5();

	//
	// Execute text ad functionality
	//
	textAds();

	/*
	 * EDIT SCHEDULE LINK
	 * - show the inputs						(check)
	 * - hide the edit link						(check)
	 * - if we have custom values				(check)
	 *     show the custom values				(check)
	 * - else									(check)
	 *     show a date one year in the future	(check)
	/**/
	$('#is_scheduled').click(function() { // show the scheduling inputs

		// Create a date object from the start date
		var start = new Date( $('#start_date').val() );

		// Create a date object from the start date
		var end = new Date( $('#end_date').val() );

		// Create a date object from the eol date
		var eol = new Date( adsanity.adsanity_eol*1000 );

		// there is no custom value, show a date one year in the future
		if( end.getMonth() == eol.getMonth() && end.getDate() == eol.getDate()+1 && end.getFullYear() == eol.getFullYear() ) {

			var now_month = start.getMonth()+1;
			// Fix zero base
			if( now_month < 10 )
				now_month = "0"+now_month;

			var now_day = start.getDate();
			// Fix zero base
			if( now_day < 10 )
				now_day = "0"+now_day;

			// Add a year from the start date
			start.setFullYear( start.getFullYear() + 1 );

			// Set the end date to a year from the start date
			if( adsanity.months != "Array" ) {
				$('#end_date').val( adsanity.months[now_month] + ' ' + now_day + ', ' + start.getFullYear() );
			} else {
				$('#end_date').val( adsanity['months_'+now_month] + ' ' + now_day + ', ' + start.getFullYear() );
			}
		}

		// hide the edit link
		$(this).addClass( 'hidden' );

		// show the inputs
		$('#for_scheduled_only').slideDown();
		return false;

	});

	/*
	 * CANCEL SCHEDULE BUTTON
	 * - reset schedule								(check)
	 * - hide the inputs							(check)
	 * - change the text to show no expiration date	(check)
	 * - show the edit link							(check)
	/**/
	$('#cancel_schedule_change').click(function() { // hide the scheduling inputs

		// hide the inputs
		$('#for_scheduled_only').slideUp( '400', function() {

			// show the edit link
			$('#is_scheduled').removeClass( 'hidden' );

			// Create a date object from the start date
			var now = new Date( ( adsanity.adsanity_eol ) * 1000 );

			var now_month = now.getMonth() + 1;
			var now_day = now.getDate() + 1;
			var now_year = now.getFullYear();

			// Set the end date to a year from the start date
			if( adsanity.months != "Array" ) {
				$('#end_date').val( adsanity.months[now_month] + ' ' + now_day + ', ' + now_year );
			} else {
				$('#end_date').val( adsanity['months_'+now_month] + ' ' + now_day + ', ' + now_year );
			}
		});

		return false;
	});

	/*
	 * ACCEPT SCHEDULE BUTTON
	 * - keep selected schedule						(check)
	 * - hide the inputs							(check)
	 * - change the text to show expiration date	(check)
	 * - show the edit link							(check)
	/**/
	$('#accept_schedule_change').click(function() { // hide the scheduling inputs

		// hide the inputs
		$('#for_scheduled_only').slideUp( '400', function() {

			// show the edit link
			$('#is_scheduled').removeClass( 'hidden' );

		});
		return false;
	});

});
