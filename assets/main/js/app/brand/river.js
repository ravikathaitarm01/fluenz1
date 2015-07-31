var userActions = function () {
    var self = {
        _renderFacebookItem: function() {
            $('#url-facebook').change(function(){
                console.log($(this).val());
                $('#url-facebook-render').html('<div class="fb-post" data-href="' + $(this).val().trim() + '"></div>');
                window.renderFacebookItem();
            }).change();
        },

        _renderTwitterItem: function() {
            $('#url-twitter').change(function(){
                console.log($(this).val());
                $('#url-twitter-render').html('<blockquote class="twitter-tweet" data-conversation="none"><a href="'+$(this).val().trim()+'"></a></blockquote>');
                window.renderTwitterItem();
            }).change();
        },

        _renderInstagramItem: function() {
            $('#url-instagram').change(function(){
                console.log($(this).val());
                $('#url-instagram-render').html('<blockquote class="instagram-media" data-conversation="none"><a href="' + $(this).val().trim() + '"></a></blockquote>');
                window.renderInstagramItem();
            }).change();
        },

        _renderYoutubeItem: function() {
            $('#url-google-youtube').change(function(){
                console.log($(this).val());
                $('#url-google-youtube-render iframe').attr('src', 'https://www.youtube.com/embed/' + $(this).val().match(/[\w-]+$/)[0]);
            }).change();
        },

        _renderGooglePlusItem: function() {
            $('#url-google-plus').change(function(){
                console.log($(this).val());
                $('#url-google-plus-render').html('<div class="g-post" data-href="' + $(this).val().trim() + '"></div>');
            }).change();
        },

        //
        init: function () {
            self._renderFacebookItem();
            self._renderTwitterItem();
            self._renderInstagramItem();
            self._renderYoutubeItem();
            self._renderGooglePlusItem();
            $('.x-form-social-auto').change(function(){
                $(this).closest('form').find('input[type=text]').prop('readonly', $(this).is(':checked'));
            }).change();
        }
    };
    return self;
}();

$(function () {
    "use strict";
    userActions.init();
});

