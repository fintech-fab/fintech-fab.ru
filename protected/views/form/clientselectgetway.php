<?php
/* @var FormController $this*/
/* @var ClientSelectGetWayForm $model*/
/* @var IkTbActiveForm $form*/
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Выбор способа получения займа
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
		<img src="/static/img/02T.png">
		<?php echo $form->radioButtonListRow($oClientCreateForm, 'get_way', Dictionaries::$aWays);
		?>
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
