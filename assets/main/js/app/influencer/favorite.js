var influencerActions = function () {
    var self = {
        _table: null,

        _invalidateRow: function (row) {
            self._table.row(row).invalidate();
        },

        _renderTable: function() {
            var url = $('.favorite-datatable').data('url');
            self._table = $('.favorite-datatable').DataTable({
                "ajax": url,
                "pageLength": 50,
                responsive: true,
                "sPaginationType": "bootstrap"
            });

            $('.chosen').chosen({
                width: "80px"
            });
        },

        _renderFavorite: function() {
            var $btn = $('.x-brand-unfavorite').click(function(e) {
                $.post($(this).data('url'), {id: $(this).data('id'), action: 'favorite'}, function(d) {
                    try {
                        d = $.parseJSON(d);
                        if (d['success']) {
                            if ( ! d['set']) {
                                self._table.row($btn.closest('tr')).remove().draw();
                            }
                            toastr['success']('Brand has been unfavorited');
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
            self._renderFavorite();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    influencerActions.init();
});

