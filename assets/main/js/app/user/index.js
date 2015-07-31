var accountActions = function () {
    var self = {
        _renderForm: function (data) {
            var $form = $('#x-form-user');
            var inputKeys = ['first_name', 'last_name', 'username', 'email', 'gender'];
            for (var i=0; i<inputKeys.length; i++) {
                $form.find('[name=' + inputKeys[i] + ']').val(data[inputKeys[i]]);
            }

            $form.find('[name=id]').val(data['_id']['$id']);
            $form.find('[name=active]').prop('checked', data['active']).change();
            var $role = $form.find('[name=role]');
            if ($role.find('option[value=' + data['role']['name'] + ']').length) {
                $role.prop('disabled', false);
                $role.val(data['role']['name']);
            } else {
                $role.prop('disabled', true);
            }

            var selfUser = $('html').data('user') == data['_id']['$id'];
            $form.find('input[name=active]').prop('disabled', selfUser).change();
            $form.find('button[value=update]').prop('disabled', false);
            $form.find('button[value=remove]').prop('disabled', selfUser);
            $form.find('button[value=login]').prop('disabled', selfUser);

        },

        _renderSelectUser: function() {
            var $selectUser = $('#x-select-user').change(function (e) {
                $.post($(this).data('url'), {action: 'info', id: $(this).val()}, function (d) {
                    try {
                        d = $.parseJSON(d);
                        if (d['success']) {
                            var user = d['user'];
                            self._renderForm(user);
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

            var $opt = $selectUser.find('option[value!=""]');
            if ($opt.length == 1) {
                $selectUser.val($opt.eq(0).val()).change().trigger("chosen:updated");
            }

            return $selectUser;
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

        renderUserActionButtons: function() {
            var $createButtons = $('#x-form-create-user-buttons');
            var $updateButtons = $('#x-form-update-user-buttons');

            $('#x-select-account-btn-add').click(function (e) {
                userActions._emptyForm(true);

                $updateButtons.hide();
                $createButtons.show();
            });

            $createButtons.find('.btn-danger').click(function(e) {
                userActions._emptyForm(false);
                $updateButtons.show();
                $createButtons.hide();
                e.preventDefault();
                e.stopImmediatePropagation();
            });
        },

        init: function () {
            self._renderSelectUser();
            self._renderSelectAccount();
            self.renderUserActionButtons();
        }
    };
    return self;
}();

var userActions = function () {
    var self = {
        _emptyForm: function (createForm) {
            var $form = $('#x-form-user');
            $form.find('input[type=text],input[type=password],input[type=email],select').each(function(i,e) {
                $(e).prop('disabled', false).val('');
            });
            $form.find('[type=checkbox]').prop('checked', false).change();
            var role = $('html').data('role');
            $('#x-enabled-username').prop('checked', false).change().prop('disabled', false);

            if ( ! createForm && ['root', 'superadmin'].indexOf(role) === -1) {
                $form.find('[name=username]').prop('disabled', true);
            }
            if (createForm) {
                $('#x-select-user').val('').prop('disabled', true).trigger("chosen:updated");
                $('#x-enabled-username').prop('checked', true).change().prop('disabled', true);
            } else {
                $('#x-select-user').prop('disabled', false).trigger("chosen:updated");
            }
            $form.find('#x-form-update-user-buttons button').prop('disabled', true);
        },

        _getFormPostData: function (key, $form) {
            var d = toSerialObject($form.serializeArray());
            var id = $form.find('[name=id]').val();
            if (key === 'login') {
                return {
                    action: 'login',
                    id: id
                };
            } else if (key === 'update') {
                d['action'] = 'update';
                return d;
            } else if (key === 'login') {
                return {
                    action: 'login',
                    id: id
                };
            } else if (key === 'remove') {
                return {
                    action: 'remove',
                    id: id
                };
            } else if (key === 'create') {
                d['action'] = 'create';
                d['account_id'] = $('#x-select-account').val();
                delete d['_id'];
                //console.log(d);
                return d;
            }
        },

        _postFormRender: function (key, data) {
            var $selectUser = $('#x-select-user');
            var d = data['user'];
            if (key === 'update') {
                $selectUser.find('option[value="' + d['_id']['$id'] + '"]').text(d['first_name'] + ' ' + d['last_name']).trigger("chosen:updated");
            } else if (key === 'remove') {
                var $opt = $selectUser.find('option[value="' + d['_id']['$id'] + '"]');
                if ($opt.prop('selected')) {
                    $selectUser.val('');
                    self._emptyForm(false);
                }
                $opt.remove();
                $selectUser.trigger("chosen:updated");
            } else if (key === 'create') {
                $selectUser.find('optgroup[label="'+data['account']['name']+'"]').append('<option value="'
                    + d['_id']['$id'] + '">'
                    + d['first_name'] + ' ' + d['last_name']
                    + '</option>').trigger("chosen:updated");
                self._emptyForm(true);
            }
        },

        _renderUserForm: function() {
            var $form = $('#x-form-user');

            $('#x-enabled-username').change(function() {
                $form.find('input[name=username]').prop('disabled', ! $(this).prop('checked'));
            }).change();

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
            self._renderUserForm();
            self._emptyForm();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    userActions.init();
    accountActions.init();
});

