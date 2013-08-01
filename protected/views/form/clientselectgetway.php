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
	'action' => Yii::app()->createUrl('/form/'),
));
?>
<div class="row">
		<?php $this->widget('StepsBreadCrumbs',array(
			'curStep'=>Yii::app()->clientForm->getDoneSteps()+1,
		)); ?>

	<div class="row span6">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/02T.png">
		<?php
		if(!(($oClientCreateForm->get_way=Yii::app()->session['get_way'])&&(array_key_exists($oClientCreateForm->get_way, Dictionaries::aWays(Yii::app()->session['product'])))))
		{
			$oClientCreateForm->get_way = "1";
		}
		?>
		<?php echo $form->radioButtonListRow($oClientCreateForm, 'get_way', Dictionaries::aWays(Yii::app()->session['product']));//TODO: вынести в контроллер
		?>
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
