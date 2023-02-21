const $ = window.jQuery;

export default function() {

	//
	// Initialize the colorpickers
	//
	$( '#ad-text-bg' ).wpColorPicker( {
		change: changeTinyMCEStyle,
	} );
	$( '#ad-text-border' ).wpColorPicker( {
		change: changeTinyMCEStyle,
	} );

	//
	// Add border-width listener
	//
	$( '#ad-text input[name="text_border_width"]' ).change(
		function handleBorderWidthChange() {

			changeTinyMCEStyle();

			if ( Number.parseInt( $( this ).val() ) < 0 ) {
				$( this ).val( 0 );
			}

		}
	);

	//
	// When iframe is inserted, change the background
	//
	$( '#ad-text' ).on( 'DOMNodeInserted', function( event ) {
		if ( event.target.className === 'mce-path-item' ) {
			changeTinyMCEStyle();
		}
	} );

}

//
// Changes the TinyMCE editor's background
//
function changeTinyMCEStyle() {

	// $( '.mce-statusbar.mce-container.mce-panel.mce-stack-layout-item.mce-last' ).css( {

	window.requestAnimationFrame( function() {

		const bgColor = $( '#ad-text-bg' ).val();
		const borderColor = $( '#ad-text-border' ).val();
		const borderWidth = $( 'input[name="text_border_width"]' ).val();
		const editorContentId = window.tinyMCE.get( 'adtexteditor' ).contentAreaContainer.id;

		const editorIframeBody = document.querySelector( `#${ editorContentId } iframe` ).contentDocument.body;

		editorIframeBody.style.background = bgColor;
		editorIframeBody.style.maxWidth = 'none';

		$( `#${ editorContentId } iframe` ).css( {
			borderWidth : `${ borderWidth }px`,
			borderColor : borderColor,
			borderStyle : 'solid',
			boxSizing   : 'border-box',
		} );

	} );

}
