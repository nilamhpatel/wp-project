const $ = window.jQuery;

import modules from './modules';

$( window ).on( 'et_builder_api_ready', function( event, API ) {
	// Register custom modules
	API.registerModules( modules );
} );
