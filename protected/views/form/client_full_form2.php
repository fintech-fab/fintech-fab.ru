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

// если форма уже была заполнена, надо разблокировать кнопку "Отправить"
if (Yii::app()->clientForm->getFlagFullFormFilled()) {
	Yii::app()->clientScript->registerScript('scriptFlagFullFormFilled', '
		// разблокировали кнопку Отправить
		$("#submitButton").removeClass("disabled").attr("disabled",false);
	', CClientScript::POS_READY);
}

//регистрация .js файла с функциями beforeValidate и afterValidate для формы
$sJsPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/form-validate.js';
Yii::app()->clientScript->registerScriptFile($sJsPath);

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
		'beforeValidate'   => 'js: beforeValidate',
		'afterValidate'    => 'js: afterValidate'
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

$sResultFlagFullFormFilled = (Yii::app()->clientForm->getFlagFullFormFilled()) ? 'true' : 'false';
//объявляем переменные для .js
Yii::app()->clientScript->registerScript('formName', '
	var personalDataOk = false;
	var passportDataOk = false;
	var addressOk = false;
	var jobInfoOk = false;
	var sendFormOk = false;
	var bFlagFullFormFilled = ' . $sResultFlagFullFormFilled . ';
	var formName = "' . get_class($oClientCreateForm) . '";
', CClientScript::POS_BEGIN);
//загружаем .js файл формы (управляет поведением элементов формы)
$sJsPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/form_script.js';
Yii::app()->clientScript->registerScriptFile($sJsPath, CClientScript::POS_HEAD);

$this->widget('YaMetrikaGoalsWidget', array(
	'iDoneSteps'    => Yii::app()->clientForm->getCurrentStep(),
	'iSkippedSteps' => 2,
));