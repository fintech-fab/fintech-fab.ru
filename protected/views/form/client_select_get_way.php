<?php
/* @var FormController $this */
/* @var ClientSelectGetWayForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Выбор способа получения займа
 */
?>

<div class="row">

	<?php $this->widget('CheckBrowserWidget'); ?>

	<?php $this->widget('StepsBreadCrumbsWidget'); ?>

	<?php

	$this->pageTitle = Yii::app()->name;

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
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/02T.png">
		<?php
		if (!(($oClientCreateForm->get_way = Yii::app()->clientForm->getSessionGetWay()) && (array_key_exists($oClientCreateForm->get_way, Dictionaries::aWays(Yii::app()->clientForm->getSessionProduct()))))) {
			$oClientCreateForm->get_way = "1";
		}
		?>
		<?php echo $form->radioButtonListRow($oClientCreateForm, 'get_way', Dictionaries::aWays(Yii::app()->clientForm->getSessionProduct()));
		?>
	</div>

	<?php $this->widget('ChosenConditionsWidget', array(
		'curStep' => Yii::app()->clientForm->getCurrentStep() + 1,
	)); ?>

	<div class="clearfix"></div>

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
