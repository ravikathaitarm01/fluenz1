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

            var $score = $('.x-filter-score');

            function getFilter(key, value) {
                if (key === 'score') {
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

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    console.log(data);
                    return getFilter('score', data[3]);
                }
            );
        },

        init: function () {
            self._renderTable();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    influencerActions.init();
});

