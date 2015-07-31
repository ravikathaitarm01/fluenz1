var influencerActions = function () {
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

            var $statusActive = $('#x-filter-status-active');
            var $statusInactive = $('#x-filter-status-inactive');
            var $score = $('.x-filter-score');

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
                } else if (key === 'score') {
                    var optPackage = $.map($score.filter(':checked'), function(e) { return $(e).val() });
                    if ($score.length === optPackage.length) {
                        return true;
                    } else {
                        for (var i=0; i<optPackage.length; i++) {
                            var t = optPackage[i].split('-');
                            value = parseInt(value);
                            if (value >= t[0] && value < t[1]) {
                                return true;
                            }
                        }
                    }
                }
                return false;
            }

            $score.change(function() {
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
                    return getFilter('status', data[6]) && getFilter('score', data[5]);
                }
            );
        },

        _renderActivator: function() {
            var reset = false;
            $('.x-influencer-activation').bootstrapToggle().change(function(e) {
                if (reset) {
                    reset = false;
                    return null;
                }
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
                            $elem.closest('td').attr('data-search', $elem.is(':checked')? 'active': 'inactive');
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
            $('.x-influencer-login').click(function(e) {
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
    influencerActions.init();
});

