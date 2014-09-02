jQuery.noConflict();
jQuery(document).ready(function($) {
	
	$.gsap.enabled(true);	/*ускорение анимации заменой на css3 и html5 способы*/
	
	$('#fancyClock').tzineClock();
	
	$('.carousel').carousel();	/*подключение бутстраповской карусели*/
	
});
