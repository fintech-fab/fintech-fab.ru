<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Цифровой код
 * Согласие с условиями и передачей данных
 */

$this->pageTitle = Yii::app()->name;

?>

<?php $this->widget('CheckBrowserWidget'); ?>

<?php $this->widget('StepsBreadCrumbsWidget'); ?>

<?php

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
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
			<h4 style="font-weight: 400;" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#personalData">
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
		<div class="accordion-heading">
			<h4 id="passportDataHeading" style="font-weight: 400;" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1">
				Паспортные данные</h4>
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
		<div class="accordion-heading">
			<h4 id="addressHeading" style="font-weight: 400;" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1">
				Постоянная регистрация</h4>
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
		<div class="accordion-heading">
			<h4 id="jobInfoHeading" style="font-weight: 400;" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1">
				Место работы</h4>
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
		<div class="accordion-heading">
			<h4 id="sendHeading" style="font-weight: 400;" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1">
				Отправка</h4>
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
	<div class="row span11">
		<div class="form-actions">
			<? $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'      => 'Отправить →',
			)); ?>
		</div>
	</div>

<?php $this->endWidget('application.components.utils.IkTbActiveForm'); ?>

<?php
Yii::app()->clientScript->registerScript('checkData', '
	function in_array(what, where) {
	    for(var i=0, length_array=where.length; i<length_array; i++)
            if(what == where[i])
                return true;
	    return false;
	}

	var personalDataOk = false;
	var passportDataOk = false;
	var addressOk = false;
	var jobInfoOk = false;

	var formName="' . get_class($oClientCreateForm) . '";

	$("#passportData").find(":input").prop("disabled",true);
	$("#address").find(":input").prop("disabled",true);
	$("#jobInfo").find(":input").prop("disabled",true);
	$("#sendForm").find(":input").prop("disabled",true);

	/**
	* вешаем на радиобаттоны "Пол" обработчик, чтобы по смене сразу валидировать (почему-то в TbActiveForm нет
	* валидации radioButtonListRow без подобных плясок с бубном)
	*/
	jQuery("#' . get_class($oClientCreateForm) . '_sex_0, #' . get_class($oClientCreateForm) . '_sex_1").change(function()
	{
		var form=$("#"+formName);
		var settings = form.data("settings");
		$.each(settings.attributes, function () {
	        if(this.id == formName+"_sex_1"){
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

	jQuery("#' . get_class($oClientCreateForm) . '_sex_0").focus(function()
	{
		jQuery("#' . get_class($oClientCreateForm) . '_sex_0").attr("checked",true).change();
	});

	jQuery("#' . get_class($oClientCreateForm) . '_sex_1").focus(function()
	{
		jQuery("#' . get_class($oClientCreateForm) . '_sex_1").attr("checked",true).change();
	});

	//по нажатии на "Паспортные данные" делаем force валидацию предыдущей части формы
	jQuery("#passportDataHeading").click(function()
	{
		var form=$("#"+formName);
        var settings = form.data("settings");

        var attrs = Array(
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
	        if(in_array(this.id,attrs)){
	            this.status = 2; // force ajax validation
	        }
	    });
	    form.data("settings", settings);

		// trigger ajax validation
	    $.fn.yiiactiveform.validate(form, function (data) {
	        $.each(settings.attributes, function () {
				if(in_array(this.id,attrs)){
	                $.fn.yiiactiveform.updateInput(this, data, form);
	                if(this.id!=formName+"_sex")
	                    this.afterValidateAttribute();
	            }
	        });
	    });
	});

	//по нажатии на "Постоянную регистрацию" делаем force валидацию предыдущей части формы
	jQuery("#addressHeading").click(function()
	{
	if(personalDataOk){
		var form=$("#"+formName);
        var settings = form.data("settings");

        var attrs = Array(
			formName+"_passport_series",
			formName+"_passport_number",
			formName+"_passport_date",
			formName+"_passport_code",
			formName+"_passport_issued",
			formName+"_document",
			formName+"_document_number"
		);

        $.each(settings.attributes, function () {
	        if(in_array(this.id,attrs)){
	            this.status = 2; // force ajax validation
	        }
	    });
	    form.data("settings", settings);

		// trigger ajax validation
	    $.fn.yiiactiveform.validate(form, function (data) {
	        $.each(settings.attributes, function () {
				if(in_array(this.id,attrs)){
	                $.fn.yiiactiveform.updateInput(this, data, form);
                    this.afterValidateAttribute();
	            }
	        });
	    });
	}});

	//по нажатии на "Место работы" делаем force валидацию предыдущей части формы
	jQuery("#jobInfoHeading").click(function()
	{
	if(personalDataOk&&passportDataOk){
		var form=$("#"+formName);
        var settings = form.data("settings");

        var attrs = Array(
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
	        if(in_array(this.id,attrs)){
	            this.status = 2; // force ajax validation
	        }
	    });
	    form.data("settings", settings);

		// trigger ajax validation
	    $.fn.yiiactiveform.validate(form, function (data) {
	        $.each(settings.attributes, function () {
				if(in_array(this.id,attrs)){
	                $.fn.yiiactiveform.updateInput(this, data, form);
                    this.afterValidateAttribute();
	            }
	        });
	    });
	}});
');
?>