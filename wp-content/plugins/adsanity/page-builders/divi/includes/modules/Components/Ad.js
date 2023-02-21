import React, { Component } from 'react';
import parse from 'html-react-parser';
const $ = window.jQuery;
const { rest_endpoint, loadingMessage, errorMessage, } = window.ADSANITY_DIVI;

class Ad extends Component {

	constructor() {
		super( ...arguments );
		this.state = {
			markup: `<p><strong>${ loadingMessage }</strong>&nbsp;</p>`,
		};
	}

	componentDidMount() {
		this.parseMarkup();
		this.retrieveAdMarkup();
	}

	//
	// Handle updates
	//
	componentDidUpdate ( prevProps ) {

		// Handle max width styles
		if (
			prevProps.data.adsanity_max_width_enabled !== this.props.data.adsanity_max_width_enabled ||
			prevProps.data.adsanity_max_width !== this.props.data.adsanity_max_width ||
			prevProps.data.adsanity_align !== this.props.data.adsanity_align
		) {
			this.parseMarkup();
		}

		if ( prevProps.data.adsanity_ad !== this.props.data.adsanity_ad ) {
			this.retrieveAdMarkup();
		}

	}

	//
	// Parse the returned markup to add class and max-width
	//
	parseMarkup() {

		let newMarkup = this.state.markup.trim();

		// Replace style attribute if active
		const search = new RegExp( /^\<div(.*?)style\=\"(.*?)\"(.*?)\>/m )

		newMarkup = newMarkup.replace( search, `<div$1$3>` );

		if ( 'on' === this.props.data.adsanity_max_width_enabled ) {
			const style = `max-width:${ this.props.data.adsanity_max_width }px;`;
			newMarkup = newMarkup.replace( /^\<div/, `<div style="${ style }" ` );
		}

		// Add alignment class
		newMarkup = newMarkup
			.replace( 'alignnone', '' )
			.replace( 'alignleft', '' )
			.replace( 'aligncenter', '' )
			.replace( 'alignright', '' )
			.replace( 'adsanity-alignnone', '' )
			.replace( 'adsanity-alignleft', '' )
			.replace( 'adsanity-aligncenter', '' )
			.replace( 'adsanity-alignright', '' );

		const classes = `${ this.props.data.adsanity_align } adsanity-${ this.props.data.adsanity_align }`;
		newMarkup = newMarkup.replace( /class\=\"/, `class="${ classes } ` );

		this.setState( {
			markup: newMarkup,
		} );

	}

	//
	// Retrieve ad markup and set it to state
	//
	retrieveAdMarkup() {
		this.setState( {
			markup: `<p><strong>${ loadingMessage }</strong>&nbsp;</p>`,
		} );

		if ( ! this.props.data.adsanity_ad ) {
			return;
		}

		$.get( `${ rest_endpoint }ads/${ this.props.data.adsanity_ad }` )
			.done( ( data ) => {
				this.setState( {
					markup: data.rendered_ad,
				} );
				this.parseMarkup();
			} )
			.fail( ( error ) => {
				console.error( error );
				this.setState( {
					markup: `<p><strong>${ errorMessage }</strong>&nbsp;</p>`,
				} );
				this.parseMarkup();
			} );
	}

	render() {
		return parse( this.state.markup );
	}

}

export default Ad;
