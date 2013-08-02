<?php
/* @var FormController $this*/
/* @var ClientSendForm $model*/
/* @var IkTbActiveForm $form*/
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Цифровой код
 * Согласие с условиями и передачей данных
 */

?>

<div class="row">

	<?php $this->widget('StepsBreadCrumbsWidget'); ?>

	<?php

	$this->pageTitle=Yii::app()->name;

	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id' => get_class($oClientCreateForm),
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnChange'=>true,
			'validateOnSubmit'=>true,
		),
		'action' => Yii::app()->createUrl('/form/'),
	));

	?>

	<div class="row span5">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/05T.png">
		<br/>
		<?php require dirname(__FILE__) . '/fields/numeric_code.php' ?>
		<?php require dirname(__FILE__) . '/fields/complete.php' ?>
	</div>

	<?php $this->widget('ChosenConditionsWidget',array(
		'curStep'=>Yii::app()->clientForm->getCurrentStep()+1,
	)); ?>

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
