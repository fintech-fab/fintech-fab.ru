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
	
	
	$('.card-only').click(function(){
		$('input[name=get]').each(function(){
			if($(this).attr('id')!='get1'){
				$(this).attr('disabled','').hide();
				$(this).parent().addClass('disabled').hide();
				$(this).parent().children('span').tooltip({placement:'right'});
			}else{
				$(this).removeAttr('disabled').show();
				$(this).parent().removeClass('disabled').show();
				$(this).attr('checked','');
			}
		});
	});

	$('.card-and-mobile-only').click(function(){
		$('input[name=get]').each(function(){
			if(
				$(this).attr('id')=='get1' ||
				$(this).attr('id')=='get2'
			){
				$(this).removeAttr('disabled').show();
				$(this).parent().removeClass('disabled').show();
				if($(this).attr('id')=='get1'){
					$(this).attr('checked','');
				}
			}else{
				$(this).attr('disabled','').hide();
				$(this).parent().addClass('disabled').hide();
				$(this).parent().children('span').tooltip({placement:'right'});
			}
		});
	});

	$('.all').click(function(){
		$('input[name=get]').each(function(){
			$(this).removeAttr('disabled');
			$(this).parent().removeClass('disabled');
			$(this).parent().children('span.limit').hide();
		});
	});
	$('.all, .card-only, .card-and-mobile-only').click(function(){
		$('.price_count').html($(this).attr('data-price'));
		$('.price_month').html($(this).attr('data-price-count'));
		$('.count_subscribe').html($(this).attr('data-count'));
		n = $(this).attr('data-time');
		d = new Date();
		d.setTime(d.getTime() + n * 24 * 60 * 60 * 1000);
		$('.date').html(dayarray[d.getDay()]+", "+d.getDate()+" "+montharray[d.getMonth()]+" "+checkTime(d.getFullYear()));
		$('.final_price').html($(this).attr('data-final-price'));
	});
	$('.radio:eq(0)').click();
	
	
	$('#proceed').click(function(){
		$('#form').show('slow');
		return false;
	});
	
	var step = '#step1';
	
	$('#steps li a').click(function(){
		if($(this).parent().hasClass('done')){
			step = $(this).attr('href');
			$('.steps div.step').hide();
			$(step).show();
			$('.steps div').removeClass('current');
			$(step).addClass('current');
			if(step == '#step6'){
				$('#next').hide();
				$('#proceed').show();
			}else{
				$('#next').show();
				$('#proceed').hide();
			}
			if(step=='#step1') yaSignal(1);
			if(step=='#step2') yaSignal(2);
			if(step=='#step3') yaSignal(3);
			if(step=='#step4') yaSignal(4);
			if(step=='#step5') yaSignal(5);
			if(step=='#step6') yaSignal(6);
		}
		return false;
	});
	
	$.validator.setDefaults({ 
    	highlight: function (element, errorClass, validClass) { 
    	    $(element).parents('div.control-group').addClass(errorClass).removeClass(validClass); 
    	}, 
    	unhighlight: function (element, errorClass, validClass) { 
    	   	$(element).parents(".error").removeClass(errorClass).addClass(validClass); 
    	},
		errorPlacement: function(err, element) {
			element.addClass('help-inline');
			element.attr('data-title',err.text());
			element.tooltip({placement:'right'});
		} 
    });
	
	$('#next').click(function(){

		var error=0;
		$('.current .alert').remove();
		$('.current .required').each(function(){
			if(!$('#form').validate().element(this)){
				error++;
			}
		});
		if(error==0){
                        $('.sendform_error').remove();
                        $.post('/send.php?type=form',$('#form').serialize(),function(errors){
                          if(errors) {
                            $('#next').parent().after('<div class="alert sendform_error span11 offset0">'+errors+'</div>');
                          } else {
                            $('#steps li a[href='+step+']').parent().next().addClass('done').children('a').click();
                          }
                        });
		}else{
			$('.current .span6').prepend('<div class="alert alert-error">Необходимо заполнить все поля!</div>');
		}
		return false;
	});
	
	
	$('#proceed').click(function(){
		yaSignal('7');
		$('input[type=checkbox]').parents(".error").removeClass('error');
		$('.current .alert').remove();
		var error=0;
		$('.current .required').each(function(){
			if(!$('#form').validate().element(this)){
				error++;
			}
		});
		$('input[type=checkbox]').not(':checked').each(function(){
    	    $(this).parents('div.control-group').addClass('error');
			error++;
		});
		if(error==0){
                        $('.sendform_error').remove();
                        $.post('/send.php?type=form&finish=1',$('#form').serialize(),function(errors){
                          if(errors) {
                            $('#proceed').parent().after('<div class="alert sendform_error span11 offset0">'+errors+'</div>');
                            Recaptcha.reload();
                          } else {
                            $('#proceed').parent().after('<div class="alert sendform_success span11 offset0">Ваша заявка отправлена! Наши менеджеры скоро свяжутся с Вами!</div>');
                            $('#proceed').hide();
                          }
                        });
		}else{
			$('.current .span6').prepend('<div class="alert alert-error">Необходимо заполнить все поля!</div>');
		}
		return false;
	});
        
	$('#acqbutton').click(function(){
		$('input[type=checkbox]').click(function(){
                  $('input[type=checkbox]').parents(".error").removeClass('error'); 
                });
		$('.current .alert').remove();
                $('.alert-error').remove();
		var error=0;
		$('.required').each(function(){
			if(!$('#form').validate().element(this)){
				error++;
			}
		});
		$('input[type=checkbox]').not(':checked').each(function(){
    	    $(this).parents('div.control-group').addClass('error');
			error++;
		});
		if(error==0){
                        return true;
		}else{
			$('.toalert').prepend('<div class="alert alert-error">Необходимо заполнить все поля!</div>');
		}
		return false;
	});
        
        $('.select-kreddy-product-price').attr('value', $('#select-kreddy-product option:selected').attr('value'));
        $('select#select-kreddy-product').change(function(){
          if ($(this).attr('value')){
            if ( ! $('.select-kreddy-product-price').attr('readonly') ){
              $('.select-kreddy-product-price').attr('readonly', 'readonly');
            }
            $('.select-kreddy-product-price').attr('value', $(this).attr('value'));
          } else {
            $('.select-kreddy-product-price').removeAttr('readonly');
            $('.select-kreddy-product-price').attr('value', '');
            $('.select-kreddy-product-price').focus();
          }
        });
	
	$('#steps li.done a:eq(0)').click();
	
	var img = 0;
	
	$('a[href=#third]').click(function(){
		if(img == 0){
			$('#third img:eq(0)').show();
			$('#third img:eq(1)').hide();
			img = 1;
		}else{
			$('#third img:eq(1)').show();
			$('#third img:eq(0)').hide();
			img = 0;
		}
	});
	
	$('#case').click(function(){
		$('a[href=#second]').click();
		return false;
	});
	
	$('.priv').click(function(){
		$('#privacy').toggle();
		return false;
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