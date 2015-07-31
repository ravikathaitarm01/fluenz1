var userActions = function () {
    var self = {
        _renderUpdateForm: function() {
            var $form = $('#x-form-user');
            $('#x-form-update-user').click(function(e) {
                var url = $(this).data('url');
                if ( ! url) {
                    url = $form.attr('action');
                }
                $.post(url, toSerialObject($form.serializeArray()), function(d) {
                    try {
                        d = $.parseJSON(d);
                        console.log(d);
                        if (d['success']) {
                            toastr['success'](d['message']);
                        } else {
                            throw d.error;
                        }
                    } catch (e) {
                        console.log(e);
                        toastr['error'](e);
                    }
                    if (d && d['redirect']) {
                        setTimeout(function () {
                            window.location = d['redirect']
                        }, 2000);
                    }
                });
                e.preventDefault();
                e.stopImmediatePropagation();
            });
            return $form;
        },

        init: function () {
            self._renderUpdateForm();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    userActions.init();
});

