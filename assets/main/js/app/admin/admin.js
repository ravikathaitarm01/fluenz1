var adminActions = function () {
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
                "sPaginationType": "bootstrap"
            });

            $('.chosen').chosen({
                width: "80px"
            });
        },

        _renderLogin: function() {
            $('.x-admin-login').click(function(e) {
                $.post($(this).data('url'), {id: $(this).data('id'), action: 'login'}, function(d) {
                    try {
                        d = $.parseJSON(d);
                        if (d['success']) {
                            toastr['success'](d['message']);
                            if (d['redirect']) {
                                setTimeout(function () {
                                    window.location = d['redirect']
                                }, 2000);
                            }
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
            self._renderLogin();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    adminActions.init();
});

