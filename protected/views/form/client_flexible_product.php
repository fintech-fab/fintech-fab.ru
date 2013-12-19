<?php
/* @var FormController $this */
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
			'validateOnChange' => false,
			'validateOnSubmit' => true,
		),
		'action'               => Yii::app()->createUrl('/form/'),
	));

	$oClientCreateForm->channel_id = Yii::app()->clientForm->getSessionChannel();
	// если в сессии канала нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
	if (empty($oClientCreateForm->channel_id)) {
		$oClientCreateForm->channel_id = reset(array_keys(Yii::app()->productsChannels->getChannelsForButtons()));
	}

	$oClientCreateForm->amount = Yii::app()->clientForm->getSessionFlexibleProductAmount();
	// если в сессии продукта нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
	if (empty($oClientCreateForm->amount)) {
		$oClientCreateForm->amount = reset(array_keys(Yii::app()->adminKreddyApi->getFlexibleProduct()));
	}

	$oClientCreateForm->time = Yii::app()->clientForm->getSessionFlexibleProductTime();
	// если в сессии времени нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
	if (empty($oClientCreateForm->time)) {
		$oClientCreateForm->time = reset(array_keys(Yii::app()->adminKreddyApi->getFlexibleProductTime()));
	}

	$form->radioButtonGroupsList($oClientCreateForm, 'channel_id', Yii::app()->productsChannels->getChannelsForButtons(), array('type' => 'primary'));
	?>
	<div class="row span7">
		<?php $this->widget('SliderWidget', array('form' => $form, 'model' => $oClientCreateForm)); ?>
	</div>

	<div class="row offset1 span4">
		<?php $this->widget('SelectedProductWidget', array('sSelectProductView' => $sSelectProductView, 'sSelectProductModelName' => $sSelectProductModelName)); ?>
	</div>

	<div class="clearfix"></div>
	<div class="row span11">
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'         => 'submitNow',
				'buttonType' => 'submit',
				'type'       => 'primary',
				'size'       => 'large',
				'label'      => 'Оформить сейчас!',
			)); ?>
		</div>
	</div>
	<?php

	$this->endWidget();

	?>

	<?php $this->widget('YaMetrikaGoalsWidget', array(
		'iDoneSteps' => Yii::app()->clientForm->getCurrentStep(),
	)); ?>

</div>
<?php
//эта функция предназначена для обработки нажатий на кнопки-переключатели, выбирающие канал
Yii::app()->clientScript->registerScript('radioButtonsColors', '
	//разные цвета кнопок
	$(".btn-group .btn.btn-primary").first().next().removeClass("btn-primary").addClass("btn-danger").next().removeClass("btn-primary").addClass("btn-warning").next().removeClass("btn-primary").addClass("btn-success");
', CClientScript::POS_READY);
?>
