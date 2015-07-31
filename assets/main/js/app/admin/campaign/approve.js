var adminActions = function () {
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
                    return getFilter('score', data[5]);
                }
            );
        },

        _renderSelector: function() {
            var $selectorView = $('#x-selected-influencers-view');
            var $selectorList = $('#x-selected-influencers');

            var influencerList = [];
            if ($selectorList.val()) {
                influencerList = $selectorList.val().split(',');
            }
            $('#x-influencers-finish').click(function(){
                $selectorList.val(influencerList.join(','));
            });

            $('#x-influencers-list').on('click', '.x-btn-influencer-add', function(e) {
                var $e = $(this).closest('tr');
                var id = $e.data('id');
                var idx = influencerList.indexOf(id);
                if (idx == -1) {
                    influencerList.push(id);
                    $selectorView.append('<div class="col-sm-3 mb5"><div class="col-sm-2"><button type="button" class="btn btn-danger btn-xs x-btn-influencer-remove" data-id="'+$e.data('id')+'"><i class="fa fa-close"></i></button></div>'
                    + '<div class="col-sm-9"><a href="'+$e.data('url')+'">'+ $e.data('username') +'</a></div></div>');
                }
                console.log(influencerList);
                e.stopImmediatePropagation();
                e.preventDefault();
            });

            $selectorView.on('click', '.x-btn-influencer-remove', function(e) {
                var $e = $(this).parent().parent();
                var id = $(this).data('id');
                var idx = influencerList.indexOf(id);
                if (idx != -1) {
                    influencerList.splice(idx, 1);
                    $e.remove();
                }
                console.log(influencerList);
                e.stopImmediatePropagation();
                e.preventDefault();
            });
        },

        init: function () {
            self._renderTable();
            self._renderSelector();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    adminActions.init();
});

