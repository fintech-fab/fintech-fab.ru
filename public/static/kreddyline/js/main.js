$(document).ready(function () {
	///// ul add class
	$('ul li:first-child').addClass('firstItem');
	$('ul li:last-child').addClass('lastItem');

	///slider
	$('.bxslider').bxSlider({
		pagerCustom: '#bx-pager',
		mode: 'fade',
		nextText: 'далее',
		prevText: 'назад'
	});


	var InputClass = 'blured';
	var ClickedClass = 'clicked';

	$('.' + InputClass).focus(function () {
		if ($(this).attr('defvalue') == undefined)
			$(this).attr('defvalue', $(this).val());
		if (($(this).attr('blurvalue') == undefined) || ($(this).attr('blurvalue') == $(this).attr('defvalue')))
			$(this).val('').addClass(ClickedClass);
	}).blur(function () {
		var blurvalue = $(this).val();
		if (blurvalue == '')
			$(this)
				.removeAttr('blurvalue')
				.val($(this).attr('defvalue'))
				.removeClass(ClickedClass);
		else
			$(this).attr('blurvalue', blurvalue);
	});


});


$(function () {
	var offset = $("#fixed").offset();
	var topPadding = 15;
	$(window).scroll(function () {
		if ($(window).scrollTop() > offset.top) {
			$("#fixed").stop().animate({marginTop: $(window).scrollTop() - offset.top + topPadding});
		}
		else {
			$("#fixed").stop().animate({marginTop: 0});
		}
		;
	});
});

