//
// NPM
//
import AsyncSelect from 'react-select/async';
import { decode } from 'html-entities';

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
import AutomaticInclusion from '../common/AutomaticInclusion';
import SingleAd from '../common/SingleAd';
import AddOnsWarning from '../common/AddOnsWarning';
import selectTheme from '../common/select-theme';
import EmotionCache from '../common/EmotionCache';

export default registerBlockType(
	'adsanity/single-ad',
	{
		title: sprintf(
			__( '%s Single Ad', 'adsanity' ),
			'AdSanity'
		),
		icon: icon,
		category: 'adsanity',
		keywords: [
			__( 'ads', 'adsanity' ),
			__( 'advertisement', 'adsanity' ),
			__( 'advertisements', 'adsanity' ),
		],
		attributes: {
			post_id: {
				type: 'number',
			},
			post_name: {
				type: 'text',
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
			rendered_ad: {
				type: 'object',
				default: {
					adId: null,
					markup: '',
				},
			},
		},
		edit: function Edit( props ) {

			//
			// Load ads for the select component
			//
			const loadOptions = ( value, callback ) => {

				let args = {
					per_page : 100,
				};

				if ( value ) {
					args.orderby = 'title';
					args.search  = value;
				}

				apiFetch( {
					path: addQueryArgs( '/wp/v2/ads/', args ),
				} )
				.then( ( posts ) => {
					posts = posts.map( ( post ) => {
						return {
							value: post.id,
							label: decode( post.title.rendered ),
						};
					} );
					callback( posts );
				} )
				.catch( ( error ) => {
					console.log( error );
				} );

			}

			//
			// Handles ad change of the select component
			//
			const onAdChange = ( nextValue ) => {
				props.setAttributes( {
					post_id   : nextValue.value,
					post_name : nextValue.label,
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
			// Save a rendered ad
			//
			const onRenderedAd = ( value ) => {
				props.setAttributes( {
					rendered_ad: value,
				} );
			}

			//
			// Define default values of the select component
			//
			const defaultValue = {
				value: props.attributes.post_id,
				label: props.attributes.post_name,
			};

			return (
				<div
					className={ props.className }
					data-align={ props.attributes.align }
				>
					{ !! props.isSelected ? (
						<Placeholder
							label={ sprintf( __( '%s Single Ad', 'adsanity' ), 'AdSanity' ) }
						>

							<label>
								<span>{ __( 'Select an ad:', 'adsanity' ) }</span>
								<EmotionCache>
									<AsyncSelect
										cacheOptions
										defaultOptions
										loadOptions={ loadOptions }
										defaultValue={ defaultValue }
										onChange={ onAdChange }
										theme={ selectTheme }
									/>
								</EmotionCache>
							</label>

							<AddOnsWarning/>

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
							<SingleAd
								adId={ props.attributes.post_id }
								renderedAd={ props.attributes.rendered_ad }
								onRenderedAd={ onRenderedAd }
								maxWidthEnabled={ props.attributes.max_width_enabled }
								maxWidth={ props.attributes.max_width }
								align={ props.attributes.align }
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
