var brandActions = function () {
    var self = {
        _table: null,

        _invalidateRow: function (row) {
            self._table.row(row).invalidate();
        },

        _renderTable: function() {
            var url = $('.datatable').data('url');
            self._table = $('.datatable').DataTable({
                "ajax": url,
                "pageLength": 50,
                responsive: true,
                "sPaginationType": "bootstrap"
            });

            $('.chosen').chosen({
                width: "80px"
            });
        },

        init: function () {
            self._renderTable();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    brandActions.init();
});

