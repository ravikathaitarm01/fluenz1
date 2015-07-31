$(function () {
    "use strict";
    var $form = $('#x-form-page');
    $('#x-page-select').change(function() {
        if ($(this).val()) {
            $form.find('input[name=page_data]').val(JSON.stringify($(this).find('option:selected').data('page')));
        }
    });
});

