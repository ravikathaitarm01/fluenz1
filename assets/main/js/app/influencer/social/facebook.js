var influencerActions = function () {
    var self = {
        _table: null,

        _invalidateRow: function (row) {
            self._table.row(row).invalidate();
        },

        _renderForm: function() {
            $('#x-page-select').change(function(){
                $('#x-page-data').val(JSON.stringify($(this).find('option:selected').data('page')));
            });
        },

        init: function () {
            self._renderForm();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    influencerActions.init();
});

