const $ = window.jQuery;

export default function() {

	//
	// Disable 'Upload Now' button until a file is present
	//
	$( '#html5zip' ).change( function handleHTML5InputChange() {

		const value = $( this ).val();

		if ( value.substr( value.length - 4, 4 ).toLowerCase() !== '.zip' ) {
			$( '#adsanity-invalid-html5-type' ).show();
			$( '#install-html5-upload' ).attr( 'disabled', 'true' );
			return;
		}

		$( '#adsanity-invalid-html5-type' ).hide();
		$( '#install-html5-upload' ).removeAttr( 'disabled' );

	} );

	//
	// Handle upload form submit
	//
	$( '#ad-upload #install-html5-upload' ).click( function handleHTML5Upload( e ) {

		// Store the message container as a variable
		const errorMessageContainer = $( '#adsanity-html5-upload' );

		// Hide the message and remove the additional context message
		errorMessageContainer.hide();
		errorMessageContainer.find( '.adsanity-additional-message' ).remove();

		e.preventDefault();

		// Disable button and show spinner
		$( '#ad-upload .spinner' ).css( 'visibility', 'visible' );
		$( '#install-html5-upload' ).attr( 'disabled', 'true' );

		// Destroy any old instance of the iframe
		$( '#ad-upload iframe' )
			.parent( '.adsanity-inner' )
			.parent( '.iframe-wrapper' )
			.remove();

		// Create post data
		const postData = new FormData();
		postData.append( 'action', 'adsanity_html5_upload' );
		postData.append( 'security', $( '#ad-upload #html5nonce' ).val() );
		postData.append( 'ad_id', adsanity.ad_id );

		const file = document.getElementById( 'html5zip' ).files;

		$.each( file, function loopOverFiles( key, value ) {
			postData.append( 'file_upload', value );
		} );

		$.ajax( {
			url         : ajaxurl,
			type        : 'POST',
			data        : postData,
			processData : false,
			contentType : false,
			success     : function handleHTML5UploadSuccess( data ) {
				// Create iframe preview
				const iframe = document.createElement( 'iframe' );
				iframe.setAttribute( 'scrolling', 'no' );
				iframe.src = data.data.src;
				document.querySelector( '#ad-upload .inside' ).appendChild( iframe );
				updateIframeDimensions();
			},
			error : function handleHTML5UploadError( error ) {
				// Display error message if set
				if ( 'message' in error.responseJSON.data ) {
					errorMessageContainer
						.find( 'p' )
						.append(
							$( `<span class="adsanity-additional-message">${ error.responseJSON.data.message }</span>` )
						);
				}

				// Reset the input field
				$( '#html5zip' ).val( '' );

				// Show the error message
				errorMessageContainer.show();
			},
			complete : function resetForm() {
				// Enable button and hide spinner
				$( '#ad-upload .spinner' ).css( 'visibility', 'hidden' );
				$( '#install-html5-upload' ).removeAttr( 'disabled' );
			}
		} );

	} );

	//
	// Updates ad upload iframe dimensions
	//
	function updateIframeDimensions() {
		const iframe = $( '#ad-upload iframe' );
		if ( ! iframe.length ) {
			return;
		}
		const size = $( '#size' ).val();

		// Unwrap the iframe
		if ( iframe.parent( '.adsanity-inner' ).length ) {
			iframe.unwrap( '.adsanity-inner' );
		}
		if ( iframe.parent( '.iframe-wrapper' ).length ) {
			iframe.unwrap( '.iframe-wrapper' );
		}

		// Rewrap the iframe
		iframe.wrap( '<div class="adsanity-inner"></div>' );
		$( '.adsanity-inner' ).wrap( `<div class="iframe-wrapper ad-${ size }"></div>` );

	}

	// Update iframe dimensions on ad size change and on load
	$( '#size' ).change( updateIframeDimensions );
	updateIframeDimensions();

	//
	// When post form is submitted, disable the file input
	//
	$( '#post' ).one( 'submit', function onPostFormSubmit( e ) {
		e.preventDefault();
		$( '#html5zip' ).attr( 'disabled', true );
		$( '#html5nonce' ).attr( 'disabled', true );
		$( '#post' ).append( '<input type="hidden" name="publish" value="Publish"/>' );
		$( '#post' ).submit();
	} );

}
