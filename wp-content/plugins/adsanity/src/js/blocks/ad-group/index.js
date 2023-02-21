//
// NPM
//
import AsyncSelect from 'react-select/async';
import { decode } from 'html-entities';
import arrayShuffle from 'array-shuffle';

//
// WordPress scripts
//
const { registerBlockType, } = window.wp.blocks;
const { __, sprintf, }       = window.wp.i18n;
const { addQueryArgs, }      = window.wp.url;
const apiFetch               = window.wp.apiFetch;
const { Placeholder, }       = window.wp.components;

//
// Local imports
//
import icon from './icon';
import AlignButtonGroup from '../common/AlignButtonGroup';
import MaxWidthControl from '../common/MaxWidthControl';
import selectTheme from '../common/select-theme';
import AutomaticInclusion from '../common/AutomaticInclusion';
import AdGroup from '../common/AdGroup';
import AddOnsWarning from '../common/AddOnsWarning';
import EmotionCache from '../common/EmotionCache';

export default registerBlockType(
	'adsanity/ad-group',
	{
		title: sprintf( __( '%s Ad Group' ), 'AdSanity' ),
		icon: icon,
		category: 'adsanity',
		keywords: [
			__( 'ads', 'adsanity' ),
			__( 'advertisement', 'adsanity' ),
			__( 'advertisements', 'adsanity' ),
		],
		attributes: {
			group_ids: {
				type: 'array',
				default: [],
			},
			groups: {
				type: 'array',
				default: [],
			},
			ad_ids: {
				type: 'array',
				default: [],
			},
			align: {
				type: 'text',
				default: 'alignnone',
			},
			max_width_enabled: {
				type: 'boolean',
				default: false,
			},
			max_width: {
				type: 'text',
				default: '100',
			},
			num_ads: {
				type: 'text',
				default: '1',
			},
			num_columns: {
				type: 'text',
				default: '1',
			},
		},
		edit( props ) {

			//
			// Load ad groups for the select component
			//
			const loadOptions = ( value, callback ) => {

				let args = {
					per_page : 5,
				};

				if ( value ) {
					args.orderby = 'name';
					args.search  = value;
				}

				apiFetch( {
					path: addQueryArgs( '/wp/v2/ad-group/', args ),
				} )
				.then( ( groups ) => {
					groups = groups.map( ( group ) => {
						return {
							value: group.id,
							label: decode( group.name ),
							adIds: group.ad_ids,
						};
					} );
					callback( groups );
				} )
				.catch( ( error ) => {
					console.log( error );
				} );

			}

			//
			// Handles a change of ad group
			//
			const onGroupChange = ( value ) => {
				let group_ids = [];
				let ad_ids = [];

				value.forEach( ( group ) => {
					group_ids.push( group.value );
					group.adIds.forEach( ( adId ) => {
						ad_ids.push( { id: adId, } );
					} );
				} );

				// Shuffle the ad ids
				ad_ids = arrayShuffle( ad_ids );

				props.setAttributes( {
					group_ids : group_ids,
					ad_ids    : ad_ids,
					groups    : value,
				} );

			}

			//
			// Handles changes of alignment buttons
			//
			const onAlignChange = ( value ) => {
				props.setAttributes( {
					align: value,
				} );
			}

			//
			// Handles changes on the Max Width Enabled checkbox
			//
			const onMaxWidthEnabledChange = () => {
				let maxWidth = '100';
				if ( props.attributes.max_width_enabled ) {
					maxWidth = '';
				}
				props.setAttributes( {
					max_width: maxWidth,
					max_width_enabled: ! props.attributes.max_width_enabled,
				} );
			}

			//
			// Handles changes on the Max Width field
			//
			const onMaxWidthChange = ( value ) => {
				props.setAttributes( {
					max_width: value,
				} );
			}

			//
			// Handles changes on the number of ads
			//
			const onNumAdsChange = ( event ) => {
				let value = event.target.value.replace( /\D/g, '' );
				if ( value < 1 ) {
					value = '';
				}
				props.setAttributes( {
					num_ads: value,
				} );
			}

			//
			// Handles changes on the number of columns
			//
			const onNumColumnsChange = ( event ) => {
				let value = event.target.value.replace( /\D/g, '' );
				if ( value < 1 ) {
					value = '';
				}
				props.setAttributes( {
					num_columns: value,
				} );
			}

			//
			// Handles change of a rendered ad
			//
			const onRenderedAd = ( value ) => {
				const adIds = props.attributes.ad_ids;

				adIds.forEach( ( adId, index ) => {
					if ( value.id === adId.id ) {
						adIds[ index ] = value;
					}
				} );

				props.setAttributes( {
					ad_ids: adIds,
				} );
			}

			//
			// Define default values of the select component
			//
			const defaultValue = props.attributes.groups;

			return (
				<div className={ props.className }>
					{ !! props.isSelected ? (
						<Placeholder
							label={ sprintf( __( '%s Ad Group', 'adsanity' ), 'AdSanity' ) }
						>
							<label>
								<span>{ __( 'Select an ad group:', 'adsanity' ) }</span>
								<EmotionCache>
									<AsyncSelect
										cacheOptions
										defaultOptions
										loadOptions={ loadOptions }
										defaultValue={ defaultValue }
										theme={ selectTheme }
										onChange={ onGroupChange }
										isMulti
									/>
								</EmotionCache>
							</label>

							<AddOnsWarning/>

							<hr className="adsanity-section-separator"/>

							<div className="ad-group-numbers">
								<label>
									<span>{ __( 'Number of Ads:', 'adsanity' ) }</span>
									<input
										type="text"
										value={ props.attributes.num_ads }
										onChange={ onNumAdsChange }
									/>
								</label>
								<label>
									<span>{ __( 'Number of Columns:', 'adsanity' ) }</span>
									<input
										type="text"
										value={ props.attributes.num_columns }
										onChange={ onNumColumnsChange }
									/>
								</label>
							</div>

							<hr className="adsanity-section-separator"/>

							<AlignButtonGroup
								onAlignChange={ onAlignChange }
								propValue={ props.attributes.align }
							/>

							<hr className="adsanity-section-separator"/>

							<MaxWidthControl
								onMaxWidthEnabledChange={ onMaxWidthEnabledChange }
								onMaxWidthChange={ onMaxWidthChange }
								maxWidthEnabled={ props.attributes.max_width_enabled }
								maxWidth={ props.attributes.max_width }
							/>

							<AutomaticInclusion/>

						</Placeholder>
					) : (
						<div>
							<AdGroup
								align={ props.attributes.align }
								adIds={ props.attributes.ad_ids }
								numAds={ props.attributes.num_ads }
								numColumns={ props.attributes.num_columns }
								maxWidthEnabled={ props.attributes.max_width_enabled }
								maxWidth={ props.attributes.max_width }
								onRenderedAd={ onRenderedAd }
								noAdMessage={ __( 'Please add an ad group', 'adsanity' ) }
							/>
						</div>
					) }
				</div>
			);

		},
		save() {
			// Rendered in PHP
			return null;
		},
	}
);
