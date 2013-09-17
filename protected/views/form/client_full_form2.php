<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

$this->pageTitle = Yii::app()->name;


$aCrumbs = array(
	array('Выбор пакета', 1),
	array('Заявка на займ', 2),
	array('Подтверждение номера телефона', 3)
);

$this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs)); ?>

<?php
// если форма уже была заполнена, регистрируем js-переменную
if (Yii::app()->clientForm->getFlagFullFormFilled()) {

	Yii::app()->clientScript->registerScript('scriptFlagFullFormFilled', '
		bFlagFullFormFilled = true;

		// разблокировали кнопку Отправить
		$("#submitButton").removeClass("disabled").attr("disabled",false);
	', CClientScript::POS_READY);
}

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
		'beforeValidate'   => 'js: function(){
			if(personalDataOk
			&&passportDataOk
			&&addressOk
			&&jobInfoOk
			|| (bFlagFullFormFilled === true)
			){
				// разблокировали все поля, чтобы они провалидировались
				$("#jobInfo").find(":input").attr("disabled",false);
				$("#passportData").find(":input").attr("disabled",false);
				$("#address").find(":input").attr("disabled",false);
				$("#sendForm").find(":input").attr("disabled",false);

				return true;
			} else {
				alertModal("Ошибка!","Сначала следует заполнить все поля формы и подтвердить, что Вы согласны с условиями передачи и обработки персональных данных", "OK");
			}

		}',
		'afterValidate'    => 'js: function(){
			var hasError = false;
			if($("#personalData").find("div").hasClass("error")) {
				hasError = true;
				if(!$("#personalData").hasClass("in")){
					$("#personalData").collapse("show");
				}
			}
			if($("#passportData").find("div").hasClass("error")) {
				personalDataOk = true;
				hasError = true;
				$("#passportDataHeading").attr("href","#passportData");
				if(!$("#passportData").hasClass("in")){
					$("#passportData").collapse("show");
				}
			}
			if($("#address").find("div").hasClass("error"))	{
				personalDataOk = true;
				passportDataOk = true;
				hasError = true;
				$("#passportDataHeading").attr("href","#passportData");
				$("#addressHeading").attr("href","#address");
				if(!$("#address").hasClass("in")){
					$("#address").collapse("show");
				}
			}
			if($("#jobInfo").find("div").hasClass("error"))	{
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
			if($("#sendForm").find("div").hasClass("error")) {
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
			<h4 id="personalDataHeading" class="accordion-toggle" data-toggle="collapse" href="#personalData">
				Личные данные</h4>
		</div>
		<div id="personalData" class="accordion-body collapse in">
			<div class="accordion-inner">
				<div class="row">
					<?php require dirname(__FILE__) . '/fields2/personal_data.php' ?>
				</div>
			</div>
		</div>
	</div>
	<div class="accordion-group">
		<div class="accordion-heading row">
			<h4 id="passportDataHeading" class="accordion-toggle span3" data-toggle="collapse">
				Паспортные данные</h4>
		</div>
		<div id="passportData" class="accordion-body collapse">
			<div class="accordion-inner">
				<div class="row">
					<?php require dirname(__FILE__) . '/fields2/passport_data.php' ?>
				</div>
			</div>
		</div>
	</div>
	<div class="accordion-group">
		<div class="accordion-heading row">
			<h4 id="addressHeading" class="accordion-toggle span3 disabled cursor-default" data-toggle="collapse">
				Постоянная регистрация</h4>
		</div>
		<div id="address" class="accordion-body collapse">
			<div class="accordion-inner">
				<div class="row">
					<?php require dirname(__FILE__) . '/fields2/address_reg.php' ?>
				</div>
			</div>
		</div>
	</div>
	<div class="accordion-group">
		<div class="accordion-heading row">
			<h4 id="jobInfoHeading" class="accordion-toggle span3 disabled cursor-default" data-toggle="collapse">
				Место работы</h4>
		</div>
		<div id="jobInfo" class="accordion-body collapse">
			<div class="accordion-inner">
				<div class="row">
					<?php require dirname(__FILE__) . '/fields2/job_info.php' ?>
				</div>
			</div>
		</div>
	</div>
	<div class="accordion-group">
		<div class="accordion-heading row">
			<h4 id="sendHeading" class="accordion-toggle span3 disabled cursor-default" data-toggle="collapse">
				Отправка</h4>
		</div>
		<div id="sendForm" class="accordion-body collapse">
			<div class="accordion-inner">
				<div class="row">
					<?php require dirname(__FILE__) . '/fields2/send.php' ?>
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
				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'id'          => 'submitButton',
					'buttonType'  => 'submit',
					'type'        => 'primary',
					'label'       => 'Отправить →',
					'htmlOptions' => array(
						'class' => 'disabled'
					)
				)); ?>
			</div>
		</div>
	</div>

<?php $this->endWidget('application.components.utils.IkTbActiveForm'); ?>

<?php

Yii::app()->clientScript->registerScript('accordionActions', '

	$(".errorAlert").find(".alert-block").addClass("alert-block-fullform");

	if(!$("#reg_as_res").find("input[type=checkbox]").prop("checked")){
		$("#address_res").find(":input").attr("disabled",false).removeClass("disabled");
		$("#address_res").show();
		$("#address_res").find("label").append("<span class=\"required\">*</span>");
	} else {
		$("#address_res").find(":input").attr("disabled","disabled").addClass("disabled").parents(".control-group").addClass("success").val("");
		$("#address_res").hide();
		$("#address_res").find("label").append("<span class=\"required\">*</span>");
	}


	$("#passportData").find(":input").prop("disabled",true);
    $("#address").find(":input").prop("disabled",true);
    $("#jobInfo").find(":input").prop("disabled",true);
    $("#sendForm").find(":input").prop("disabled",true);

	var personalDataOk = false;
	var passportDataOk = false;
	var addressOk = false;
	var jobInfoOk = false;
	var sendFormOk = false;

	var formName="' . get_class($oClientCreateForm) . '";

	/**
	* вешаем на чекбокс обработчик, чтобы по смене сразу валидировать и менять состояние формы
	*/

	jQuery("#reg_as_res").find("input[type=checkbox]").change(function()
	{
		/*
		 * Проверяем, установлен или снят чекбокс, и либо убираем и дизейблим, либо наоборот соответствующие части формы
		 * Обязательно убираем класс error/success и очищаем поля при этом
		 */
		if(!$("#reg_as_res").find("input[type=checkbox]").prop("checked")){
			$("#address_res").find(":input").attr("disabled",false).removeClass("disabled").parents(".control-group").removeClass("error success");
			$("#address_res").show();
		} else {
			$("#address_res").find(":input").attr("disabled","disabled").addClass("disabled").val("").parents(".control-group").removeClass("error").addClass("success").find(".help-inline").hide();
			$("#address_res").hide();
		}


	});

	/**
	* вешаем на радиобаттоны "Пол" обработчик, чтобы по смене сразу валидировать (почему-то в TbActiveForm нет
	* валидации radioButtonListRow без подобных плясок с бубном)
	*/
	jQuery("#sex").find(":input").change(function()
	{
		var form=$("#"+formName);
		var settings = form.data("settings");
		var regExp = new RegExp(formName+"_sex");
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

	//по нажатии на "Паспортные данные" делаем force валидацию предыдущей части формы
	jQuery("#passportDataHeading").click(function()
	{
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
			formName+"_complete",
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

$this->widget('YaMetrikaGoalsWidget', array(
	'iDoneSteps'    => Yii::app()->clientForm->getCurrentStep(),
	'iSkippedSteps' => 2,
));
