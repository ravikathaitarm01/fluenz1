var adminActions = function () {
    var self = {
        init: function () {
            $('#x-btn-campaign-reject').click(function(e){
                if ( ! confirm('Are you sure you wish to reject this campaign?')) {
                    e.stopImmediatePropagation();
                    e.preventDefault();
                }
            });
        }
    };
    return self;
}();

$(function () {
    "use strict";
    adminActions.init();
});

