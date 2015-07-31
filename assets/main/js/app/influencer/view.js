var influencerActions = function () {
    var self = {
        _renderFavorite: function() {
            var $btn = $('#x-influencer-favorite').click(function(e) {
                var $form = $(this).closest('form');
                $.post($form.attr('action'), $form.serialize(), function(d) {
                    try {
                        d = $.parseJSON(d);

                        if (d['success']) {
                            if (d['set']) {
                                $btn.removeClass('btn-outline').prop('title', 'Unfavorite');
                            } else {
                                $btn.addClass('btn-outline').prop('title', 'Favorite');
                            }
                            $btn.blur();
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

        _renderListAdd: function() {
            var $btn = $('#x-influencer-list-add').click(function(e) {
                var $form = $(this).closest('form');
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
            self._renderFavorite();
            self._renderListAdd();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    influencerActions.init();
});

