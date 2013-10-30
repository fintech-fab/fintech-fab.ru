<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

$this->pageTitle = Yii::app()->name;


$aCrumbs = array(
	array('Выбор пакета', 1),
	array('Заявка на займ', 2),
	array('Подтверждение номера телефона', 3),
	array('Идентификация', 4)
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
<?php if (Yii::app()->session['error']): ?>
	<div class="alert alert-error"><?= Yii::app()->session['error']; ?></div>
	<?php Yii::app()->session['error'] = null; ?>
<?php endif; ?>
	<div class="alert in alert-block alert-info" style="color: #000000;">
		<strong>Всего несколько шагов и Вы получите решение по займу:</strong>

		<ol>
			<li>Выберите удобный Пакет займов и канал получения (на мобильный телефон или на карту Кредди).</li>
			<li>Заполните анкету</li>
			<li>Убедитесь в наличие работающей веб-камеры и используйте один из перечисленных браузеров: Chrome или
				Firefox последних версий.
			</li>
			<li>Приготовьте свой паспорт и <span id="second-document-popover" class="dashed">второй документ</span> для демонстрации в
				вэб-камеру при прохождении идентификации (подтверждение личности).
			</li<
			<li>Ознакомьтесь с Офертой <a href="#fl-distance" class="dotted" data-toggle="modal" data-target="#fl-distance">здесь.</a>
			</li>
		</ol>
	</div>

	<div class="clearfix"></div>
	<script type="text/javascript">
		$('#second-document-popover').popover({
			html: true,
			trigger: 'hover',
			content: 'Заграничный паспорт<br/>Водительское удостоверение<br/>'
					+'Пенсионное удостоверение<br/>Военный билет<br/>Свидетельство ИНН<br/>'
					+'Страховое свидетельство государственного пенсионного страхования'
		});
	</script>
	<div class="accordion-group">
		<div class="accordion-heading">
			<h4 id="personalDataHeading" class="accordion-toggle" data-toggle="collapse" href="#personalData">
				Личные данные</h4>
		</div>
		<div id="personalData" class="accordion-body collapse in">
			<div class="accordion-inner">
				<div class="row">
					<?php require dirname(__FILE__) . '/fields/personal_data.php' ?>
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
					<?php require dirname(__FILE__) . '/fields/passport_data.php' ?>
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
					<?php require dirname(__FILE__) . '/fields/address_reg.php' ?>
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
					<?php require dirname(__FILE__) . '/fields/job_info.php' ?>
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
					<?php require dirname(__FILE__) . '/fields/send.php' ?>
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