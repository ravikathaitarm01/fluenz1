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

            var $btn = $('.x-campaign-remove').click(function(e){
                $.post($(this).data('url'), {id: $(this).data('id')}, function(d) {
                    try {
                        d = $.parseJSON(d);
                        if (d['success']) {
                            self._table.row($btn.closest('tr')).remove().draw();
                            toastr['success'](d['message']);
                        } else {
                            throw d.error;
                        }
                    } catch (e) {
                        toastr['error'](e);
                    }
                });
                e.preventDefault();
                e.stopImmediatePropagation();
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

