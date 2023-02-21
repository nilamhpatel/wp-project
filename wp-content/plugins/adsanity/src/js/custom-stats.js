const $ = window.jQuery;

import Chart from './vendor/Chart.js';
import { decode } from 'html-entities';

// Chart colors. Max results of 15 ads
const chartColors = [
	'#4dc9f6', '#f67019', '#f53794', '#537bc4', '#acc236',
	'#166a8f', '#00a950', '#58595b', '#8549ba', '#4dc9f6',
	'#f67019', '#f53794', '#537bc4', '#acc236', '#166a8f',
];

//
// Handle custom charts change
//
let customStatsXhr = null;

$( '#ad-choices input' ).change( updateDisplay );

function updateDisplay() {

	// Hide the charts by default
	$( '#custom-views-container' ).hide();
	$( '#custom-clicks-container' ).hide();

	// Show loading animation.
	$( '#custom-reports-loading' ).show();

	// Check to see if xhr request is still in progress
	if ( customStatsXhr && customStatsXhr.readystate !== 4 ) {
		customStatsXhr.abort();
	}

	//
	// Make an array the selected ads
	//
	const ads = [];

	$( '#ad-choices' ).find( 'input:checked' ).each( function () {
		ads.push( $( this ).val() );
	} );

	// Make a request for data on the ads
	customStatsXhr = $.post(
		window.ajaxurl,
		{
			action : 'custom_stats_selection',
			ads    : ads,
			start  : $( '#start_date' ).val(),
			end    : $('#end_date').val(),
		},
		'json'
	)
	.done( function( data ) {
		const results = JSON.parse( data );

		// Hide loading animation.
		$( '#custom-reports-loading' ).hide();

		// Trigger a custom action so add-ons can do something here.
		$( '#custom-report-container' ).trigger( 'adsanitycustomreportloaded' );

		// Show the report table
		$( '#adsanity-data' )
			.html( results.table )
			.parents( '#custom-report-container' )
			.show();

		// Report table total
		$( '#total-views' ).html( results.total_views );
		$( '#total-clicks' ).html( results.total_clicks );
		$( '#total-ctr' ).html( results.total_ctr );


		//
		// Views chart
		//

		// For each ad, create a dataset
		const viewDatasets = [];
		for ( let ad in results.chart_data.views ) {
			const data = [];

			// Track the title of this ad
			let title = '';

			for ( let date in results.chart_data.views[ ad ] ) {

				title = results.chart_data.views[ ad ][ date ].title;

				const dateData = results.chart_data.views[ ad ][ date ];
				title = dateData.title;

				data.push( {
					x: new Date( Number.parseInt( dateData.timestamp ) * 1000 ),
					y: dateData.viewcount,
				} );
			}

			// Push this dataset to the datasets array
			viewDatasets.push( {
				label           : decode( title ),
				fill            : false,
				backgroundColor : chartColors[ viewDatasets.length ],
				borderColor     : chartColors[ viewDatasets.length ],
				data            : data,
			} );
		}

		// Create the views chart
		if ( viewDatasets.length ) {
			$( '#custom-views-container' ).show();
			const viewsContext = document.getElementById( 'custom-views-container-canvas' ).getContext( '2d' );

			new Chart( viewsContext, {
				type: 'line',
				data: {
					datasets : viewDatasets,
				},
				options: {
					scales: {
						xAxes: [ {
							type: 'time',
							time: {
								unit: 'day',
								tooltipFormat: 'MMM D',
							},
							scaleLabel: {
								display: true,
							},
						}, ],
						yAxes: [ {
							ticks: {
								beginAtZero: true,
							},
						}, ],
					},
					tooltips: {
						displayColors: false,
					},
				},
			} );
		}

		//
		// Clicks chart
		//

		// For each ad, create a dataset
		const clickDatasets = [];
		for ( let ad in results.chart_data.clicks ) {
			const data = [];

			// Track the title of this ad
			let title = '';

			for ( let date in results.chart_data.clicks[ ad ] ) {

				title = results.chart_data.clicks[ ad ][ date ].title;

				const dateData = results.chart_data.clicks[ ad ][ date ];
				title = dateData.title;

				data.push( {
					x: new Date( Number.parseInt( dateData.timestamp ) * 1000 ),
					y: dateData.clickcount,
				} );
			}

			// Push this dataset to the datasets array
			clickDatasets.push( {
				label           : decode( title ),
				fill            : false,
				backgroundColor : chartColors[ clickDatasets.length ],
				borderColor     : chartColors[ clickDatasets.length ],
				data            : data,
			} );
		}

		// Create the views chart
		if ( clickDatasets.length ) {
			$( '#custom-clicks-container' ).show();
			const viewsContext = document.getElementById( 'custom-clicks-container-canvas' ).getContext( '2d' );

			new Chart( viewsContext, {
				type: 'line',
				data: {
					datasets : clickDatasets,
				},
				options: {
					scales: {
						xAxes: [ {
							type: 'time',
							time: {
								unit: 'day',
								tooltipFormat: 'MMM D',
							},
							scaleLabel: {
								display: true,
							},
						}, ],
						yAxes: [ {
							ticks: {
								beginAtZero: true,
							},
						}, ],
					},
					tooltips: {
						displayColors: false,
					},
				},
			} );
		}

	} );

}

//
// Datepicker
//
var dates = $( '#start_date, #end_date' ).datepicker({
	changeMonth: true,
	changeYear: true,
	dateFormat: 'MM dd, yy',
	onSelect: function( selectedDate ) {
		var option = this.id == "start_date" ? "minDate" : "maxDate",
			instance = $( this ).data( "datepicker" ),
			date = $.datepicker.parseDate(
				instance.settings.dateFormat ||
				$.datepicker._defaults.dateFormat,
				selectedDate, instance.settings );
		dates.not( this ).datepicker( "option", option, date );

		updateDisplay();
	}
});

$('.selectall, .selectnone').click(function() {
	var choiceContainer = $('#ad-choices');

	if( $(this).hasClass('selectall') ) {
		choiceContainer.find('input').not(':hidden').attr('checked', 'checked');
	} else {
		choiceContainer.find('input').removeAttr('checked');
	}
	updateDisplay();
	return false;
})

//
// Ad search
//
var timer;
var delay = 500;

$('#ad-search').on( 'focus', function() {
	var input_value = $(this).val();
	if( input_value.toLowerCase() == 'search ads' ) {
		$(this).val( '' );
		$(this).css({color: '#000'});
	}
});

$('#ad-search').on( 'keypress change', function( event ) {
	// Don't track change with value attached. This could cause duplicate requests.
	if ( event.type === 'change' && event.target.value !== '' ) {
		return;
	}

	// check for output
	if( (typeof event.which == "undefined") || ( (typeof event.which == "number" && event.which > 0) && !event.ctrlKey && !event.metaKey && !event.altKey ) ) {
		if( timer ) window.clearTimeout( timer );
		timer = window.setTimeout( do_ad_search, delay, $(this) );
	}
});

function do_ad_search( input ) {

	// the value of the search
	var input_value = input.val().toLowerCase();
	var context = $( '#ad-choices ul' );

	if( input_value.length == 0 || input_value == '' ) {
		$('label', context).parent('li').show();
	} else {
		$('label', context).parent('li').hide();
		$('label', context).each(function( index, self ) {
			var t = $(this);
			var text = t.text();
			if( text.toLowerCase().indexOf( input_value ) != -1 ) t.parent('li').show();
		});
	}
}

//
// Handle export CSV click
//
$('#export-csv').on('click', function(e) {
	e.preventDefault();
	$('#data-export').submit();
	return false;
});

//
// Make functions available on the window object
//
window.AdSanityCustomReports = {
	updateDisplay: updateDisplay,
};
