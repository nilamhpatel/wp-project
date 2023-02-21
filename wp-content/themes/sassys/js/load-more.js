/**
 * Function for loading more items via ajax calls
 */
( function($) {
  function load_more_func( e, el, item ) {
    e.preventDefault();
  
    // disable load more button for accidental double clicks
    el.prop( 'disabled', true );
  
    // Pass in any necessary attributes
    var button       = el,
        max_page     = el.attr( 'max-pages' ),
        current_page = el.attr( 'current-page' ),
        data         = {
          'action': el.attr( 'action' ),
          'category_name': el.attr( 'category' ),
          'tag': el.attr( 'tag' ),
          'post_type': el.attr( 'post-type' ),
          'posts_per_page': el.attr( 'posts-per-page' ),
          'excluded_ids': el.attr( 'excluded-ids' ),
          'search_value': el.attr( 'search' ),
          'q_topic': el.attr( 'q-topic' ), // name
          'q_author': el.attr( 'q-author' ), // member_type 
          'q_year': el.attr( 'q-year' ), // city
          'page': current_page,
          'cat': el.attr( 'cat' ),
          'filter': el.attr( 'filter' ),
          'site': '' // value required for multisites
        };
  
    // Get the button text so it can reset
    var buttonText = el.text();
    
    // console.log( max_page );
    console.log( data );
    
    $.ajax( {
      url: data.site + '/wp-admin/admin-ajax.php', // AJAX handler
      data: data,
      type: 'POST',
      
      beforeSend: function() {
        if( document.documentElement.lang.includes( 'fr' ) ) {
					button.text( 'Chargement...' ); // change the button text
				}

        else {
          button.text( 'Loading...' ); // change the button text
        }
      },
      
      success: function( html ) {
        el.prop( 'disabled', false ); // re-enable the load more button

        button.text( buttonText );
  
        if( html ) {
          // console.log( html );
          
          item.append( html );
          button.attr( 'current-page', ++current_page );

          // button.text( '' );
          // button.append( buttonText + '<i class="fas fa-long-arrow-right"></i>' );

          if( current_page == max_page ) {
            button.hide(); // if last page
          }

        } else {
          button.hide(); // if no data
        }
      }
    } );
  }
  
  $( '.load-more-industry-news' ).click( function(e) {
    load_more_func( e, $( this ), $( '.industry-news-post-list' ) );
  } );

  $( '.load-more-search-results' ).click( function(e) {
    load_more_func( e, $( this ), $( '.search-results-list' ) );
  } );

  $( '.load-more-pdf-cards' ).click( function(e) {
    load_more_func( e, $( this ), $( '.pdf-post-list' ) );
  } );

} )( jQuery );  
