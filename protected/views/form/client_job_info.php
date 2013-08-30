<?php
/* @var FormController $this */
/* @var ClientJobInfoForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Место работы
 * Должность
 * Номер телефона
 * Стаж работы
 * Среднемесячный доход
 * Среднемесячный расход
 * Наличие кредитов и займов в прошлом
 */

$this->pageTitle = Yii::app()->name;

?>

<div class="row">

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
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/04T.png"> <br />
		<? require dirname(__FILE__) . '/fields/job.php' ?>
	</div>

	<?php $this->widget('ChosenConditionsWidget', array(
		'curStep' => Yii::app()->clientForm->getCurrentStep() + 1,
	)); ?>

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
		'iDoneSteps'    => Yii::app()->clientForm->getCurrentStep(),
		'iSkippedSteps' => 2,
	)); ?>

</div>
