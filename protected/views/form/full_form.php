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
	'enableClientValidation' => false,
	'type'=>'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
		'success'=>'js: function(data){alert(1);}'
	),
	'action'               => Yii::app()->createUrl('/form/'),
));
?>

<?php $collapse = $this->beginWidget('bootstrap.widgets.TbCollapse', array(
	'id'          => 'accordion1',
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
		<h4 style="font-weight: 400;" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#passportData">
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
		<h4 style="font-weight: 400;" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#address">
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
		<h4 style="font-weight: 400;" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#jobInfo">
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
		<h4 style="font-weight: 400;" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#send">
			Отправка</h4>
	</div>
	<div id="send" class="accordion-body collapse">
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
