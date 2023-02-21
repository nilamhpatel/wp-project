// Add your custom JS here.
( function($) {  
  $( document ).ready(function() {
    $( '#homeTestimonialSlider' ).carousel( {
      interval: 8000,
      pause: false
    } );


  /**
   * Fun hamburger animation toggle
   */
  $( '.mobile-hamburger-icon' ).on( 'click', function() {
    $( this ).toggleClass( 'active' );
    $( this ).toggleClass( 'not-active' );

    $( '.reno-mobile-social-container' ).toggleClass( 'd-none' );
  } );


  /**
   * Bootstrap disables parent links from opening their page, and instead displays its children.
   * This is fine for mobile menus, but we want parent links to work on desktop.
   */
  function setNavDropdownItem() {
    var windowWidth = Math.max( document.documentElement.clientWidth || 0, window.innerWidth || 0 );
    var navDropdownItem = $( '#main-menu' ).find( "[data-bs-toggle]" );

    // 768 = $screen-md, 992 = $screen-lg
    if( windowWidth >= 768 ) {
      navDropdownItem.attr( "data-bs-toggle", 'reno-dropdown' );
    } else {
      navDropdownItem.attr( "data-bs-toggle", 'dropdown' );
    }
  }

  /**
   * Run the function should users resize their window.
   */
     window.addEventListener( 'resize', function() {
      setNavDropdownItem();
    }, true);

  setNavDropdownItem();
  });


  

$(document).on("gform_post_render", function(){
	$('.ginput_container:not(.ginput_container_fileupload) > input[type!="hidden"], .ginput_container > select').each(function(){
		if($(this).val() !== ""){
			$(this).closest(".gfield:not(.gfield_multi_time)").addClass("has-focus-or-value");
		}
	});
});

$(".gform_wrapper")
.on("focus", '.ginput_container:not(.ginput_container_fileupload) > input[type!="hidden"], .ginput_container > select', function(){
	$(this).closest(".gfield:not(.gfield_multi_time)").addClass("has-focus-or-value");
})
.on("blur", '.ginput_container:not(.ginput_container_fileupload) > input[type!="hidden"], .ginput_container > select', function(){
	if($(this).val() === ""){
		$(this).closest(".gfield:not(.gfield_multi_time)").removeClass("has-focus-or-value");
	}
});

} )( jQuery );  



