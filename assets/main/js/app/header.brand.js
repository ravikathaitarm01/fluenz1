/**
 * Created by nisheeth on 20/3/15.
 */

$(function(){
	$('.x-select-brand').removeClass('open').find('div').click(function (e) {
		e.stopPropagation();
	});
	$('.x-select-brand select').change(function(e) {
		window.location = $(this).data('url') + '/' + $(this).val();
	});
});