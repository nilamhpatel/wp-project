(function() {
    if ( typeof AdSanityMCE !== 'undefined' ) {
        tinymce.PluginManager.add('AdSanity', function( editor, url ) {
            editor.addButton( 'adsanity_ad', {
                title: AdSanityMCE.adsanity_ad_tooltip,
                image: url + '/../../src/images/shortcode-ad-icon.png',
                onclick: function() {
                    var win = editor.windowManager.open( {
                        body: [{
                            type: 'listbox',
                            name: 'ad_id',
                            label: AdSanityMCE.adsanity_ad_ads_label,
                            values: AdSanityMCE.adsanity_ad_ads
                        },{
                            type: 'listbox',
                            name: 'align',
                            label: AdSanityMCE.adsanity_ad_align_label,
                            values: AdSanityMCE.adsanity_ad_align
                        },{
                            type: 'checkbox',
                            name: 'max_width_enabled',
                            label: AdSanityMCE.adsanity_max_width_enabled_label,
                            value: 0,
                            onclick: function onMaxWidthEnabledClick() {
                                var maxWidthField = win.find( '#max_width' );
                                var maxWidthLabel = win.find( 'label' )[3];

                                if ( this.value() ) {
                                    maxWidthField.disabled( false );
                                    maxWidthLabel.disabled( false );
                                } else {
                                    maxWidthField.disabled( true );
                                    maxWidthLabel.disabled( true );
                                }
                            },
                        },{
                            type: 'textbox',
                            id: 'max_width',
                            name: 'max_width',
                            label: AdSanityMCE.adsanity_max_width_label,
                            value: '100',
                            disabled: true,
                        }],
                        height: 180,
                        onsubmit: function( e ) {
                            var shortcode = `[adsanity id="${ e.data.ad_id }" align="${ e.data.align }"`;
                            if ( e.data.max_width_enabled &&
                                 window._.isNumber( parseFloat( e.data.max_width ) ) ) {
                                shortcode += ` max_width="${ parseFloat( e.data.max_width ) }"`;
                            }
                            shortcode += '/]';
                            editor.insertContent( shortcode );
                        },
                        title: AdSanityMCE.adsanity_ad_modal_title,
                        width: 400
                    });
                }
            });

            editor.addButton( 'adsanity_ad_group', {
                title: AdSanityMCE.adsanity_ad_group_tooltip,
                image: url + '/../../src/images/shortcode-ad-group-icon.png',
                onclick: function() {
                    var win = editor.windowManager.open( {
                        body: [{
                            type: 'listbox',
                            name: 'ad_groups',
                            label: AdSanityMCE.adsanity_ad_group_groups_label,
                            values: AdSanityMCE.adsanity_ad_group_ad_groups
                        },{
                            type: 'listbox',
                            name: 'align',
                            label: AdSanityMCE.adsanity_ad_align_label,
                            values: AdSanityMCE.adsanity_ad_align
                        },{
                            type: 'textbox',
                            name: 'num_ads',
                            label: AdSanityMCE.adsanity_ad_group_num_ads_label,
                        },{
                            type: 'textbox',
                            name: 'num_columns',
                            label: AdSanityMCE.adsanity_ad_group_num_columns_label,
                        },{
                            type: 'checkbox',
                            name: 'max_width_enabled',
                            label: AdSanityMCE.adsanity_max_width_enabled_label,
                            value: 0,
                            onclick: function onMaxWidthEnabledClick() {
                                var maxWidthField = win.find( '#max_width' );
                                var maxWidthLabel = win.find( 'label' )[5];

                                if ( this.value() ) {
                                    maxWidthField.disabled( false );
                                    maxWidthLabel.disabled( false );
                                } else {
                                    maxWidthField.disabled( true );
                                    maxWidthLabel.disabled( true );
                                }
                            },
                        },{
                            type: 'textbox',
                            id: 'max_width',
                            name: 'max_width',
                            label: AdSanityMCE.adsanity_max_width_label,
                            value: '100',
                            disabled: true,
                        }],
                        height: 260,
                        onsubmit: function( e ) {

                            if ( e.data.num_ads === '' || e.data.num_columns === '' ) {
                                var window_id = this._id;
                                var inputs = jQuery( '#' + window_id + '-body' ).find( '.mce-formitem input' );
                                jQuery( inputs ).css( 'border-color', '#ddd' );

                                editor.windowManager.alert( AdSanityMCE.empty_ad_group_fields );

                                if ( e.data.num_ads === '') {
                                    jQuery( inputs.get(0) ).css( 'border-color', 'red' );
                                }

                                if ( e.data.num_columns === '' ) {
                                    jQuery( inputs.get(1) ).css( 'border-color', 'red' );
                                }

                                return false;
                            }

                            var shortcode = `[adsanity_group num_ads="${ e.data.num_ads }" align="${ e.data.align }" num_columns="${ e.data.num_columns }" group_ids="${ e.data.ad_groups }"`;
                            if ( e.data.max_width_enabled &&
                                window._.isNumber( parseFloat( e.data.max_width ) ) ) {
                                shortcode += ` max_width="${ parseFloat( e.data.max_width ) }"`;
                            }
                            shortcode += '/]';

                            editor.insertContent( shortcode );
                        },
                        title: AdSanityMCE.adsanity_ad_group_modal_title,
                        width: 400
                    });
                }
            });
        });
    }
})();
