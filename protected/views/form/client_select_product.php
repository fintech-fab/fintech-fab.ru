<?php
/* @var FormController $this */
/* @var ClientSelectProductForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Выбор суммы займа
 */

$this->pageTitle = Yii::app()->name;

$aCrumbs = Yii::app()->clientForm->getBreadCrumbs();

?>

<div class="row">

	<?php $this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs)); ?>

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
	<div class="span6">
		<div class="row">

			<?php
			$oClientCreateForm->product = Yii::app()->clientForm->getSessionProduct();
			// если в сессии продукта нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
			if (empty($oClientCreateForm->product)) {
				$oClientCreateForm->product = reset(array_keys(Yii::app()->productsChannels->getProducts()));
			}
			?>
			<?= $form->radioButtonListRow($oClientCreateForm, 'product', Yii::app()->productsChannels->getProducts(), array("class" => "all")); ?>
		</div>
		<br />

		<div class="row">
			<?php
			$oClientCreateForm->channel_id = Yii::app()->clientForm->getSessionChannel();
			// если в сессии продукта нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
			if (empty($oClientCreateForm->channel_id)) {
				$oClientCreateForm->channel_id = reset(array_keys(Yii::app()->productsChannels->getChannels()));
			}
			?>
			<?= $form->radioButtonListRow($oClientCreateForm, 'channel_id', Yii::app()->productsChannels->getChannels(), array("class" => "all")); ?>
		</div>
	</div>

	<div class="span6">
		<?php $this->widget('SelectedProductWidget', array('sSelectProductView' => $sSelectProductView, 'sSelectProductModelName' => $sSelectProductModelName)); ?>
	</div>

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

	<?php
	$this->endWidget();
	?>

	<div class="clearfix"></div>
	<br />

	<div class="span8 offset2">
		<div class="alert in alert-block fade alert-info center">
			<strong>Если Вы уже являетесь нашим Клиентом, воспользуйтесь <?=
				CHtml::link('Личным кабинетом', Yii::app()
					->createUrl('account')) ?>.</strong>
		</div>
	</div>

	<?php $this->widget('YaMetrikaGoalsWidget', array(
		'iDoneSteps' => Yii::app()->clientForm->getCurrentStep(),
	)); ?>

</div>
