jQuery(document).ready(function($) {
	var clipboard = new Clipboard('.adsanity-clipboard');
	clipboard.on('success', function(e) {
		alert( 'Copied to the clipboard.' );

		e.clearSelection();
	});

	clipboard.on('error', function(e) {});
	$('.adsanity-clipboard').on('click', function(e) {
		e.preventDefault();
	});
});
