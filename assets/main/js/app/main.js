/**
 * Created by nisheeth on 2/3/15.
 */

$(function(){
	var $msg = $('.x-alert-once');
	if ($msg.length) {
		toastr[$msg.data('type')]($msg.data('message'));
	}

	openSidebar();
	initTooltip();
});

function openSidebar() {
	$('nav .sub-menu a').each(function (i, e) {
		if ($(e).attr('href').indexOf(window.location.href) == 0) {
			var $e = $(e).parent().parent().parent().find('a:first');
			if ( ! $e.is(':visible')) {
				$e.parent().parent().parent().find('a:first').click();
			}
			$e.click();
			return false;
		}
	});
}

function initTooltip() {
	$("[data-toggle=tooltip]").tooltip();

	$("[data-toggle=popover]")
		.popover()
		.click(function (e) {
			e.preventDefault();
		});
}


function loadJS(file) {
	if ($('script[src="'+file+'"]').length) {
		return false;
	}
	var s = document.createElement('script');
	s.setAttribute('type','text/javascript');
	s.setAttribute('src', file);
	document.body.appendChild(s);
	return true;
}