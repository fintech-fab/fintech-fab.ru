<?php
/* @var FormController $this */
/* @var ClientAddressForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Адрес:
 * + Регион
 * + Город
 * + Адрес
 * Контактное лицо:
 * + ФИО
 * + Номер телефона
 */

$this->pageTitle = Yii::app()->name;

?>

<div class="row">

	<?php $this->widget('CheckBrowserWidget'); ?>

	<?php $this->widget('StepsBreadCrumbsWidget'); ?>

	<?php

	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                   => get_class($oClientCreateForm),
		'enableAjaxValidation' => true,
		'clientOptions'        => array(
			'validateOnChange' => true,
			'validateOnSubmit' => true,
		),
		'action'               => Yii::app()->createUrl('/form/'),
	));

	?>

	<div class="row span6">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/03T.png">

		<h2>Адрес</h2>
		<? require dirname(__FILE__) . '/fields/address_reg.php' ?>
	</div>

	<?php $this->widget('ChosenConditionsWidget', array(
		'curStep' => Yii::app()->clientForm->getCurrentStep() + 1,
	)); ?>

	<div class="row span12">
		<h2>Контактное лицо</h2>
		<? require dirname(__FILE__) . '/fields/relatives_one.php' ?>
	</div>
	<div class="row span12">
		<h2>Дополнительный контакт (родственника/друга)</h2>
		<? require dirname(__FILE__) . '/fields/friend.php' ?>
		<span class="span10">
		<?php $this->widget('bootstrap.widgets.TbLabel', array(
			'label'       => '* Указывая дополнительный номер телефона родственника или друга, Вы увеличиваете шансы получить одобрение по займу.',
			'htmlOptions' => array('class' => 'label-explanation',),
		)); ?>
	</span>
	</div>

	<div class="clearfix"></div>
	<div class="row span11">
		<div class="form-actions">
			<? $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'      => 'Далее →',
			)); ?>
		</div>
	</div>
	<?
	$this->endWidget();
	?>

	<?php $this->widget('YaMetrikaGoalsWidget', array(
		'iDoneSteps' => Yii::app()->clientForm->getCurrentStep(),
	)); ?>

</div>

