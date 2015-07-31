$(function () {
    "use strict";

    var $queryItems = $('.x-network-marker-query-item:first').parent();
    var $urls = $('#x-data-network-url');

    $queryItems.on('click', '.x-query-item-note', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var $div = $(this);
        var divText = $div.text();
        var $edit = $('<textarea />').addClass('form-control input-sm').prop('rows', 5).val(divText);
        $div.replaceWith($edit);
        $edit.focus();
        $edit.keydown(function(e) {
           if (e.which == 27) {
               $div.html(divText);
               $edit.replaceWith($div);
           }
        });
        $edit.blur(function() {
            var editText = $edit.val();
            $div.html(editText);
            $edit.replaceWith($div);
            if (divText != editText) {
                var $item = $div.closest('.x-query-item');
                var id = $item.data('id');
                var $spinner = $item.find('.spinner:eq(0)');

                $spinner.show();

                $.post($urls.data('queryItemAction'), {action: 'note', id: id, text: editText}, function(d) {
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
                }).complete(function(){
                    $spinner.hide();
                });
            }
        });
    });

    function onContainerUpdate($queryNetworkItem, id) {
        $queryNetworkItem.unbind('DOMSubtreeModified');
        $queryNetworkItem.find('[data-id="'+id+'"]').addClass('alert alert-info alert-link');
        $queryNetworkItem.bind('DOMSubtreeModified', function(){
            onContainerUpdate($queryNetworkItem, id);
        });
    }

    $queryItems.on('click', '.x-query-item-status', function(){
        var $item = $(this).closest('.x-query-item');
        var id = $item.data('id');
        var $spinner = $item.find('.spinner:eq(0)');

        $spinner.show();
        $.post($urls.data('queryItemAction'), {action: 'status', id: id}, function(d) {
            try {
                d = $.parseJSON(d);
                if (d['success']) {
                    toastr['success'](d['message']);
                    if (d['redirect']) {
                        setTimeout(function () {
                            window.location = d['redirect']
                        }, 2000);
                    }
                    if (d['body']) {
                        $item.replaceWith(d['body']);
                    }
                } else {
                    throw d.error;
                }
            } catch (e) {
                toastr['error'](e);
            }
        }).complete(function(){
            $spinner.hide();
        });
    });

    var $queryNetworkItem = $('#x-section-query-network-item section');
    $queryItems.on('click', '.x-query-item-fetch', function(){
        var $item = $(this).closest('.x-query-item');
        var id = $item.data('item_id');
        var $spinner = $item.find('.spinner:eq(0)');

        $spinner.show();
        $.post($urls.data('queryItemAction'), {action: 'item', id: id}, function(d) {
            try {
                d = $.parseJSON(d);
                if (d['success']) {
                    //toastr['success'](d['message']);
                    if (d['redirect']) {
                        setTimeout(function () {
                            window.location = d['redirect']
                        }, 2000);
                    }
                    if (d['body']) {
                        $queryNetworkItem.unbind('DOMSubtreeModified');
                        $queryNetworkItem.html(d['body']);
                        onContainerUpdate($queryNetworkItem, id);
                    }
                } else {
                    throw d.error;
                }
            } catch (e) {
                toastr['error'](e);
            }
        }).complete(function(){
            $spinner.hide();
        });
    });

    $queryItems.on('click', '.x-query-item-remove-comment', function(){
        var $item = $(this).closest('.x-query-item');
        var id = $item.data('id');
        var $spinner = $item.find('.spinner:eq(0)');

        $spinner.show();
        $.post($urls.data('queryItemAction'), {action: 'comment', id: id, message: null}, function(d) {
            try {
                d = $.parseJSON(d);
                if (d['success']) {
                    toastr['success'](d['message']);
                    if (d['redirect']) {
                        setTimeout(function () {
                            window.location = d['redirect']
                        }, 2000);
                    }
                    if (d['body']) {
                        $item.parent().parent().replaceWith(d['body']);
                    }
                } else {
                    throw d.error;
                }
            } catch (e) {
                toastr['error'](e);
            }
        }).complete(function(){
            $spinner.hide();
        });
    });

    $queryItems.on('click', '.x-query-item-show-comment', function(){
        console.log($(this).parent().parent());
        $(this).parent().parent().find('> .media').toggle('slow');
    });

    var $popoverComment = null;
    $queryItems.on('click', 'a.x-query-item-comment', function(e){
        var $item = $(this).closest('.x-query-item');
        var id = $item.data('id');
        var $spinner = $item.find('.spinner:eq(0)');

        if ($popoverComment && $popoverComment.is(':visible')) {
            if ($popoverComment[0] == e.currentTarget) {
                return;
            } else {
                $popoverComment.popover('hide');
            }
        }

        $popoverComment = $(this).popover({
            html: true,
            position: 'right',
            trigger: 'click',
            content: function() {
                return $('#x-query-item-comment-box').html();
            }
        }).on('shown.bs.popover', function(e) {
            var $p = $popoverComment.next('div.popover');
            $p.find('.x-query-item-id').val($item.data('id'));
            $p.find('.x-query-item-comment-post').click(function(e) {
                $popoverComment.popover('hide');
                var message = $p.find('form textarea.x-query-item-comment').val();
                $spinner.show();
                $.post($urls.data('queryItemAction'), {action: 'comment', id: id, message: message}, function(d) {
                    try {
                        d = $.parseJSON(d);
                        if (d['success']) {
                            toastr['success'](d['message']);
                            if (d['redirect']) {
                                setTimeout(function () {
                                    window.location = d['redirect']
                                }, 2000);
                            }
                            if (d['body']) {
                                $item.replaceWith(d['body']);
                            }
                        } else {
                            throw d.error;
                        }
                    } catch (e) {
                        toastr['error'](e);
                    }
                }).complete(function(){
                    $spinner.hide();
                });
            });
            $p.find('.close').on('click', function (e) {
                $popoverComment.popover('hide');
            });
        }).popover('show');
    });

    var $popoverQuery = null;
    $queryItems.on('click', 'a.x-network-item-query', function(e){
        var $item = $(this).closest('.x-network-item');
        var $spinner = $item.find('.spinner:eq(0)');

        if ($popoverQuery && $popoverQuery.is(':visible')) {
            if ($popoverQuery[0] == e.currentTarget) {
                return;
            } else {
                $popoverQuery.popover('hide');
            }
        }

        $popoverQuery = $(this).popover({
            html: true,
            position: 'right',
            trigger: 'click',
            content: function() {
                return $('#x-network-item-query-box').html();
            }
        }).on('shown.bs.popover', function(e) {
            renderQueryBox($popoverQuery, $item, $spinner, $urls);
        }).popover('show');
    });
});