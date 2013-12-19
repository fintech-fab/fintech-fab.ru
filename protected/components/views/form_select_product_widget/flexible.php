<?php
/**
 * @var $form                    IkTbActiveForm
 * @var $sSelectProductView      string
 * @var $sSelectProductModelName string
 * @var $oClientCreateForm       ClientFlexibleProductForm
 */

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'clientOptions'        => array(
		'validateOnChange' => true,
	),
	'action'               => Yii::app()->createUrl('/form/'),
)); //todo: аякс-сабмит

// todo: пока берётся тупо из сессии
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
?>

<div class="row">
	<?= $form->radioButtonGroupsList($oClientCreateForm, 'channel_id', Yii::app()->productsChannels->getChannelsForButtons(), array('type' => 'primary')); ?>
</div>

<div class="row">
	<div class="span7">
		<?php $this->widget('SliderWidget', array('form' => $form, 'model' => $oClientCreateForm)); ?>
	</div>

	<div class="offset1 span4">
		<?php $this->widget('SelectedProductWidget', array('sSelectProductView' => $sSelectProductView, 'sSelectProductModelName' => $sSelectProductModelName,)); ?>
	</div>
</div>

<div class="clearfix"></div>

<?php
$this->endWidget();
?>

<?php
//эта функция предназначена для обработки нажатий на кнопки-переключатели, выбирающие канал
Yii::app()->clientScript->registerScript('radioButtonsColors2', '
	//разные цвета кнопок
	$(".btn-group .btn.btn-primary").first().next().removeClass("btn-primary").addClass("btn-danger").next().removeClass("btn-primary").addClass("btn-warning").next().removeClass("btn-primary").addClass("btn-success");
', CClientScript::POS_READY);
?>
