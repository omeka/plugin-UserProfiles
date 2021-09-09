var Omeka = Omeka || {};
Omeka.ElementSets = {};
(function($) {
    Omeka.ElementSets.toggleElement = function (button) {
        if(jQuery(button).nextAll('input.delete').val() != 1) {
            jQuery(button).nextAll('input.delete').val(1);
            
        } else {
            jQuery(button).nextAll('input.delete').val(0);
        }
    };
})(jQuery);