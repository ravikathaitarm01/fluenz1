var partnerActions = function () {
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

        _renderActivator: function() {
            var reset = false;
            $('.x-partner-activation').bootstrapToggle().change(function(e) {
                if (reset) {
                    reset = false;
                    return null;
                }
                console.log(e.data);
                var $form = $(this).parents('form');
                var $elem = $(this);
                $.post($form.attr('action'), $form.serialize(), function(d) {
                    try {
                        d = $.parseJSON(d);
                        if (d['success']) {
                            toastr['success'](d['message']);
                            if (d['redirect']) {
                                setTimeout(function () {
                                    window.location = d['redirect']
                                }, 2000);
                            }
                            //$elem.closest('td').attr('data-search', $elem.is(':checked')? 'active': 'inactive');
                            self._invalidateRow($elem.closest('tr'));
                        } else {
                            throw d.error;
                        }
                    } catch (e) {
                        toastr['error'](e);
                        reset = true;
                        $elem.bootstrapToggle('toggle');
                    }
                });
            });
        },

        _renderLogin: function() {
            $('.x-partner-login').click(function(e) {
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
            self._renderActivator();
            self._renderLogin();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    partnerActions.init();
});

