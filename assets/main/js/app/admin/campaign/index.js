var adminActions = function () {
    var self = {
        init: function () {
            $('a[href="'+window.location.href+'"]').addClass('btn-primary bolder')

            $('.datatable').DataTable({
                "pageLength": 50,
                responsive: true,
                "sPaginationType": "bootstrap"
            });

            $('.chosen').chosen({
                width: "80px"
            });
        }
    };
    return self;
}();

$(function () {
    "use strict";
    adminActions.init();
});

