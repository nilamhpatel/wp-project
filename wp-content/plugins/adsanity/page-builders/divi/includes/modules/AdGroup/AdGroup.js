import React, { Component } from 'react';
import Ad from '../Components/Ad';
import arrayShuffle from 'array-shuffle';
const $ = window.jQuery;
const { rest_endpoint, loadingMessage, errorMessage, } = window.ADSANITY_DIVI;

class AdGroup extends Component {

	static slug = 'adsanity_divi_ad_group';

	constructor() {
		super( ...arguments );

		const initialData = { ...this.props };
		initialData.adsanity_ad = null;

		this.state = {
			data : initialData,
			ads  : [],
		};
	}

	//
	// After component mount
	//
	componentDidMount() {
		this.retrieveAds();
	}

	//
	// Handle updates
	//
	componentDidUpdate ( prevProps ) {

		if ( ! Array.isArray( this.props.content ) ) {
			return;
		}

		if ( ! Array.isArray( prevProps.content ) ) {
			return;
		}

		const newGroups = this.props.content.map( group => {
			return Number.parseInt( group.props.attrs.adsanity_group );
		} );
		const oldGroups = prevProps.content.map( group => {
			return Number.parseInt( group.props.attrs.adsanity_group );
		} );

		if ( JSON.stringify( newGroups ) !== JSON.stringify( oldGroups ) ) {
			this.retrieveAds();
		}
	}

	//
	// Retrieve ads
	//
	retrieveAds() {

		if ( ! Array.isArray( this.props.content ) ) {
			this.setState( {
				ads: [],
			} );
			return;
		}

		const groupIds = this.props.content.map( group => {
			return Number.parseInt( group.props.attrs.adsanity_group );
		} );

		$.get( `${ rest_endpoint }ad-group/`, {
			include: groupIds.join( ',' ),
		} )
			.done( ( data ) => {
				let adIds = [];
				data.forEach( ( group ) => {
					adIds.push( ...group.ad_ids );
				} );
				// Randomize ad ids
				adIds = arrayShuffle( adIds );

				this.setState( {
					ads: adIds,
				} );
			} )
			.fail( ( error ) => {
				console.error( error );
			} );

	}

	//
	// Render the component
	//
	render() {

		const { ads } = this.state;
		const numColumns = this.props.adsanity_num_cols;

		const rows = [];

		let numAds = this.props.adsanity_num_ads;
		if ( ads && ads.length < numAds ) {
			numAds = ads.length;
		}
		const numRows = Math.ceil( numAds / numColumns );
		for ( let i = 0; i < numRows; i++ ) {

			const adsInRow = [];

			for ( let k = 0; k < numColumns; k++ ) {
				const adIndex = ( i * numColumns ) + k;
				if ( adIndex > ads.length || adIndex > numAds - 1 ) {
					break;
				}
				adsInRow.push( <Ad
					key={ `adsanity-divi-ad-group-ad-${ ads[ adIndex ] }` }
					data={ {
						adsanity_ad                : ads[ adIndex ],
						adsanity_align             : 'alignnone',
						adsanity_max_width_enabled : this.props.adsanity_max_width_enabled,
						adsanity_max_width         : this.props.adsanity_max_width,
					} }
				/> );
			}

			rows.push(
				<div className="ad-row" key={ i }>
					{ adsInRow }
				</div>
			);
		}

		return (
			<div>
				<div
					className={ `ad-${ this.props.adsanity_align }` }
				>
					{ rows }
				</div>
				<div style={ {
					clear   : 'both',
					display : 'table',
				} }></div>
			</div>
		);
	}

}

export default AdGroup;
