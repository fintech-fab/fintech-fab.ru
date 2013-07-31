function checkTime(i)
{
	if (i<10) {
		i="0" + i;
	}
	return i;
}

function getDateToPayUntil(n)
{
	var dayarray=new Array("воскресенья","понедельника","вторника","среды","четверга","пятницы","субботы");
	var montharray=new Array ("января","февраля","марта","апреля","мая","июня","июля","августа","сентября", "октября","ноября","декабря");

	d = new Date();
	d.setTime(d.getTime() + n * 24 * 60 * 60 * 1000);

	return dayarray[d.getDay()]+", "+d.getDate()+" "+montharray[d.getMonth()]+" "+checkTime(d.getFullYear());
}

$(document).ready(function()
{
	$('#ClientSelectProductForm .radio').click(function(){
		$('.conditions').show();
		$('.picconditions').show();
		$('.price_count').html($(this).find("label > span").attr('data-price'));
		$('.price_month').html($(this).find("label > span").attr('data-price-count'));
		$('.count_subscribe').html($(this).find("label > span").attr('data-count'));
		n = $(this).find("label > span").attr('data-time');
		$('.date').html(getDateToPayUntil(n));
		$('.final_price').html($(this).find("label > span").attr('data-final-price'));
	});

	if($('.conditions').is(":visible"))
	{
		n = $('.date').attr('data-time');
		$('.date').html(getDateToPayUntil(n));
	}
});
