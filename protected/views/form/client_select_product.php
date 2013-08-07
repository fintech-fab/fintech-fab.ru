<?php
/* @var FormController $this*/
/* @var ClientSelectProductForm $model*/
/* @var IkTbActiveForm $form*/
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Выбор суммы займа
 */

?>

<div class="row">

	<?php $this->widget('BrowserWidget'); ?>

	<?php $this->widget('StepsBreadCrumbsWidget'); ?>

	<?php

	$this->pageTitle=Yii::app()->name;

	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id' => get_class($oClientCreateForm),
		'enableAjaxValidation' => true,
		'clientOptions'=>array(
			'validateOnChange'=>true,
			'validateOnSubmit'=>true,
		),
		'action' => Yii::app()->createUrl('/form/'),
	));

	?>
	<div class="row span6">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/01T.png"/>
		<?php
		if(!($oClientCreateForm->product=Yii::app()->clientForm->getSessionProduct()))
		{
			$oClientCreateForm->product = "1";
		}
		?>
		<?php echo $form->radioButtonListRow($oClientCreateForm, 'product', Dictionaries::$aProducts, array("class"=>"all"));?>

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
