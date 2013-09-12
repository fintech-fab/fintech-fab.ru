<?php
/* @var FormController $this */
/* @var ClientSelectProductForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Выбор суммы займа
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
		<img src="<?= Yii::app()->request->baseUrl; ?>/static/img/01T.png" />
		<?php
		if (!($oClientCreateForm->product = Yii::app()->clientForm->getSessionProduct())) {
			$oClientCreateForm->product = "1";
		}
		?>
		<?= $form->radioButtonListRow($oClientCreateForm, 'product', Dictionaries::$aProducts, array("class" => "all")); ?>

	</div>

	<?php $this->widget('ChosenConditionsWidget', array(
		'curStep' => Yii::app()->clientForm->getCurrentStep() + 1,
	)); ?>

	<div class="clearfix"></div>
	<div class="row span11">
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
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
