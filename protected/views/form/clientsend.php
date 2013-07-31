<?php
/* @var FormController $this*/
/* @var ClientSendForm $model*/
/* @var IkTbActiveForm $form*/
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Цифровой код
 * Согласие с условиями и передачей данных
 */


$this->pageTitle=Yii::app()->name;

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
'id' => get_class($oClientCreateForm),
'enableAjaxValidation' => true,
'action' => '/form/',
));
?>
<div class="row">
	<div class="span12">
		<?php $this->widget('StepsBreadCrumbs',array(
			'curStep'=>Yii::app()->clientForm->getCurrentStep()+1,
		)); ?>

	<div class="row span12">
		<img src="/static/img/05T.png">
		<br/>
		<?php require dirname(__FILE__) . '/fields/numeric_code.php' ?>
		<?php require dirname(__FILE__) . '/fields/complete.php' ?>
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
</div>
<?

$this->endWidget();
?>
