//
// WordPress scripts
//
const { __, }        = window.wp.i18n;
const apiFetch       = window.wp.apiFetch;
const { Component, } = window.wp.element;

class SingleAd extends Component {

	constructor() {
		super( ...arguments );
		this.onRenderedAd = this.onRenderedAd.bind( this );
		this.state = {
			classes: '',
			markup: {
				__html: this.props.renderedAd.markup,
			},
			style: {},
		};
		this.adLoading = `<h1>${ __( 'Loading ad', 'adsanity' ) }...</h1>`;
		this.errorMessage = `<h1>${ __( 'Unable to render ad', 'adsanity' ) }</h1>`;
		this.setPlaceholder();
	}

	//
	// Load ad after this component mounts
	//
	componentDidMount() {
		this.loadAd();
	}

	//
	// Send the markup to parent component when markup changes
	//
	onRenderedAd( value ) {
		this.props.onRenderedAd( value );
	}

	//
	// Set the placeholder
	//
	setPlaceholder() {

		let { maxWidthEnabled, maxWidth, } = this.props;

		maxWidth = Number.parseFloat( maxWidth );

		let titleStyle = '';
		let disclaimerStyle = '';

		// If the placeholder is tiny, font-size needs to be adjusted
		if ( maxWidthEnabled && maxWidth < 120 ) {
			titleStyle = `font-size:${ maxWidth * 0.15 }px;`;
			disclaimerStyle = `font-size:${ maxWidth * 0.1 }px;`;
		}

		this.placeholder = `<div class="adsanity-inner">
			<p class="title" style="${ titleStyle }">
				<strong>${ __( 'AdSanity Ad', 'adsanity' ) }</strong>
			</p>
			<p class="disclaimer" style="${ disclaimerStyle }">${ __( 'for position only', 'adsanity' ) }</p>
		</div>`;
	}

	//
	// Get style based on max width settings
	//
	getStyle( isNetwork = false ) {

		const { maxWidthEnabled, maxWidth, } = this.props;

		if ( maxWidthEnabled ) {

			const styles = {
				maxWidth: `${ maxWidth }px`,
			}

			this.setState( {
				style: styles,
			} );
		}

	}

	//
	// Apply alignment class
	//
	align( classes ) {
		const { align, } = this.props;

		classes = classes.replace( 'alignnone', '' )
			.replace( 'aligncenter', '' )
			.replace( 'alignleft', '' )
			.replace( 'alignright', '' )
			.replace( 'adsanity-alignnone', '' )
			.replace( 'adsanity-aligncenter', '' )
			.replace( 'adsanity-alignleft', '' )
			.replace( 'adsanity-alignright', '' );

		classes += ` ${ align } adsanity-${ align }`;

		return classes;
	}

	//
	// Get the classes for this ad
	//
	getClasses( markup, json = null ) {

		const { align, } = this.props;
		let classAttr = markup.match( /class\=\"(.*?)(?=\")/ );

		if ( ! classAttr ) {
			return false;
		} else {
			classAttr = classAttr[0].replace( 'class="', '' );
		}

		// If this is anything other than a
		// self-hosted ad, add a placeholder class
		if ( json && json.ad_type !== 'self-hosted' ) {
			classAttr += ' adsanity-placeholder';
		}

		classAttr += ` ${ align } adsanity-${ align }`;

		return classAttr;

	}

	//
	// Get inner markup of an ad
	//
	getMarkup( markup, json = null ) {

		// If this is anything other than a
		// self-hosted ad, insert a placeholder
		if ( json && json.ad_type !== 'self-hosted' ) {
			this.getStyle( true );
			return this.placeholder;
		}

		markup = markup.trim();

		// Unwrap the markup
		markup = markup.replace( /\<(.*?)\>/, '' );
		markup = markup.slice( 0, markup.length - 6 );

		// Replace any href
		markup = markup.replace( /href\=\"(.*?)\"/, '' );

		return markup;

	}

	//
	// Load an ad from the REST API
	//
	loadAd() {

		// Get some data from props
		const { adId, renderedAd, } = this.props;

		// Check to see if the current ad and rendered ad match
		if ( adId === renderedAd.id && renderedAd.markup && renderedAd.classes ) {
			let classes = this.align( renderedAd.classes );
			let markup = renderedAd.markup;

			this.getStyle( renderedAd.isNetwork );

			if ( renderedAd.isNetwork === true ) {
				markup = this.placeholder;
			}

			this.setState( {
				classes: classes,
				markup: {
					__html: markup,
				},
			} );
			return;
		}

		// Temporarily ad an "Ad loading" message
		this.setState( {
			markup: {
				__html: this.adLoading,
			},
		} );

		//
		// Fetch an ad and render markup
		//
		apiFetch( {
			path: `/wp/v2/ads/${ this.props.adId }`,
		} )
		.then( ( ad ) => {

			const classes = this.getClasses( ad.rendered_ad, ad );
			const markup = this.getMarkup( ad.rendered_ad, ad );

			if ( ! markup || ! classes ) {
				this.setState( {
					markup: {
						__html: this.errorMessage,
					},
				} );
				return;
			}

			this.setState( {
				classes: classes,
				markup: {
					__html: markup,
				},
			} );

			let isNetwork = false;

			if ( ad.ad_type !== 'self-hosted' ) {
				isNetwork = true;
			}

			this.getStyle( isNetwork );

			this.onRenderedAd( {
				classes: classes,
				markup: markup,
				id: ad.id,
				isNetwork: isNetwork,
			} );
		} )
		.catch( ( error ) => {
			console.log( error );
			this.setState( {
				markup: {
					__html: this.errorMessage,
				},
			} );
		} );

	}

	//
	// Render this component
	//
	render() {

		return (
			<div
				className={ this.state.classes }
				dangerouslySetInnerHTML={ this.state.markup }
				style={ this.state.style }
			>
			</div>
		);

	}

}

export default SingleAd;
