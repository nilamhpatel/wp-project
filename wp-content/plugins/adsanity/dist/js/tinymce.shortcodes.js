"undefined"!=typeof AdSanityMCE&&tinymce.PluginManager.add("AdSanity",(function(a,d){a.addButton("adsanity_ad",{title:AdSanityMCE.adsanity_ad_tooltip,image:d+"/../../src/images/shortcode-ad-icon.png",onclick:function(){var d=a.windowManager.open({body:[{type:"listbox",name:"ad_id",label:AdSanityMCE.adsanity_ad_ads_label,values:AdSanityMCE.adsanity_ad_ads},{type:"listbox",name:"align",label:AdSanityMCE.adsanity_ad_align_label,values:AdSanityMCE.adsanity_ad_align},{type:"checkbox",name:"max_width_enabled",label:AdSanityMCE.adsanity_max_width_enabled_label,value:0,onclick:function(){var a=d.find("#max_width"),t=d.find("label")[3];this.value()?(a.disabled(!1),t.disabled(!1)):(a.disabled(!0),t.disabled(!0))}},{type:"textbox",id:"max_width",name:"max_width",label:AdSanityMCE.adsanity_max_width_label,value:"100",disabled:!0}],height:180,onsubmit:function(d){var t='[adsanity id="'.concat(d.data.ad_id,'" align="').concat(d.data.align,'"');d.data.max_width_enabled&&window._.isNumber(parseFloat(d.data.max_width))&&(t+=' max_width="'.concat(parseFloat(d.data.max_width),'"')),t+="/]",a.insertContent(t)},title:AdSanityMCE.adsanity_ad_modal_title,width:400})}}),a.addButton("adsanity_ad_group",{title:AdSanityMCE.adsanity_ad_group_tooltip,image:d+"/../../src/images/shortcode-ad-group-icon.png",onclick:function(){var d=a.windowManager.open({body:[{type:"listbox",name:"ad_groups",label:AdSanityMCE.adsanity_ad_group_groups_label,values:AdSanityMCE.adsanity_ad_group_ad_groups},{type:"listbox",name:"align",label:AdSanityMCE.adsanity_ad_align_label,values:AdSanityMCE.adsanity_ad_align},{type:"textbox",name:"num_ads",label:AdSanityMCE.adsanity_ad_group_num_ads_label},{type:"textbox",name:"num_columns",label:AdSanityMCE.adsanity_ad_group_num_columns_label},{type:"checkbox",name:"max_width_enabled",label:AdSanityMCE.adsanity_max_width_enabled_label,value:0,onclick:function(){var a=d.find("#max_width"),t=d.find("label")[5];this.value()?(a.disabled(!1),t.disabled(!1)):(a.disabled(!0),t.disabled(!0))}},{type:"textbox",id:"max_width",name:"max_width",label:AdSanityMCE.adsanity_max_width_label,value:"100",disabled:!0}],height:260,onsubmit:function(d){if(""===d.data.num_ads||""===d.data.num_columns){var t=this._id,i=jQuery("#"+t+"-body").find(".mce-formitem input");return jQuery(i).css("border-color","#ddd"),a.windowManager.alert(AdSanityMCE.empty_ad_group_fields),""===d.data.num_ads&&jQuery(i.get(0)).css("border-color","red"),""===d.data.num_columns&&jQuery(i.get(1)).css("border-color","red"),!1}var n='[adsanity_group num_ads="'.concat(d.data.num_ads,'" align="').concat(d.data.align,'" num_columns="').concat(d.data.num_columns,'" group_ids="').concat(d.data.ad_groups,'"');d.data.max_width_enabled&&window._.isNumber(parseFloat(d.data.max_width))&&(n+=' max_width="'.concat(parseFloat(d.data.max_width),'"')),n+="/]",a.insertContent(n)},title:AdSanityMCE.adsanity_ad_group_modal_title,width:400})}})}));