<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

$this->pageTitle = Yii::app()->name;


$aCrumbs = array(
	array('Выбор пакета', 1),
	array('Знакомство', 2),
	array('Заявка на займ', 5, 3)
);

$this->widget('CheckBrowserWidget');


$this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs)); ?>

<?php

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	//'enableClientValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
		'afterValidate'    => 'js: function(){
			var hasError = false;
			if($("#personalData").find("div").hasClass("error"))
			{
				if(!$("#personalData").hasClass("in")){
					$("#personalData").collapse("show");
				}
				hasError = true;
			}
			else if($("#passportData").find("div").hasClass("error"))
			{
				personalDataOk = true;
				hasError = true;
				$("#passportDataHeading").attr("href","#passportData");
				if(!$("#passportData").hasClass("in")){
					$("#passportData").collapse("show");
				}
			}
			else if($("#address").find("div").hasClass("error"))
			{
				personalDataOk = true;
				passportDataOk = true;
				hasError = true;
				$("#passportDataHeading").attr("href","#passportData");
				$("#addressHeading").attr("href","#address");
				if(!$("#address").hasClass("in")){
					$("#address").collapse("show");
				}

			}
			else if($("#jobInfo").find("div").hasClass("error"))
			{
				personalDataOk = true;
				passportDataOk = true;
				addressOk = true;
				hasError = true;
				$("#passportDataHeading").attr("href","#passportData");
				$("#addressHeading").attr("href","#address");
				$("#jobInfoHeading").attr("href","#jobInfo");
				if(!$("#jobInfo").hasClass("in")){
					$("#jobInfo").collapse("show");
				}
			}
			else if($("#sendForm").find("div").hasClass("error"))
			{
			    personalDataOk = true;
				passportDataOk = true;
				addressOk = true;
				jobInfoOk = true;
				hasError = true;
				$("#passportDataHeading").attr("href","#passportData");
				$("#addressHeading").attr("href","#address");
				$("#jobInfoHeading").attr("href","#jobInfo");
				$("#sendFormHeading").attr("href","#sendForm");
				if(!$("#sendForm").hasClass("in")){
					$("#sendForm").collapse("show");
				}
			}

			if(hasError){
				$("#submitError").fadeIn(400).delay(4000).fadeOut( 800 );
			}

			return true;
		}'
	),
	'action'               => Yii::app()->createUrl('/form/'),
));

?>


<?php $collapse = $this->beginWidget('bootstrap.widgets.TbCollapse', array(
	'id'          => 'accordion',
	//'toggle'      => false,
	'htmlOptions' => array(
		'class' => 'accordion',
	),
));?>

	<div class="accordion-group">
		<div class="accordion-heading">
			<h4 class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#personalData">
				Личные данные</h4>
		</div>
		<div id="personalData" class="accordion-body collapse in">
			<div class="accordion-inner">
				<div class="row">
					<? require dirname(__FILE__) . '/fields2/personal_data.php' ?>
				</div>
			</div>
		</div>
	</div>
	<div class="accordion-group">
		<div class="accordion-heading row">
			<h4 id="passportDataHeading" class="accordion-toggle span3" data-toggle="collapse" data-parent="#accordion1">
				Паспортные данные</h4>
			<?php $this->widget('AlertWidget', array(
				'message'     => 'Ошибка! Необходимо сначала заполнить все обязательные поля анкеты.',
				'htmlOptions' => array('class' => 'errorAlert hide span7')
			));?>
		</div>
		<div id="passportData" class="accordion-body collapse">
			<div class="accordion-inner">
				<div class="row">
					<? require dirname(__FILE__) . '/fields2/passport_data.php' ?>
				</div>
			</div>
		</div>
	</div>
	<div class="accordion-group">
		<div class="accordion-heading row">
			<h4 id="addressHeading" class="accordion-toggle span3" data-toggle="collapse" data-parent="#accordion1">
				Постоянная регистрация</h4>
			<?php $this->widget('AlertWidget', array(
				'message'     => 'Ошибка! Необходимо сначала заполнить все обязательные поля анкеты.',
				'htmlOptions' => array('class' => 'errorAlert hide span7')
			));?>
		</div>
		<div id="address" class="accordion-body collapse">
			<div class="accordion-inner">
				<div class="row">
					<? require dirname(__FILE__) . '/fields2/address_reg.php' ?>
				</div>
			</div>
		</div>
	</div>
	<div class="accordion-group">
		<div class="accordion-heading row">
			<h4 id="jobInfoHeading" class="accordion-toggle span3" data-toggle="collapse" data-parent="#accordion1">
				Место работы</h4>
			<?php $this->widget('AlertWidget', array(
				'message'     => 'Ошибка! Необходимо сначала заполнить все обязательные поля анкеты.',
				'htmlOptions' => array('class' => 'errorAlert hide span7')
			));?>
		</div>
		<div id="jobInfo" class="accordion-body collapse">
			<div class="accordion-inner">
				<div class="row">
					<? require dirname(__FILE__) . '/fields2/job_info.php' ?>
				</div>
			</div>
		</div>
	</div>
	<div class="accordion-group">
		<div class="accordion-heading row">
			<h4 id="sendHeading" class="accordion-toggle span3" data-toggle="collapse" data-parent="#accordion1">
				Отправка</h4>
			<?php $this->widget('AlertWidget', array(
				'message'     => 'Ошибка! Необходимо сначала заполнить все обязательные поля анкеты.',
				'htmlOptions' => array('class' => 'errorAlert hide span7')
			));?>
		</div>
		<div id="sendForm" class="accordion-body collapse">
			<div class="accordion-inner">
				<div class="row">
					<? require dirname(__FILE__) . '/fields2/send.php' ?>
				</div>
			</div>
		</div>
	</div>
<?php $this->endWidget(); ?>


	<div class="clearfix"></div>
	<div class="row span10">
		<div class="form-actions">
			<div class="row">
				<?php $this->widget('AlertWidget', array(
					'message'     => 'Для отправки анкеты необходимо заполнить все обязательные поля!',
					'htmlOptions' => array('id' => 'submitError', 'class' => 'hide')
				));?>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<? $this->widget('bootstrap.widgets.TbButton', array(
					'buttonType' => 'submit',
					'type'       => 'primary',
					'label'      => 'Отправить →',
				)); ?>
			</div>
		</div>
	</div>

<?php $this->endWidget('application.components.utils.IkTbActiveForm'); ?>

<?php

//TODO разобраться почему нельзя дизейблить инпуты, происходит стирание данных в форме
// TODO UPD: разобрался! вероятно, стоит вернуть дизейблы скрытых частей формы
/**
 * $("#passportData").find(":input").prop("disabled",true);
 * $("#address").find(":input").prop("disabled",true);
 * $("#jobInfo").find(":input").prop("disabled",true);
 * $("#sendForm").find(":input").prop("disabled",true);
 *
 * TODO сделать более правильно работающую цепочку вызовов, с обратным проходом
 *
 * if(!personalDataOk){
 * jQuery("#passportDataHeading").click();
 * }
 *
 */


Yii::app()->clientScript->registerScript('accordionActions', '
	function in_array(what, where) {
	    for(var i=0, length_array=where.length; i<length_array; i++)
            if(what == where[i])
                return true;
	    return false;
	}


	$(".errorAlert").find(".alert-block").addClass("alert-block-fullform");

	if(!$("#regAsResCheckBox").prop("checked")){
		$("#address_res").find(":input").attr("disabled",false).removeClass("disabled").removeClass("success");
		$("#address_res").show();
		$("#address_res").find("label").append("<span class=\"required\">*</span>");
	} else {
		$("#address_res").find(":input").attr("disabled","disabled").addClass("disabled").val("");
		$("#address_res").hide();
		$("#address_res").find("label").append("<span class=\"required\">*</span>");
	}

	var personalDataOk = false;
	var passportDataOk = false;
	var addressOk = false;
	var jobInfoOk = false;
	var sendFormOk = false;

	var formName="' . get_class($oClientCreateForm) . '";

	/**
	* вешаем на чекбокс обработчик, чтобы по смене сразу валидировать (почему-то в TbActiveForm нет
	* валидации radioButtonListRow без подобных плясок с бубном)
	*/

	jQuery("#regAsResCheckBox").change(function()
	{
		if(!$("#regAsResCheckBox").prop("checked")){
			$("#address_res").find(":input").attr("disabled",false).removeClass("disabled").removeClass("success");
			$("#address_res").show();
		} else {
			$("#address_res").find(":input").attr("disabled","disabled").addClass("disabled").val("").parents(".control-group").removeClass("error success").find(".help-inline").hide();
			$("#address_res").hide();
		}

		var form=$("#"+formName);
		var settings = form.data("settings");
		var regExp = new RegExp("_reg_as_res");
		$.each(settings.attributes, function () {
			var sID = this.id;
	        if(sID.match(regExp)){
	            this.status = 2; // force ajax validation
	        }
	    });
	    form.data("settings", settings);

		// trigger ajax validation
	    $.fn.yiiactiveform.validate(form, function (data) {
	        $.each(settings.attributes, function () {
	            var sID = this.id;
				if(sID.match(regExp)){
	                $.fn.yiiactiveform.updateInput(this, data, form);
	                this.afterValidateAttribute();
	            }
	        });
	    });
	});
	/**
	* вешаем на радиобаттоны "Пол" обработчик, чтобы по смене сразу валидировать (почему-то в TbActiveForm нет
	* валидации radioButtonListRow без подобных плясок с бубном)
	*/
	jQuery("#sex").find(":input").change(function()
	{
		var form=$("#"+formName);
		var settings = form.data("settings");
		$.each(settings.attributes, function () {
	        if(this.id == formName+"_sex_0"||this.id == formName+"_sex_1"){
	            this.status = 2; // force ajax validation
	        }
	    });
	    form.data("settings", settings);

		// trigger ajax validation
	    $.fn.yiiactiveform.validate(form, function (data) {
	        $.each(settings.attributes, function () {
				if(this.id==formName+"_sex"){
	                $.fn.yiiactiveform.updateInput(this, data, form);
	                this.afterValidateAttribute();
	            }
	        });
	    });
	});

	jQuery("#product").find(":input").change(function()
	{
		var form=$("#"+formName);
		var settings = form.data("settings");
		var regExp = new RegExp("^"+formName+"_product");
		$.each(settings.attributes, function () {
			var sID = this.id;
	        if(sID.match(regExp)){
	            this.status = 2; // force ajax validation
	        }
	    });
	    form.data("settings", settings);

		// trigger ajax validation
	    $.fn.yiiactiveform.validate(form, function (data) {
	        $.each(settings.attributes, function () {
	            var sID = this.id;
				if(sID.match(regExp)){
	                $.fn.yiiactiveform.updateInput(this, data, form);
	                this.afterValidateAttribute();
	            }
	        });
	    });
	});

	jQuery("#have_past_credit").find(":input").change(function()
	{
		var form=$("#"+formName);
		var settings = form.data("settings");
		var regExp = new RegExp("^"+formName+"_have_past_credit");
		$.each(settings.attributes, function () {
			var sID = this.id;
	        if(sID.match(regExp)){
	            this.status = 2; // force ajax validation
	        }
	    });
	    form.data("settings", settings);

		// trigger ajax validation
	    $.fn.yiiactiveform.validate(form, function (data) {
	        $.each(settings.attributes, function () {
	            var sID = this.id;
				if(sID.match(regExp)){
	                $.fn.yiiactiveform.updateInput(this, data, form);
	                this.afterValidateAttribute();
	            }
	        });
	    });

	});
	//по получению фокуса чекбоксами (через клавишу TAB например)
	//сразу ставим значение по-умолчанию
	jQuery("#sex").find(":input").focus(function()
	{
		var bChecked = false;
		a = $("#sex").find(":input");
		$.each(a, function(){
			if($(this).attr("checked")=="checked"){
				bChecked = true;
			}
		});
		if(!bChecked){
			jQuery("#' . get_class($oClientCreateForm) . '_sex_0").attr("checked",true).change();
		}
	});

	jQuery("#product").find(":input").focus(function()
	{
		var bChecked = false;
		a = $("#product").find(":input");
		$.each(a, function(){
			if($(this).attr("checked")=="checked"){
				bChecked = true;
			}
		});

		if(!bChecked){
			jQuery("#product").find(":input").first().next().find(":input").attr("checked","checked").change();
		}
	});

	jQuery("#have_past_credit").find(":input").focus(function()
	{
		var bChecked = false;
		a = $("#have_past_credit").find(":input");
		$.each(a, function(){
			if($(this).attr("checked")=="checked"){
				bChecked = true;
			}
		});
		if(!bChecked){
			jQuery("#have_past_credit").find(":input").first().next().find(":input").attr("checked","checked").change();
		}
	});

	//по нажатии на "Паспортные данные" делаем force валидацию предыдущей части формы
	jQuery("#passportDataHeading").click(function()
	{

		if(!personalDataOk&&$("#personalData").find("div").hasClass("error")){
			$("#passportDataHeading").parent().find(".errorAlert").fadeIn(400).delay(4000).fadeOut( 800 );
		}

		if($("#passportDataHeading").attr("href")=="#passportData"){
			return;
		}

		var form=$("#"+formName);
        var settings = form.data("settings");

        var aAttrs = Array(
			formName+"_first_name",
			formName+"_last_name",
			formName+"_third_name",
			formName+"_birthday",
			formName+"_phone",
			formName+"_email",
			formName+"_sex",
			formName+"_sex_0",
			formName+"_sex_1"
		);

        $.each(settings.attributes, function () {
	        if(in_array(this.id,aAttrs)){
	            this.status = 2; // force ajax validation
	        }
	    });
	    form.data("settings", settings);

		// trigger ajax validation
	    $.fn.yiiactiveform.validate(form, function (data) {
	        $.each(settings.attributes, function () {
				if(in_array(this.id,aAttrs)){
	                $.fn.yiiactiveform.updateInput(this, data, form);
	                if(this.id!=formName+"_phone")
	                    this.afterValidateAttribute();
	            }
	        });
	    });
	});

	//по нажатии на "Постоянную регистрацию" делаем force валидацию предыдущей части формы
	jQuery("#addressHeading").click(function()
	{
		if(!passportDataOk&&$("#passportData").find("div").hasClass("error")){
		        $("#addressHeading").parent().find(".errorAlert").fadeIn(400).delay(4000).fadeOut( 800 );
		        $("#addressHeading").parent().find(".errorAlert").fadeIn(400).delay(4000).fadeOut( 800 );
		}

		if(!personalDataOk){
			jQuery("#passportDataHeading").click();
		}

	if(personalDataOk&&$("#addressHeading").attr("href")!="#address"){
		var form=$("#"+formName);
        var settings = form.data("settings");

        var aAttrs = Array(
			formName+"_passport_series",
			formName+"_passport_number",
			formName+"_passport_date",
			formName+"_passport_code",
			formName+"_passport_issued",
			formName+"_document",
			formName+"_document_number"
		);

        $.each(settings.attributes, function () {
	        if(in_array(this.id,aAttrs)){
	            this.status = 2; // force ajax validation
	        }
	    });
	    form.data("settings", settings);

		// trigger ajax validation
	    $.fn.yiiactiveform.validate(form, function (data) {
	        $.each(settings.attributes, function () {
				if(in_array(this.id,aAttrs)){
	                $.fn.yiiactiveform.updateInput(this, data, form);
                    this.afterValidateAttribute();
	            }
	        });
	    });
	}});

	//по нажатии на "Место работы" делаем force валидацию предыдущей части формы
	jQuery("#jobInfoHeading").click(function()
	{
	if(!addressOk&&$("#address").find("div").hasClass("error")){
	        $("#jobInfoHeading").parent().find(".errorAlert").fadeIn(400).delay(4000).fadeOut( 800 );
	}

	if(!passportDataOk){
		jQuery("#addressHeading").click();
	}
	if(personalDataOk&&passportDataOk&&$("#jobInfoHeading").attr("href")!="#jobInfo"){
		var form=$("#"+formName);
        var settings = form.data("settings");

        var aAttrs = Array(
			formName+"_address_reg_region",
			formName+"_address_reg_city",
			formName+"_address_reg_address",
			formName+"_address_res_region",
			formName+"_address_res_city",
			formName+"_address_res_address",
			formName+"_relatives_one_fio",
			formName+"_relatives_one_phone",
			formName+"_friends_fio",
			formName+"_friends_phone"
		);

        $.each(settings.attributes, function () {
	        if(in_array(this.id,aAttrs)){
	            this.status = 2; // force ajax validation
	        }
	    });
	    form.data("settings", settings);

		// trigger ajax validation
	    $.fn.yiiactiveform.validate(form, function (data) {
	        $.each(settings.attributes, function () {
				if(in_array(this.id,aAttrs)){
	                $.fn.yiiactiveform.updateInput(this, data, form);
                    this.afterValidateAttribute();
	            }
	        });
	    });
	}});

	//по нажатии на "Отправка" делаем force валидацию предыдущей части формы
	jQuery("#sendHeading").click(function()
	{

	if(!jobInfoOk&&$("#jobInfo").find("div").hasClass("error")){
	        $("#sendHeading").parent().find(".errorAlert").fadeIn(400).delay(4000).fadeOut( 800 );
	}

	if(!addressOk){
		jQuery("#jobInfoHeading").click();
	}

	if(personalDataOk&&passportDataOk&&addressOk&&$("#sendHeading").attr("href")!="#sendForm"){
		var form=$("#"+formName);
        var settings = form.data("settings");

        var aAttrs = Array(
			formName+"_job_company",
			formName+"_job_position",
			formName+"_job_phone",
			formName+"_job_time",
			formName+"_job_monthly_income",
			formName+"_job_monthly_outcome",
			formName+"_have_past_credit"		);

        $.each(settings.attributes, function () {
	        if(in_array(this.id,aAttrs)){
	            this.status = 2; // force ajax validation
	        }
	    });
	    form.data("settings", settings);

		// trigger ajax validation
	    $.fn.yiiactiveform.validate(form, function (data) {
	        $.each(settings.attributes, function () {
				if(in_array(this.id,aAttrs)){
	                $.fn.yiiactiveform.updateInput(this, data, form);
                    this.afterValidateAttribute();
	            }
	        });
	    });

	}});


');
?>