//
// Handles tab swapping and initialization
//

const $ = window.jQuery;

class AdSanityHandleTabs {

	constructor() {

		//
		// Whether or not this fan is not to be plotted
		//
		this.doNotPlot = ! window._.isUndefined( window.ADSANITY_DO_NOT_PLOT );

		//
		// Kick off this class' methods
		//
		this.setupMetaboxes();
		this.positionTabs();
		this.loadInitialTabs();
		this.eventListeners();

	}

	//
	// Setup metabox arrays
	//
	setupMetaboxes() {

		// An array of all available metaboxes
		this.metaboxes = [];

		// Tab slugs and associated metaboxes
		this.tabs = {};

		const tabs = window.ADSANITY_AD_TABS;

		for ( let tab in tabs ) {
			this.tabs[ tab ] = tabs[ tab ].active_tabs;
			this.metaboxes.push( ...tabs[ tab ].active_tabs );
		}

		// Remove duplicates from metaboxes
		this.metaboxes = window._.uniq( this.metaboxes );

	}

	//
	// Place the tabs in the correct place
	//
	positionTabs() {

		$( '#ad-source-tabs' ).insertBefore( $( 'form#post' ) );

	}

	//
	// Load initial tab state
	//
	loadInitialTabs() {

		const activeTab = $( '.nav-tab-active' );
		if ( ! activeTab.length ) {
			this.handleNavChange( 'internal' );
		} else {
			const href = activeTab.attr( 'href' ).replace( '#', '' );
			this.handleNavChange( href );
		}

	}

	//
	// Event listeners for this class
	//
	eventListeners() {

		$( '.nav-tab' ).click( ( event ) => {
			const target = $( event.target )

			// Don't do anything if tabs haven't changed
			if ( target.hasClass( 'nav-tab-active' ) ) {
				return;
			}

			// Swap out active tabs
			$( '.nav-tab-active' ).removeClass( 'nav-tab-active' );
			target.addClass( 'nav-tab-active' );

			// Change metaboxes
			const href = target.attr( 'href' ).replace( '#', '' );
			this.handleNavChange( href );

			
		} );

	}

	//
	// Handle tab change
	//
	handleNavChange( href ) {

		const tabs = this.tabs[ href ];
		this.metaboxes.forEach( ( metabox ) => {
			if ( tabs.includes( metabox ) ) {

				// Show this metabox and return
				$( `#${ metabox }` ).show();
				return;
			}

			// Hide this metabox
			$( `#${ metabox }` ).hide();
		} );

	}

}

export default AdSanityHandleTabs;
