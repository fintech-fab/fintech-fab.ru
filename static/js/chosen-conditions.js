function checkTime(i)
{
	if (i<10) {
		i="0" + i;
	}
	return i;
}


function yaSignal(id){
	console.log(id);
	try{
		yaCounter21390544.reachGoal('done_step_'+id);
	}catch(e){}
}

$(document).ready(function(){

	var d = new Date();
	var dayarray=new Array("воскресенья","понедельника","вторника","среды","четверга","пятницы","субботы") 
	var montharray=new Array ("января","февраля","марта","апреля","мая","июня","июля","августа","сентября", "октября","ноября","декабря")

	$('.all').click(function(){
		$('.price_count').html($(this).find("span").attr('data-price'));
		$('.price_month').html($(this).attr('data-price-count'));
		$('.count_subscribe').html($(this).attr('data-count'));
		n = $(this).attr('data-time');
		d = new Date();
		d.setTime(d.getTime() + n * 24 * 60 * 60 * 1000);
		$('.date').html(dayarray[d.getDay()]+", "+d.getDate()+" "+montharray[d.getMonth()]+" "+checkTime(d.getFullYear()));
		$('.final_price').html($(this).attr('data-final-price'));
	});
	
	d.getDate();
	var current_month = d.getMonth();
	var current_year = d.getFullYear();
        
        // New Year
        // $.fn.snow({ minSize: 5, maxSize: 25, newOn: 500, flakeColor: '#0099FF' });
        
        // Google Recaptcha
        /*Recaptcha.create("6Lez_9sSAAAAAICY3dz8OVYaARwJyZP4q9GphMtm",
          "recaptcha_element",
          {
            theme: "red",
            callback: Recaptcha.focus_response_field
          }
        );*/
	
});
