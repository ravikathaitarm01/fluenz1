var demoButtons = function () {
    return {
        init: function () {
            $(".loading-demo").on("click", function () {
                var btn = $(this);
                btn.button("loading");
                setTimeout(function () {
                    btn.button("reset");
                }, 3000);
            });
        }
    };
}();

$(function () {
    "use strict";
    demoButtons.init();
});