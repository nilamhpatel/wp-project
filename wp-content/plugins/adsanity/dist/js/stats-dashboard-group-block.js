(()=>{var a;(a=window.jQuery)(document).ready((function(){function d(){var d=a("#adsanity-ad-group-dashboard select").val();a("#adsanity-ad-group-dashboard table").addClass("loading"),a("#adsanity-ad-group-dashboard tbody").html("<tr></tr>"),a.post(ajaxurl,{action:"adsanity_get_ads_by_term",term_id:d}).done((function(d){!function(d){for(var t in a("#adsanity-ad-group-dashboard tbody").html(""),a("#adsanity-ad-group-dashboard table").removeClass("loading"),d){var r="<tr>";r+='<td><a href="'+d[t].link+'">'+t+"<a></td>",r+="<td>"+d[t].views+"</td>",r+="<td>"+d[t].clicks+"</td>",r+="<td>"+d[t].ctr+"</td>",r+="</tr>",a("#adsanity-ad-group-dashboard tbody").append(r)}}(d.data),a("#adsanity-ad-group-dashboard").trigger("adsanity-ad-group-dashboard",d.data)}))}a("#adsanity-ad-group-dashboard select").change(d),d()}))})();