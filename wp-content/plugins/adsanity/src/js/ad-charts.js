const $ = window.jQuery;

import Chart from './vendor/Chart.js';
import moment from 'moment';

export default function() {

	//
	// Views
	//
	const canvasEl = document.getElementById( 'ad-chart-views-canvas' );
	if ( null === canvasEl ) {
		return false;
	}
	const viewsContext = canvasEl.getContext( '2d' );

	const viewsDates = window.views.data.map( ( date ) => {
		const dateString = moment( date[0] ).format( 'MMM D' );
		return dateString;
	} );

	const viewsData = window.views.data.map( ( view ) => {
		return view[1];
	} );

	printChart( viewsContext, viewsDates, viewsData, '#986aff', '#ad88ff' );

	//
	// Clicks
	//
	if ( ! window._.isUndefined( window.ADSANITY_DO_NOT_CHART_CLICKS ) ) {
		$( '#ad-chart .click-only' ).hide();
		return;
	}

	const clicksContext = document.getElementById( 'ad-chart-clicks-canvas' ).getContext( '2d' );

	const clicksDates = window.clicks.data.map( ( date ) => {
		const dateString = moment( date[0] ).format( 'MMM D' );
		return dateString;
	} );

	const clicksData = window.clicks.data.map( ( view ) => {
		return view[1];
	} );

	printChart( clicksContext, clicksDates, clicksData, '#37cdc7', '#5fd7d3' );

}

function printChart( context, dates, data, background, border ) {

	const config = {
		type: 'line',
		data: {
			labels: dates,
			datasets: [ {
				backgroundColor: background,
				borderColor: border,
				fill: false,
				data: data,
			}, ]
		},
		options: {
			scales: {
				yAxes: [ {
					ticks: {
						beginAtZero: true,
					},
				}, ],
			},
			legend: {
				display: false,
			},
			tooltips: {
				displayColors: false,
			},
		},
	};

	new Chart( context, config );

}
