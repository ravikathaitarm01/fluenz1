$(function () {
    "use strict";

    //var $facebookPosts = $('#x-section-facebook-posts');
    //var $facebookPosts = $('#x-section-facebook-posts');
    var $facebookPosts = $('.x-network-marker-facebook-post:first').parent();
    var $urls = $('#x-data-network-url');

    $facebookPosts.on('click', '.x-facebook-item-like', function(e){
        var $item = $(this).closest('.x-network-item');
        var id = $item.data('id');
        var $spinner = $item.find('.spinner:eq(0)');

        $spinner.show();
        $.post($urls.data('networkItemFacebookAction'), {action: 'like', id: id}, function(d) {
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

    $facebookPosts.on('change', 'select.x-network-item-predef', function(){
        $(this).parent().parent().find('.x-network-item-comment').val(decodeURI($(this).val()));
    });
    $facebookPosts.on('click', '.x-facebook-item-show-comment', function(){
        console.log($(this).parent().parent());
        $(this).parent().parent().find('> .media').toggle('slow');
    });

    var $popoverComment = null;
    $facebookPosts.on('click', 'a.x-facebook-item-comment', function(e){
        var $item = $(this).closest('.x-network-item');
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
                return $('#x-network-item-comment-box').html();
            }
        }).on('shown.bs.popover', function(e) {
            var $p = $popoverComment.next('div.popover');
            $p.find('.x-network-item-id').val($item.data('id'));
            $p.find('.x-network-item-comment-post').click(function(e) {
                $popoverComment.popover('hide');
                var message = $p.find('form textarea.x-network-item-comment').val();
                $spinner.show();
                $.post($urls.data('networkItemFacebookAction'), {action: 'comment', id: id, message: message}, function(d) {
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
                                console.log('refreshed body!');
                                console.log($item.html());
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
    $facebookPosts.on('click', 'a.x-network-item-query', function(e){
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