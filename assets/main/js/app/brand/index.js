var brandSelector = function () {
    var self = {
        _renderForm: function (data) {
            var $form = $('#x-form-brand');
            var inputKeys = ['name', 'description'];
            for (var i=0; i<inputKeys.length; i++) {
                $form.find('[name=' + inputKeys[i] + ']').val(data[inputKeys[i]]);
            }

            var o = '';
            for (var i in data['all_users']) {
                o += '<option value="' + i + '">' + data['all_users'][i]['username'] + '</option>';
            }
            $('#x-brand-users').html(o).change().trigger('chosen:updated');

            var users = [];
            for (var i in data['users']) {
                users.push(data['users'][i]['_id']['$id']);
            }
            $form.find('[name=id]').val(data['_id']['$id']);
            $form.find('[name="users[]"]').val(users).change().trigger('chosen:updated');
            $form.find('button[value=update]').prop('disabled', false);
            $form.find('button[value=remove]').prop('disabled', false);
        },

        _renderSelectBrand: function() {
            var $selectBrand = $('#x-select-brand').change(function (e) {
                if ( ! $(this).val()) {
                    return null;
                }
                console.log($(this).val());
                $.post($(this).data('url'), {action: 'info', id: $(this).val()}, function (d) {
                    try {
                        d = $.parseJSON(d);
                        if (d['success']) {
                            var brand = d['brand'];
                            self._renderForm(brand);
                            if (d['message']) {
                                toastr['success'](d['message']);
                            }

                        } else {
                            throw d.error;
                        }
                    } catch (e) {
                        toastr['error'](e);
                    }
                    if (d && d['redirect']) {
                        setTimeout(function () {
                            window.location = d['redirect']
                        }, 2000);
                    }
                });
            });

            var $opt = $selectBrand.find('option[value!=""]');
            if ($opt.length == 1) {
                $selectBrand.val($opt.eq(0).val()).change().trigger("chosen:updated");
            }

            return $selectBrand;
        },

        _renderSelectAccount: function() {
            var $selectAccount = $('#x-select-account').change(function (e) {
                if ($(this).val()) {
                    $(this).parent().parent().find('button').prop('disabled', false);
                }
            });
            var $opt = $selectAccount.find('option[value!=""]');
            if ($opt.length == 1) {
                $selectAccount.val($opt.eq(0).val()).change().prop('disabled', true).trigger("chosen:updated");
            }

            return $selectAccount;
        },

        _renderFormButtons: function() {
            var $createButtons = $('#x-form-create-brand-buttons');
            var $updateButtons = $('#x-form-update-brand-buttons');

            $('#x-select-account-btn-add').click(function (e) {
                brandActions._emptyForm(true);
                $.post($(this).data('url'), {action: 'all_users', id: $('#x-select-account').val()}, function(d) {
                    try {
                        d = $.parseJSON(d);
                        if (d['success']) {
                            var users = d['users'];
                            var o = '';
                            for (var i in users) {
                                o += '<option value="' + i + '">' + users[i]['username'] + '</option>';
                            }
                            $('#x-brand-users').html(o).change().trigger('chosen:updated');
                            if (d['message']) {
                                toastr['success'](d['message']);
                            }

                        } else {
                            throw d.error;
                        }
                    } catch (e) {
                        toastr['error'](e);
                    }
                });
                $('#x-select-brand').val('').change().prop('disabled', true).trigger('chosen:updated');
                $updateButtons.hide();
                $createButtons.show();
            });


            $createButtons.find('.btn-danger').click(function(e) {
                brandActions._emptyForm(false);
                $updateButtons.show();
                $createButtons.hide();
                $('#x-select-brand').val('').change().prop('disabled', false).trigger('chosen:updated');
                e.preventDefault();
                e.stopImmediatePropagation();
            });
        },

        init: function () {
            self._renderSelectBrand();
            self._renderSelectAccount();
            self._renderFormButtons();
        }
    };
    return self;
}();

var brandActions = function () {
    var self = {
        _emptyForm: function (createForm) {
            var $form = $('#x-form-brand');
            $form.find('input[type=text],textarea,select').each(function(i,e) {
                $(e).prop('disabled', false).val('');
            });
            $form.find('[name="users[]"]').val([]).change().trigger('chosen:updated');
            $form.find('#x-form-update-brand-buttons button').prop('disabled', true);
        },

        _getFormPostData: function (key, $form) {
            var d = toSerialObject($form.serializeArray());
            console.log(d);
            var id = $form.find('[name=id]').val();
            if (key === 'update') {
                d['action'] = 'update';
                return d;
            } else if (key === 'remove') {
                return {
                    action: 'remove',
                    id: id
                };
            } else if (key === 'create') {
                d['action'] = 'create';
                d['account_id'] = $('#x-select-account').val();
                delete d['id'];
                return d;
            }
        },

        _postFormRender: function (key, data) {
            var $selectBrand = $('#x-select-brand');
            var d = data['brand'];
            if (key === 'update') {
                $selectBrand.find('option[value="' + d['_id']['$id'] + '"]').text(d['name']).trigger("chosen:updated");
            } else if (key === 'remove') {
                var $opt = $selectBrand.find('option[value="' + d['_id']['$id'] + '"]');
                if ($opt.prop('selected')) {
                    $selectBrand.val('');
                    self._emptyForm(false);
                }
                $opt.remove();
                $selectBrand.trigger("chosen:updated");
            } else if (key === 'create') {
                $selectBrand.find('optgroup[label="'+data['account']['name']+'"]').append('<option value="'
                + d['_id']['$id'] + '">'
                + d['name']
                + '</option>').trigger("chosen:updated");
                self._emptyForm(true);
            }
        },

        _renderBrandForm: function() {
            var $form = $('#x-form-brand');

            $form.find('button[type=button]').click(function(e) {
                var key = $(this).val();
                if ( $(this).data('confirmAction') && ! confirm($(this).data('confirmAction'))) {
                    return false;
                }
                var _d = self._getFormPostData(key, $form);
                if ( ! _d) {
                    return null;
                }

                var url = $(this).data('url');
                if ( ! url) {
                    url = $form.attr('action');
                }
                $.post(url, _d, function(d) {
                    try {
                        d = $.parseJSON(d);
                        console.log(d);
                        if (d['success']) {
                            self._postFormRender(key, d);
                            toastr['success'](d['message']);
                        } else {
                            throw d.error;
                        }
                    } catch (e) {
                        console.log(e);
                        toastr['error'](e);
                    }
                    if (d && d['redirect']) {
                        setTimeout(function () {
                            window.location = d['redirect']
                        }, 2000);
                    }
                });
                e.preventDefault();
                e.stopImmediatePropagation();
            });
            return $form;
        },

        init: function () {
            self._renderBrandForm();
            self._emptyForm();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    brandSelector.init();
    brandActions.init();
});

