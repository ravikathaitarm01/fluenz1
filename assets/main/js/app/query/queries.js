var networkActions = function () {
    var self = {

        _streamFilter: function($container, $begin) {
            var $last = $container.children('.x-query-item:last');
            if ($begin) {
                $begin = $container.children($begin).next();
            } else {
                $begin = $container.children('.x-query-item');
            }

            var $form = $('#x-query-filter-form');
            var filterAssigner = $form.find('.x-query-filter-assigner').val();
            var filterAssigned = $form.find('.x-query-filter-assigned').val();
            var filterStatus = $form.find('.x-query-filter-status').val();

            $begin.each(function(i,e){
                var $elem = $(e).find('.x-query-item-filter-values');
                if (
                    (filterAssigner != '' && $elem.data('assigner') != filterAssigner) ||
                    (filterAssigned != '' && $elem.data('assigned') != filterAssigned) ||
                    (filterStatus != '' && $elem.data('status') != filterStatus)
                ) {
                    $(e).hide();
                } else {
                    console.log('show');
                    $(e).show();
                }
            });
            return $last;
        },

        _renderFilter: function() {
            var $scroll = $('#x-section-queries');
            var $container = $scroll.find('section');

            var $form = $('#x-query-filter-form');
            $form.find('.x-query-filter-assigner').change(function(){
                self._streamFilter($container, null);
            });
            $form.find('.x-query-filter-assigned').change(function(){
                self._streamFilter($container, null);
            });
            $form.find('.x-query-filter-status').change(function(){
                self._streamFilter($container, null);
            });
        },

        _fetchNewer: function($scroll, $container) {
            if ($scroll.find('.mCSB_container').css('top') == '0px') {
                var $firstItem = $scroll.find('.post-comments > div.media').eq(0);
                if ( ! $firstItem) {
                    return null;
                }
                var lastId = $firstItem.data('id');

                $.post($('#x-data-network-url').data('queryItemAction'), {action: 'get', page: 0, last_id: lastId}, function(d) {
                    d = $.parseJSON(d);
                    if (d['success']) {
                        $container.prepend(d['body']);
                    } else {
                        console.log(d['error']);
                        toastr['error'](d['error']);
                    }
                }).complete(function() {
                    setTimeout(function() {
                        self._fetchNewer($scroll, $container);
                    }, 5000);
                });
            } else {
                setTimeout(function() {
                    self._fetchNewer($scroll, $container);
                }, 5000);
            }
        },

        _fetchQuery: function(id) {
            var $scroll = $('#x-section-queries');
            var $spinner = $scroll.parent().find('.spinner:last');
            var $container = $scroll.find('section');
            function fetchPage() {
                $spinner.show();
                $.post($('#x-data-network-url').data('queryItemAction'), {action: 'get', page: 0, id: id}, function(d) {
                    d = $.parseJSON(d);
                    if (d['success']) {
                        $container.append(d['body']);
                    } else {
                        console.log(d['error']);
                        toastr['error'](d['error']);
                    }
                })
                    .complete(function() {
                        $spinner.hide();

                    });
            }

            $scroll.mCustomScrollbar({
                advances: {
                    updateOnContentResize: true
                },
                callbacks:{
                    onTotalScroll: function(){
                    }
                }
            });

            fetchPage();
        },

        _fetchQueries: function() {
            var $lastFiltered = null;
            var page = 0;
            var $scroll = $('#x-section-queries');
            var $spinner = $scroll.parent().find('.spinner:last');
            var $container = $scroll.find('section');
            function fetchPage() {
                $spinner.show();
                $.post($('#x-data-network-url').data('queryItemAction'), {action: 'get', page: page}, function(d) {
                    page += 1;
                    d = $.parseJSON(d);
                    //console.log(d);
                    if (d['success']) {
                        $container.append(d['body']);
                        $lastFiltered = self._streamFilter($container, $lastFiltered);
                    } else {
                        console.log(d['error']);
                        toastr['error'](d['error']);
                    }
                })
                    .complete(function() {
                        $spinner.hide();

                    });
            }

            self._fetchNewer($scroll, $container);

            $scroll.mCustomScrollbar({
                advances: {
                    updateOnContentResize: true
                },
                callbacks:{
                    onTotalScroll: function(){
                        fetchPage();
                    }
                }
            });

            fetchPage();
        },

        init: function () {
            var $e = $('#x-query-item-single');
            if ($e.length) {
                self._fetchQuery($e.data('id'));
            } else {
                self._fetchQueries();
                self._renderFilter();
            }

        }
    };
    return self;
}();

$(function () {
    "use strict";
    networkActions.init();
});