(function($) {
	"use strict";

	//Fade in page
	$('html').hide(0);
	$('html').fadeIn(300);

	//Enable full backgrounds
	$('.full-bg').each(function(){
		var url = $(this).attr('data-bg');
		$(this).backstretch(url);
	});

	//The menu trigger
	$('#menu-trigger').click(function(){
		$('body').toggleClass('menu-hover');	
		$('#menu-trigger').toggleClass('fa-bars').toggleClass('fa-long-arrow-left');
	});

	//Anchor and Fade links
	$('a').click(function(e){
		if (this.getAttribute("href").charAt(0) !== "#") {
			e.preventDefault();
			var link = $(this).attr('href');
			$('html').fadeOut(500);
			setTimeout(function() {
			    goToLink(link);
			}, 500);
		} 
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
		        $('html,body').animate({
		          scrollTop: target.offset().top
		        }, 500);
		        console.log('ok');
	      	}
	    }
	    e.preventDefault();
	});

	//Go to target
	function goToLink(link){
		window.location = link;
	}

	//Make tabs availabla
	$('.nav-tabs a').click(function (e) {
	  e.preventDefault()
	  $(this).tab('show')
	})

})(jQuery);