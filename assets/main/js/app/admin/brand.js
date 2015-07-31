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
                "sPaginationType": "bootstrap"
            });

            $('.chosen').chosen({
                width: "80px"
            });

            var $statusActive = $('#x-filter-status-active');
            var $statusInactive = $('#x-filter-status-inactive');
            var $package = $('.x-filter-package');

            function getFilter(key, value) {
                if (key === 'status') {
                    var active = $statusActive.is(':checked');
                    var inactive = $statusInactive.is(':checked');
                    if (active && inactive) {
                        return true;
                    } else if (active && value === 'active') {
                        return true;
                    } else if (inactive && value === 'inactive') {
                        return true;
                    }
                } else if (key === 'package') {
                    var optPackage = $.map($package.filter(':checked'), function(e) { return $(e).val() });
                    if ($package.length === optPackage.length) {
                        return true;
                    } else if (optPackage.indexOf(value) != -1) {
                        return true;
                    }
                }
                return false;
            }

            $package.change(function() {
                self._table.draw();
            });
            $statusActive.change(function() {
                self._table.draw();
            });
            $statusInactive.change(function() {
                self._table.draw();
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    console.log(data);
                    return getFilter('status', data[5]) && getFilter('package', data[2]);
                }
            );
        },

        _renderActivator: function() {
            var reset = false;
            $('.x-brand-activation').bootstrapToggle().change(function(e) {
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
            $('.x-brand-login').click(function(e) {
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
    brandActions.init();
});

