function checkTime(i) {
	if (i < 10) {
		i = "0" + i;
	}
	return i;
}

function getDateToPayUntil(n) {
	var dayarray = new Array("воскресенья", "понедельника", "вторника", "среды", "четверга", "пятницы", "субботы");
	var montharray = new Array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");

	var d = new Date();
	d.setTime(d.getTime() + n * 24 * 60 * 60 * 1000);

	return dayarray[d.getDay()] + ", " + d.getDate() + " " + montharray[d.getMonth()] + " " + checkTime(d.getFullYear());
}

function showConditions(products, channels) {
	var final_price = parseInt(products.attr('data-price'));
	var card = parseInt(channels.find('input:checked').parent().find("label > span").attr('data-card'));
	if (card == 1) {
		final_price += parseInt(products.attr('data-card'));
	}


	$('.price_count').html(final_price);
	$('.price_month').html(products.attr('data-price-count'));
	var packetSize = parseInt(products.attr('data-int-count')) * parseInt(products.attr('data-final-price'));
	$('.packet_size').html(packetSize);
	$('.count_subscribe').html(products.attr('data-count'));
	var n = products.attr('data-time');
	$('.date').html(getDateToPayUntil(n));
	$('.final_price').html(products.attr('data-final-price'));
	$('.channel').html(channels.find('input:checked').parent().find("label > span").html());
}
