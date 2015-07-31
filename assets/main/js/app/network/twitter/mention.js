$(function () {
    "use strict";

    var $twitterMentions = $('.x-network-marker-twitter-mention:first').parent();
    var $urls = $('#x-data-network-url');

    $twitterMentions.on('click', '.x-twitter-item-favorite', function(e){
        var $item = $(this).closest('.x-network-item');
        var id = $item.data('id');
        var $spinner = $item.find('.spinner:eq(0)');

        $spinner.show();
        $.post($urls.data('networkItemTwitterAction'), {action: 'favorite', id: id}, function(d) {
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

    $twitterMentions.on('click', '.x-twitter-item-retweet', function(e){
        var $item = $(this).closest('.x-network-item');
        var id = $item.data('id');
        var $spinner = $item.find('.spinner:eq(0)');

        $spinner.show();
        $.post($urls.data('networkItemTwitterAction'), {action: 'retweet', id: id}, function(d) {
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

    $twitterMentions.on('change', 'select.x-network-item-predef', function(){
        var $e = $(this).parent().parent().find('.x-network-item-comment');
        $e.val($e.val().match(/\S+/) + ' ' + decodeURI($(this).val()));
    });
    $twitterMentions.on('click', '.x-twitter-item-show-comment', function(){
        console.log($(this).parent().parent());
        $(this).parent().parent().find('> .media').toggle('slow');
    });

    var $popoverComment = null;
    $twitterMentions.on('click', 'a.x-twitter-item-comment', function(e){
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
            var $commentMessage = $p.find('form textarea.x-network-item-comment');
            $commentMessage.val('@' + $item.data('user') + ' ');
            $p.find('.x-network-item-id').val($item.data('id'));
            $p.find('.x-network-item-comment-post').click(function(e) {
                $popoverComment.popover('hide');
                var message = $commentMessage.val();
                $spinner.show();
                $.post($urls.data('networkItemTwitterAction'), {action: 'reply', id: id, message: message}, function(d) {
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
    $twitterMentions.on('click', 'a.x-network-item-query', function(e){
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