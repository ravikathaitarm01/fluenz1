var networkActions = function () {
	var self = {
		_fetchNewer: function($scroll, $container) {
			if ($scroll.find('.mCSB_container').css('top') == '0px') {
				var $firstItem = $scroll.find('.post-comments > div.media').eq(0);
				if ( ! $firstItem) {
					return null;
				}
				var lastId = $firstItem.data('id').split('-')[1];
				$.post($('#x-data-network-url').data('networkItemTwitterAction'), {action: 'post', page: 0, last_id: lastId}, function(d) {
					d = $.parseJSON(d);
					if (d['success']) {
						$container.prepend(d['body']);
					} else {
						console.log(d['error']);
						toastr['error'](d['error']);
					}
				}).complete(function() {
					setTimeout(function() {
						self._fetchNewer($scroll, $container);
					}, 5000);
				});
			} else {
				setTimeout(function() {
					self._fetchNewer($scroll, $container);
				}, 5000);
			}
		},

		_fetchTwitterMention: function() {
			var page = 0;
			var $scroll = $('#x-section-twitter-mentions');
			var $spinner = $scroll.parent().find('.spinner:last');
			var $container = $scroll.find('section');
			function fetchPage() {
				$spinner.show();
				$.post($('#x-data-network-url').data('networkItemTwitterAction'), {action: 'mention', page: page}, function(d) {
					page += 1;
					d = $.parseJSON(d);
					console.log(d);
					if (d['success']) {
						$container.append(d['body']);
					} else {
						console.log(d['error']);
						toastr['error'](d['error']);
					}
				})
					.complete(function() {
						$spinner.hide();
					});
			}

			self._fetchNewer($scroll, $container);

			$scroll.mCustomScrollbar({
				advances: {
					updateOnContentResize: true
				},
				callbacks:{
					onTotalScroll: function(){
						fetchPage();
					}
				}
			});

			fetchPage();
		},

		_fetchTwitterSearch: function() {
			var page = 0;
			var $scroll = $('#x-section-twitter-searches');
			var $spinner = $scroll.parent().find('.spinner:last');
			var $container = $scroll.find('section');
			function fetchPage() {
				$spinner.show();
				$.post($('#x-data-network-url').data('networkItemTwitterAction'), {action: 'search', page: page}, function(d) {
					page += 1;
					d = $.parseJSON(d);
					console.log(d);
					if (d['success']) {
						$container.append(d['body']);
					} else {
						console.log(d['error']);
						toastr['error'](d['error']);
					}
				})
					.complete(function() {
						$spinner.hide();
					});
			}

			self._fetchNewer($scroll, $container);

			$scroll.mCustomScrollbar({
				advances: {
					updateOnContentResize: true
				},
				callbacks:{
					onTotalScroll: function(){
						fetchPage();
					}
				}
			});

			fetchPage();
		},

		init: function () {
			self._fetchTwitterMention();
			self._fetchTwitterSearch();
		}
	};
	return self;
}();

$(function () {
	"use strict";
	networkActions.init();
});