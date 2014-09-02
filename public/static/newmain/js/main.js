jQuery.noConflict();
jQuery(document).ready(function($) {
	
	$.gsap.enabled(true);	/*ускорение анимации заменой на css3 и html5 способы*/
	
	//$('#fancyClock').tzineClock();
	
	$('.carousel').carousel({pause: false, interval: 3500});	/*подключение бутстраповской карусели*/
	
	$('.askbu_left').click(function(){
		TweenMax.to(".ask_upbuttons", 0, {background: "url(images/askbl_lact.png) 50% 0% no-repeat"});
		TweenMax.to(".not_socask", 0, {display: "none"});
		TweenMax.to(".soc_ask", 0, {display: "block"});
		
	});
	
	$('.askbu_right').click(function(){
		TweenMax.to(".ask_upbuttons", 0, {background: "url(images/askbl_ract.png) 50% 0% no-repeat"});
		TweenMax.to(".not_socask", 0, {display: "block"});
		TweenMax.to(".soc_ask", 0, {display: "none"});
	});
	
	$('.not_link').click(function(e){
		e.preventDefault();
		TweenMax.to(".first_inprow", 0, {display: "none"});
		TweenMax.to(".second_inprow", 0, {display: "block"});
		$('.slider_submit').removeClass('not_link');
	});
	
	$('.ask_si1').click(function(e){
		e.preventDefault();
		var vis_blok = $('.savi:visible');
		$('.ask_sibl').removeClass('act');
		$(this).addClass('act');
		TweenMax.to(vis_blok, 0, {display: "none"});
		TweenMax.to(".savi_vk", 0, {display: "block"});
	});
	$('.ask_si2').click(function(e){
		e.preventDefault();
		var vis_blok = $('.savi:visible');
		$('.ask_sibl').removeClass('act');
		$(this).addClass('act');
		TweenMax.to(vis_blok, 0, {display: "none"});
		TweenMax.to(".savi_f", 0, {display: "block"});
	});
	$('.ask_si3').click(function(e){
		e.preventDefault();
		var vis_blok = $('.savi:visible');
		$('.ask_sibl').removeClass('act');
		$(this).addClass('act');
		TweenMax.to(vis_blok, 0, {display: "none"});
		TweenMax.to(".savi_od", 0, {display: "block"});
	});
	$('.ask_si4').click(function(e){
		e.preventDefault();
		var vis_blok = $('.savi:visible');
		$('.ask_sibl').removeClass('act');
		$(this).addClass('act');
		TweenMax.to(vis_blok, 0, {display: "none"});
		TweenMax.to(".savi_inst", 0, {display: "block"});
	});
	
	

});
