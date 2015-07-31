$(function(){
    $('form.login-form').submit(function(e) {
        $.post($(this).attr('action'), $(this).serialize(), function(d) {
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
        e.stopImmediatePropagation();
        e.preventDefault();
    });
});
