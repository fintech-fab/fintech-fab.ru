<?php
/**
 * @var $form                    IkTbActiveForm
 * @var $sSelectProductView      string
 * @var $sSelectProductModelName string
 * @var $oClientCreateForm       ClientFlexibleProductForm
 */

?>

<div id="form_selected_product_ivanovo">
	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                   => get_class($oClientCreateForm),
		'enableAjaxValidation' => true,
		'clientOptions'        => array(
			'validateOnChange' => true,
		),
		'action'               => Yii::app()->createUrl('/form/saveSelectedProduct'),
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
	?>

	<div class="row span10">
		<?php echo $form->radioButtonGroupsListRow($oClientCreateForm, 'channel_id', Yii::app()->productsChannels->getChannelsForButtons(), array('type' => 'primary', 'label' => false, 'class' => 'btn-small')); ?>
	</div>

	<div class="row">

		<div class="span5" style="padding: 5px 5px 10px 30px;">
		<?php $this->widget('SliderWidget', array('form' => $form, 'model' => $oClientCreateForm)); ?>
		</div>

		<div class="offset1 span4" style="width:330px; padding-top:30px;">
			<?php $this->widget('SelectedProductWidget', array('sSelectProductView' => $sSelectProductView, 'sSelectProductModelName' => $sSelectProductModelName,)); ?>
		</div>
	</div>
	<?php
	$this->endWidget();
	?>
</div>

<?php
//эта функция предназначена для обработки нажатий на кнопки-переключатели, выбирающие канал
Yii::app()->clientScript->registerScript('radioButtonsColors', '
	//разные цвета кнопок
	$(".btn-group .btn.btn-primary").first().next().removeClass("btn-primary").addClass("btn-danger").next().removeClass("btn-primary").addClass("btn-warning").next().removeClass("btn-primary").addClass("btn-success");
', CClientScript::POS_READY);
?>
