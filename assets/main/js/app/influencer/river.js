var userActions = function () {
    var self = {
        _renderFacebookItem: function() {
            var $r = $('#url-facebook-render');
            $r.html('<div class="fb-post" data-href="' + $r.data('url').trim() + '"></div>');
            window.renderFacebookItem();
        },

        _renderTwitterItem: function() {
            var $r = $('#url-twitter-render');
            $r.html('<blockquote class="twitter-tweet" data-conversation="none"><a href="' + $r.data('url').trim() + '"></a></blockquote>');
            window.renderTwitterItem();
        },

        _renderInstagramItem: function() {
            var $r = $('#url-instagram-render');
            $r.html('<blockquote class="instagram-media" data-conversation="none"><a href="' + $r.data('url').trim() + '"></a></blockquote>');
            window.renderInstagramItem();
        },

        _renderYoutubeItem: function() {
            var $r = $('#url-google-youtube-render');
            $r.find('iframe').attr('src', 'https://www.youtube.com/embed/' + $r.data('url').trim().match(/[\w-]+$/)[0]);
        },

        _renderGooglePlusItem: function() {
            var $r = $('#url-google-plus-render');
            $r.html('<div class="g-post" data-href="' + $r.data('url').trim() + '"></div>');
        },

        //
        init: function () {
            self._renderFacebookItem();
            self._renderTwitterItem();
            self._renderInstagramItem();
            self._renderYoutubeItem();
            self._renderGooglePlusItem();
            $('#x-brand-select').change(function(){
                if ($(this).val()) {
                    var $form = $('#x-brand-form');
                    $form.find('input[name=id]').val($(this).val());
                    $form.submit();
                }
            });
        }
    };
    return self;
}();

$(function () {
    "use strict";
    userActions.init();
});

