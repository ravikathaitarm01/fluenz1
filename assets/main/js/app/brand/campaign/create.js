var campaignActions = function () {
    var self = {
        _renderWizard: function (data) {
            var $form = $("#x-create-form");

            /*
            $('.x-input-type').change(function(){
                $('.x-container-subtype').hide();
                $('#x-container-subtype-' + $(this).val()).show();
            })
            */
            $form.find('input[name="type"]').change(function(){
                var $e = $('input[name="type"][value="' + $(this).val() + '"]');
                $('.x-container-subtype').hide();
                if ($e.is(':checked')) {
                    $(this).parent().parent().find('.x-container-subtype').show();
                }
            });
            var $socialDiv = null;
            $form.find('input[name="subtype"]').change(function(){
                /*
                var $e = $('input[name="subtype"][value="' + $(this).val() + '"]');
                $('.x-container-subtype-ext').hide();
                if ($e.is(':checked')) {
                    $(this).parent().parent().find('.x-container-subtype-ext').show();
                }
                */
                $form.find('.x-social').hide();
                $socialDiv = $form.find('#x-social-' + $form.find('input[name="type"]:checked').val() + '-' + $form.find('input[name="subtype"]:checked').val()).show();
            });

            function treatAsUTC(date) {
                var result = new Date(date);
                result.setMinutes(result.getMinutes() - result.getTimezoneOffset());
                return result;
            }

            function daysBetween(startDate, endDate) {
                var millisecondsPerDay = 24 * 60 * 60 * 1000;
                return (treatAsUTC(endDate) - treatAsUTC(startDate)) / millisecondsPerDay;
            }


            var $dateFrom = $form.find('input[name="date[from]"]');
            var $dateTo = $form.find('input[name="date[to]"]');

            function updateDays() {
                var d1 = $dateFrom.val();
                var d2 = $dateTo.val();
                if (d1 && d2) {
                    var d = daysBetween(d1, d2);
                    if (d > 0) {
                        $('#x-input-duration-days').text(d);
                    } else {
                        if (d == 0) {
                            alert('Campaign must be a day long at minimum')
                        } else {
                            alert('End date cannot be prior to start date');
                        }
                        $dateTo.val('');
                    }
                }
            }

            $dateFrom.change(updateDays);
            $dateTo.change(updateDays);

            $form.stepy({
                backLabel: "Previous Step",
                nextLabel: "Next Step",
                errorImage: true,
                block: true,
                validate: true,
                next: function(index) {
                    if (index == 6) {   // Overview
                        $('#x-overview-title').text($form.find('input[name="title"]').val());
                        $('#x-overview-brief').text($form.find('textarea[name="brief"]').val());
                        $('#x-overview-category').text($form.find('input[name="type"]:checked').data('title') + ' : ' + $form.find('input[name="subtype"]:checked').data('title'));
                        $('#x-overview-date-from').text($dateFrom.val());
                        $('#x-overview-date-to').text($dateTo.val());
                        $('#x-overview-date-days').text($form.find('#x-input-duration-days').text() + ' Day(s)');

                        var $e = $form.find('select[name="influencer_list"] option:selected');
                        console.log($e);
                        var v = $e.text();
                        if ( ! $e.val()) {
                            v = 'auto';
                        }
                        $('#x-overview-influencer-list').text(v);

                        $socialDiv.find('input[name^=social]').each(function(i,e){
                            var value = $(this).val();
                            if ($(this).attr('type') == 'checkbox' && ! $(this).is(':checked')) {
                                value = null;
                            }
                            if ( ! value) {
                                value = '<i class="fa fa-close"></i>'
                            } else if (value == 1) {
                                value = '<i class="fa fa-check text-success"></i>'
                            } else {
                                var lbl = $(this).parent().parent().find('select').val();
                                if ( ! lbl) {
                                    lbl = '-';
                                }
                                value = '<span style="width:50px;display:inline-block;padding: 0" class="label mr5 label-default">'+lbl+'</span>'+'<a href="'+value+'" target="_blank">'+value+'</a>';
                            }

                            $('#x-overview-social-' + $(this).attr('name').replace(/[^\[]+\[([^\]]+).*/, '$1')).html(value);
                        });
                    } else if (index == 3) {
                        var oneSelected = false;
                        $socialDiv.find('input[name^=social]').each(function(i,e){
                            var value = $(this).val();
                            if ($(this).attr('type') == 'checkbox') {
                                if ($(this).is(':checked')) {
                                    oneSelected = true;
                                    return false;
                                }
                            } else {
                                if ($(this).val()) {
                                    oneSelected = true;
                                    return false;
                                }
                            }
                        });
                        if ( ! oneSelected) {
                            alert('Please specify at least one Social channel');
                            return false;
                        }
                        return oneSelected;
                    }
                }
            });

            $.validator.addMethod("subtype_required", function(value, element) {
                var $e = $form.find('input[name="type"]:checked');
                if ($e.parent().parent().find('.x-container-subtype').length) {
                    return $form.find('input[name="subtype"]:checked').length != 0;
                }
                return true;
            }, 'Subtype is required');

            $.validator.addMethod("social_required", function(value, element) {
                var $e = $form.find('input[name="type"]:checked');
                if ($e.parent().parent().find('.x-container-subtype').length) {
                    return $form.find('input[name="subtype"]:checked').length != 0;
                }
                return true;
            }, 'Subtype is required');

            $form.validate({
                errorPlacement: function (error) {
                    $form.find('.stepy-errors').append(error);
                },
                rules: {
                    'type': 'required',
                    'subtype': 'subtype_required',
                    'date[from]': 'required',
                    'date[to]': 'required',
                    'title': 'required',
                    'brief': 'required'
                },
                messages: {
                    'type': {
                        required: 'Type is required!'
                    },
                    'date[from]': {
                        required: 'Start date is required!'
                    },
                    'date[to]': {
                        required: 'End date is required!'
                    },
                    'title': {
                        required: 'Title is required!'
                    },
                    'brief': {
                        required: 'Brief is required!'
                    }
                }
            });
        },

        init: function () {
            self._renderWizard();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    campaignActions.init();
});

