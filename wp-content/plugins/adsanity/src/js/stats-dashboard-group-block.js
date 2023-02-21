( function( $ ) {

	$( document ).ready( function() {

		function getAds() {

			var termId = $( '#adsanity-ad-group-dashboard select' ).val();

			$( '#adsanity-ad-group-dashboard table' ).addClass( 'loading' );
			$( '#adsanity-ad-group-dashboard tbody' ).html( '<tr></tr>' );

			$.post( ajaxurl, {
				action  : 'adsanity_get_ads_by_term',
				term_id : termId,
			} )
			.done( function( response ) {

				constructTable( response.data );
				$( '#adsanity-ad-group-dashboard' ).trigger( 'adsanity-ad-group-dashboard', response.data );

			} );

		}

		function constructTable( data ) {

			$( '#adsanity-ad-group-dashboard tbody' ).html( '' );
			$( '#adsanity-ad-group-dashboard table' ).removeClass( 'loading' );

			for ( var ad in data ) {

				var row = '<tr>';
					row += '<td><a href="' + data[ ad ].link + '">' + ad + '<a></td>';
					row += '<td>' + data[ ad ].views + '</td>';
					row += '<td>' + data[ ad].clicks + '</td>';
					row += '<td>' + data[ ad ].ctr + '</td>';
				row += '</tr>';

				$( '#adsanity-ad-group-dashboard tbody' ).append( row );
			}

		}

		$( '#adsanity-ad-group-dashboard select' ).change( getAds );

		getAds();

	} );

} )( window.jQuery );
