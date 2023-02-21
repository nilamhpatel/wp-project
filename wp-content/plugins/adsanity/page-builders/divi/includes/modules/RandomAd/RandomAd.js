import React, { Component } from 'react';
import Ad from '../Components/Ad';
const $ = window.jQuery;
const { rest_endpoint, loadingMessage, errorMessage, } = window.ADSANITY_DIVI;

class RandomAd extends Component {

	static slug = 'adsanity_divi_random_ad';

	constructor() {
		super( ...arguments );

		const initialData = { ...this.props };
		initialData.adsanity_ad = null;

		this.state = {
			data: initialData,
		};
	}

	componentDidMount() {
		this.retrieveAdId();
	}

	//
	// Handle updates
	//
	componentDidUpdate ( prevProps ) {

		if (
			prevProps.adsanity_max_width_enabled !== this.props.adsanity_max_width_enabled ||
			prevProps.adsanity_max_width !== this.props.adsanity_max_width ||
			prevProps.adsanity_align !== this.props.adsanity_align
		) {
			const data = { ...this.props };
			data.adsanity_ad = this.state.data.adsanity_ad;
			this.setState( {
				data: data,
			} );
		}

		// Check for ad group change
		if ( ! Array.isArray( this.props.content ) ) {
			return;
		}

		if ( ! Array.isArray( prevProps.content ) ) {
			return;
		}

		const prevGroups = prevProps.content.map( group => group.props.attrs.adsanity_group );
		const newGroups = this.props.content.map( group => group.props.attrs.adsanity_group );

		if ( JSON.stringify( prevGroups ) !== JSON.stringify( newGroups ) ) {
			this.retrieveAdId();
		}

	}

	//
	// Retrieve a random ad id in the group
	//
	retrieveAdId() {

		if ( ! Array.isArray( this.props.content ) ) {
			const obj = { ...this.state.data };
			obj.adsanity_ad = null;
			this.setState( {
				data: obj,
			} );
			return;
		}

		const groupIds = this.props.content.map( group => {
			return group.props.attrs.adsanity_group;
		} );

		const groupId = groupIds[ Math.floor( Math.random() * groupIds.length ) ];

		$.get( `${ rest_endpoint }ad-group/${ groupId }` )
			.done( ( data ) => {
				const obj = { ...this.state.data };
				obj.adsanity_ad = data.ad_ids[ Math.floor( Math.random() * data.ad_ids.length ) ];
				this.setState( {
					data: obj,
				} );
			} )
			.fail( ( error ) => {
				console.error( error );
			} );

	}

	render() {
		return <div>
			<Ad data={ this.state.data }/>
			<div style={ {
				clear   : 'both',
				display : 'table',
			} }></div>
		</div>;
	}

}

export default RandomAd;
