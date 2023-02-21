( function( $ ) {

	ADSANITY_ELEMENTOR.css_urls.forEach( function( css_url ) {
		elementor.helpers.enqueueEditorStylesheet( css_url );
	} );

	//
	// Parse options.
	//
	function parseOptions( vals ) {

		if ( '' === vals ) {
			return false;
		}

		// If multiple is false.
		if ( 'string' === typeof vals ) {
			var parsedVal = JSON.parse( vals );
			if ( ! parsedVal.id || ! parsedVal.text ) {
				return false;
			}
			return [
				{
					id  : parsedVal.id,
					text: parsedVal.text,
				}
			];
		}

		// If multiple is true.
		if ( 'object' === typeof vals && vals.length ) {
			return vals.map( function( val ) {
				var parsedVal = JSON.parse( val );
				if ( ! parsedVal.id || ! parsedVal.text ) {
					return false;
				}
				return {
					id  : parsedVal.id,
					text: parsedVal.text,
				};
			} );
		}

		return false;

	}

	elementor.hooks.addAction( 'panel/open_editor/widget/adsanity-single', initializeSingleAdSelect );
	elementor.hooks.addAction( 'panel/open_editor/widget/adsanity-oagw', initializeSingleAdSelect );

	//
	// Initialize the ad search.
	//
	function initializeSingleAdSelect( panel, model ) {

		// Handle previous value(s).
		var defaultVals = model.getSetting( 'ad' );
		var parsed      = parseOptions( defaultVals );

		if ( false !== parsed ) {
			parsed.forEach( function( ad ) {
				$( '.adsanity-elementor-ad-control select' ).append(
					$( '<option/>', {
						selected: 'selected',
						value   : JSON.stringify( ad ),
						text    : ad.text,
					} )
				);
			} );
		}

		// Initialize select2 instance.
		$( '.adsanity-elementor-ad-control select' ).select2(
			{
				ajax: {
					url : ADSANITY_ELEMENTOR.rest_url + 'ads/',
					data: function( params ) {
						return {
							orderby: 'title',
							search : params.term,
						};
					},
					processResults: function( data ) {

						var results = data.map( function( ad ) {
							var title = decodeEntities( ad.title.rendered );
							return {
								id  : JSON.stringify( { id: ad.id, text: title } ),
								text: title,
							};
						} );

						if ( false !== parsed ) {
							results = parsed.map( function( ad ) {
								var title = decodeEntities( ad.text );
								return {
									id  : JSON.stringify( { id: ad.id, text: title } ),
									text: title,
								}
							} ).concat( results );
						}

						return {
							results,
						};
					},
				}
			}
		);

	}

	elementor.hooks.addAction( 'panel/open_editor/widget/adsanity-group', initializeAdGroupSelect );
	elementor.hooks.addAction( 'panel/open_editor/widget/adsanity-random', initializeAdGroupSelect );
	elementor.hooks.addAction( 'panel/open_editor/widget/adsanity-raw', initializeAdGroupSelect );

	//
	// Initialize ad group select.
	//
	function initializeAdGroupSelect( panel, model ) {

		// Handle previous value(s).
		var defaultVals = model.getSetting( 'group' );
		var parsed      = parseOptions( defaultVals );

		if ( false !== parsed ) {
			parsed.forEach( function( group ) {
				$( '.adsanity-elementor-ad-group-control select' ).append(
					$( '<option/>', {
						selected: 'selected',
						value   : JSON.stringify( group ),
						text    : group.text,
					} )
				);
			} );
		}

		// Initialize select2 instance.
		$( '.adsanity-elementor-ad-group-control select' ).select2(
			{
				ajax: {
					url : ADSANITY_ELEMENTOR.rest_url + 'ad-group/',
					data: function( params ) {
						return {
							orderby: 'name',
							search : params.term,
						};
					},
					processResults: function( data ) {
						var results = data.map( function( group ) {
							var name = decodeEntities( group.name );
							return {
								id  : JSON.stringify( { id: group.id, text: name } ),
								text: name,
							};
						} );

						if ( false !== parsed ) {
							results = parsed.map( function( group ) {
								var name = decodeEntities( group.text );
								return {
									id  : JSON.stringify( { id: group.id, text: name } ),
									text: name,
								}
							} ).concat( results );
						}

						return {
							results,
						};
					}
				}
			}
		);

	}

	//
	// Decode HTML entitites.
	//
	var decodeEntities = ( function() {
		var element = document.createElement( 'div' );
		function decodeHTMLEntities( str ) {
			if ( str && typeof str === 'string' ) {
				// Strip script/html tags.
				str = str.replace( /<script[^>]*>([\S\s]*?)<\/script>/gmi, '' );
				str = str.replace( /<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '' );
				element.innerHTML = str;
				str = element.textContent;
				element.textContent = '';
			}
			return str;
		}
		return decodeHTMLEntities;
	} )();

} )( window.jQuery );
