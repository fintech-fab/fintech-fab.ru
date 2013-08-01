<?php
/* @var FormController $this*/
/* @var ClientAddressForm $model*/
/* @var IkTbActiveForm $form*/
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


$this->pageTitle=Yii::app()->name;

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id' => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'action' => Yii::app()->createUrl('/form/'),
));
?>
<div class="row">
	<?php $this->widget('StepsBreadCrumbs',array(
		'curStep'=>Yii::app()->clientForm->getCurrentStep()+1,
	)); ?>

<div class="row span5">
	<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/03T.png">
	<h2>Адрес</h2>
		<? require dirname(__FILE__) . '/fields/address_reg.php' ?>
</div>

	<?php $this->widget('ChosenConditionsWidget',array(
		'curStep'=>Yii::app()->clientForm->getCurrentStep()+1,
	)); ?>

<div class="row span12">
	<h2>Контактное лицо</h2>
		<? require dirname(__FILE__) . '/fields/relatives_one.php' ?>
</div>

	<div class="clearfix"></div>

	<div class="form-actions">
		<? $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type' => 'primary',
			'label' => 'Далее →',
		)); ?>
	</div>
</div>
<?
$this->endWidget();
?>
