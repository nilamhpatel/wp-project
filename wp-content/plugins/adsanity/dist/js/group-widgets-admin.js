(()=>{"use strict";var a=window.jQuery;const d=function(d){var t="".concat(d," .adsanity-max-width-enabled");a("body").on("change",t,(function(t){t.target.checked?a(this).closest(d).find(".adsanity-max-width").removeAttr("disabled").prev().removeClass("adsanity-label-disabled"):a(this).closest(d).find(".adsanity-max-width").attr("disabled","disabled").val(!1).prev().addClass("adsanity-label-disabled")}))};jQuery(document).ready((function(a){d(".adsanity-group-additional-options")}))})();