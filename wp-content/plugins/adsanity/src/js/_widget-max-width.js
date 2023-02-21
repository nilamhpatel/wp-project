const $ = window.jQuery;

//
// Handle max width toggle in widget
//
// groupSelector should be the container that
// contains both the `adsanity-max-width-enabled`
// and `adsanity-max-width` inputs
//
function handleMaxWidth( groupSelector ) {

	// The checkbox selector
	const checkboxSelector = `${ groupSelector } .adsanity-max-width-enabled`;

	// Disable/Enable max-width
	$( 'body' ).on( 'change', checkboxSelector, function onMaxWidthEnabled( event ) {

		if ( event.target.checked ) {
			$( this )
				.closest( groupSelector )
				.find( '.adsanity-max-width' )
				.removeAttr( 'disabled' )
				.prev()
				.removeClass( 'adsanity-label-disabled' )
		} else {
			$( this )
				.closest( groupSelector )
				.find( '.adsanity-max-width' )
				.attr( 'disabled', 'disabled' )
				.val( false )
				.prev()
				.addClass( 'adsanity-label-disabled' )
		}

	} );

}

export default handleMaxWidth;
