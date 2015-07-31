function renderQueryBox($popoverQuery, $item, $spinner, $urls) {
    var id = $item.data('id');
    var $p = $popoverQuery.next('div.popover');
    $p.find('.x-network-item-id').val($item.data('id'));
    $spinner.show();
    var $saveBtn = $p.find('.x-network-item-query-save');
    var $sentiment = $p.find('.x-network-item-sentiment');
    var $assigned = $p.find('.x-network-item-assign');
    $saveBtn.prop('disabled', true);
    $.post($urls.data('networkItemQueryAction'), {action: 'info', id: id}, function(d) {
        try {
            d = $.parseJSON(d);
            console.log(d);
            if (d['success']) {
                if (d['info'] && d['info']['details']) {
                    if (d['info']['details']['sentiment']) {
                        $p.find('.x-network-item-sentiment').val(d['info']['details']['sentiment']);
                    }
                }
                if (d['query']) {
                    $p.find('.x-network-item-assign').val(d['query']['assigned']['$id']);
                }
            } else {
                throw d.error;
            }
        } catch (e) {
            toastr['error'](e);
        }
    })
        .complete(function(){
            $spinner.hide();
            $saveBtn.prop('disabled', false);
        });

    $saveBtn.click(function(e) {
        $popoverQuery.popover('hide');
        $spinner.show();
        $.post($urls.data('networkItemQueryAction'), {
            action: 'save',
            id: id,
            sentiment: $sentiment.val(),
            assigned: $assigned.val()
        }, function(d) {
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
    });
    $p.find('.close').on('click', function (e) {
        $popoverQuery.popover('hide');
    });
}