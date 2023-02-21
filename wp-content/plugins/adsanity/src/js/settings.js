import 'chosen-js';

jQuery( document ).ready( function( $ ) {

	// Initialize chosen
	$( 'select[multiple]' ).chosen();

	// Click listeners
	$( '.automatic-inclusion-rules' ).on( 'click', '.add-rule', addRuleset );
	$( '.automatic-inclusion-rules' ).on( 'click', '.remove-rule', removeRuleset );

	// Enable/Disable remove rule button(s) on load
	disableEnableRemoveRule();

	// Hide/Show rulesets
	hideShowRulesets();
	$( '#adsanity_show_in_content' ).change( hideShowRulesets );

	//
	// Handle dynamic input change disabled field toggling
	//
	$( '.automatic-inclusion-rules' ).on( 'change', '.position input[type="radio"]', function handleDynamicInputChange() {
		if ( $( this ).val() === 'dynamic' ) {
			$( this ).siblings().removeAttr( 'disabled' );
		} else {
			$( this )
			.parent()
			.parent()
			.find( 'input[type="number"].dynamic, select.dynamic' )
			.attr( 'disabled', 'disabled' );
		}
	} );

	$( '#adsanity-reset-stats button' ).on( 'click', function( e ) {
		e.preventDefault();

		let confirmed = confirm( window.ADSANITY_SETTINGS.reset_confirmation );
		if ( confirmed ) {
			window.location.href = window.ADSANITY_SETTINGS.reset_url;

		}
	});

	//
	// Handle add rule
	//
	function addRuleset( e ) {

		e.preventDefault();

		var ruleset = $( '.ruleset' ).clone();

		// Get the new index for names, ids, etc
		var newIndex = $( '.ruleset' ).length;

		// Change the name, for, and id attributes
		ruleset.find( 'input, select' ).each( function replaceNameAttributes() {
			const currentName = $( this ).attr( 'name' );
			if ( ! currentName ) {
				return;
			}
			$( this ).attr( 'name', currentName.replace( /[0-9].*?/, newIndex ) )
		} );

		ruleset.find( '.alignment input' ).each( function replaceIds() {
			const currentId = $( this ).attr( 'id' );
			if ( ! currentId ) {
				return;
			}
			$( this ).attr( 'id', currentId.replace( /[0-9].*?/, newIndex ) )
		} );

		ruleset.find( '.alignment label' ).each( function replaceIds() {
			const currentFor = $( this ).attr( 'for' );
			if ( ! currentFor ) {
				return;
			}
			$( this ).attr( 'for', currentFor.replace( /[0-9].*?/, newIndex ) )
		} );

		// Reset all of the values
		ruleset.find( 'select option:selected' ).removeAttr( 'selected' );
		ruleset.find( 'input:checked' ).removeAttr( 'checked' );
		ruleset.find( '.position input[value="before"]' ).attr( 'checked', 'checked' );
		ruleset.find( '.alignment input[value="none"]' ).attr( 'checked', 'checked' );

		// Hide extra fields.
		ruleset.find( '.hide-on-duplication' ).hide();

		// Remove chosen containers
		$( ruleset ).find( '.chosen-container' ).remove();

		// Append the clone
		$( '.automatic-inclusion-rules' ).append( ruleset[0] );

		// Enable "Remove Rule" buttons
		disableEnableRemoveRule();

		// Reapply chosen to post types multiselect
		$( ruleset ).find( '.post-types' ).show().chosen();

	}

	//
	// Remove a ruleset
	//
	function removeRuleset() {

		if ( $( '.automatic-inclusion-rules .ruleset' ).length < 2 ) {
			return;
		}
		$( this ).closest( '.ruleset' ).remove();
		reorderRulesets();
		disableEnableRemoveRule();

	}

	//
	// Reorder ruleset for, name, and id attributes
	//
	function reorderRulesets() {

		$( '.automatic-inclusion-rules .ruleset' ).each( function reorderRuleset( index ) {
			// Change the name, for, and id attributes
			$( this ).find( 'input, select' ).each( function replaceNameAttributes() {
				const currentName = $( this ).attr( 'name' );
				if ( ! currentName ) {
					return;
				}
				$( this ).attr( 'name', currentName.replace( /[0-9].*?/, index ) )
			} );

			$( this ).find( '.alignment input' ).each( function replaceIds() {
				const currentId = $( this ).attr( 'id' );
				if ( ! currentId ) {
					return;
				}
				$( this ).attr( 'id', currentId.replace( /[0-9].*?/, index ) )
			} );

			$( this ).find( '.alignment label' ).each( function replaceIds() {
				const currentFor = $( this ).attr( 'for' );
				if ( ! currentFor ) {
					return;
				}
				$( this ).attr( 'for', currentFor.replace( /[0-9].*?/, index ) )
			} );
		} );

	}

	//
	// Disable or Enable the "Remove Rule" button
	//
	function disableEnableRemoveRule() {
		if ( $( '.automatic-inclusion-rules .ruleset' ).length > 1 ) {
			$( '.automatic-inclusion-rules .remove-rule' ).removeAttr( 'disabled' );
		} else {
			$( '.automatic-inclusion-rules .remove-rule' ).attr( 'disabled', 'disabled' );
		}
	}

	//
	// Hide or show rulesets
	//
	function hideShowRulesets() {
		if ( $( '#adsanity_show_in_content:checked' ).length ) {
			$( '.automatic-inclusion-rules .ruleset' ).show()
		} else {
			$( '.automatic-inclusion-rules .ruleset' ).hide();
		}
	}

	//
	// Max width
	//
	$( '.automatic-inclusion-rules' ).on( 'change', '.adsanity-automatic-inclusion-max-width-enabled-container input', function() {

		if ( this.checked ) {
			$( this ).parent().next().find( 'input' ).removeAttr( 'disabled' );
			return;
		}
		$( this ).parent().next().find( 'input' ).attr( 'disabled', 'disabled' );
	} );

} );
