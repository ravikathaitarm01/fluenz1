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
            var $btn = $('.x-influencer-unfavorite').click(function(e) {
                $.post($(this).data('url'), {id: $(this).data('id')}, function(d) {
                    try {
                        d = $.parseJSON(d);
                        if (d['success']) {
                            if ( ! d['set']) {
                                self._table.row($btn.closest('tr')).remove().draw();
                            }
                            toastr['success']('Influencer has been unfavorited');
                        } else {
                            throw d.error;
                        }
                    } catch (e) {
                        toastr['error'](e);

                    }
                });
            });
        },

        _renderList: function() {
            var $content = $('#x-influencer-list-content');
            $('#x-influencer-list').change(function() {
                if ($(this).val().length == 0) {
                    return null;
                }
                $.post($(this).data('url'), {action: 'list-view', list: $(this).val()}, function(d) {
                    try {
                        d = $.parseJSON(d);
                        if (d['success']) {
                            if (d['body']) {
                                $content.html(d['body']);
                                $content.find('.datatable').DataTable({
                                    "pageLength": 50,
                                    responsive: true,
                                    "sPaginationType": "bootstrap"
                                });
                                $('.chosen').chosen({
                                    width: "80px"
                                });
                            }
                        } else {
                            throw d.error;
                        }
                    } catch (e) {
                        toastr['error'](e);
                    }
                })
            });
            $content.on('click', '.x-influencer-list-remove', function(e){
                $.post($(this).data('url'), {action: 'list-remove', list: $('#x-influencer-list').val(), id: $(this).data('id')}, function(d) {
                    try {
                        d = $.parseJSON(d);
                        if (d['success']) {
                            console.log(d);
                            if (d['body']) {
                                $content.html(d['body']);
                                toastr['success']('User removed from list');
                                $content.find('.datatable').DataTable({
                                    "pageLength": 50,
                                    responsive: true,
                                    "sPaginationType": "bootstrap"
                                });
                                $('.chosen').chosen({
                                    width: "80px"
                                });
                                if (d['redirect']) {
                                    setTimeout(function () {
                                        window.location = d['redirect']
                                    }, 1000);
                                }
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
            self._renderFavorite();
            self._renderList();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    influencerActions.init();
});

