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
          'posts_per_page': el.attr( 'posts-per-page' ),
          'taxonomy': el.attr( 'tax-id' ),
          // 'post_type': el.attr( 'post-type' ),
          'excluded_ids': el.attr( 'exclude-posts' ),
          'category': el.attr( 'category' ),
          'contains': el.attr( 'contains' ),
          // 'tag': el.attr( 'tag' ),
          // 'search_value': el.attr( 'search' ),
          'query_province': el.attr( 'query-province' ), 
          'query_location': el.attr( 'query-location' ), 
          'query_city': el.attr( 'query-city' ),
          'query_type': el.attr( 'query-type' ),
          'page': current_page,
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

          var myEle = document.getElementById("renoExclusionArray");
          // var loadRenoButton = document.getElementById("loadMoreRenovators");
  
          console.log( button );
  
          if(myEle){
            console.log( 'run' );
              var myEleValue = myEle.innerText;
  
              button.attr( "exclude-posts", myEleValue );

              myEle.remove();
  
              // loadRenoButton.setAttribute("exclude-posts", myEleValue);
  
              // console.log( myEleValue );
          }
      

        } else {
          button.hide(); // if no data
        }
      }
    } );
  }
  
  $( '.load-more-projects' ).click( function(e) {
    load_more_func( e, $( this ), $( '.card-list-container' ) );
  } );

  $( '.load-more-blogs' ).click( function(e) {
    load_more_func( e, $( this ), $( '.blog-list-container' ) );
  } );

  $( '.load-more-renovators' ).click( function(e) {
    load_more_func( e, $( this ), $( '.find-a-renovator-list-container' ) );
  } );

  $( '.load-more-our-menu' ).click( function(e) {
    load_more_func( e, $( this ), $( '.our-menu-list-container' ) );
  } );
} )( jQuery );  
