<?php
/* @var DefaultController $this */
/* @var ClientSubscribeForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Оформление займа";

$iChannelId = Yii::app()->adminKreddyApi->getSubscribeFlexChannelId();
?>
<h4>Оформление займа</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => get_class($model),
	'action' => Yii::app()->createUrl('/account/doSubscribe'),
));

$model->channel_id = $iChannelId;
// если в сессии канала нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
if (empty($model->channel_id)) {
	$model->channel_id = reset(array_keys(Yii::app()->productsChannels->getChannelsForButtons()));
}

$model->amount = Yii::app()->adminKreddyApi->getSubscribeFlexAmount();
// если в сессии продукта нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
if (empty($model->amount)) {
	$model->amount = reset(array_keys(Yii::app()->adminKreddyApi->getFlexibleProduct()));
}

$model->time = Yii::app()->adminKreddyApi->getSubscribeFlexTime();
// если в сессии времени нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
if (empty($model->time)) {
	$model->time = reset(array_keys(Yii::app()->adminKreddyApi->getFlexibleProductTime()));
}
$form->radioButtonGroupsList($model, 'channel_id', Yii::app()->productsChannels->getChannelsForButtons(), array('type' => 'primary'));
?>
<div class="row">
	<div class="span7">
		<?php $this->widget('SliderWidget', array(
			'form'                  => $form,
			'model'                 => $model,
			'bIsNeedNewClientAlert' => Yii::app()->adminKreddyApi->getIsNewClient(),
		)); ?>
	</div>
</div>
<div class="row">
	<div class="row offset1 span5 conditions">
		<div class="alert alert-success" style="margin-top:50pt;">
			<h5 class="pay_legend">Выбранные условия</h5>
			<ul>
				<li>Размер займа:
					<span class="cost final_price"><?= ""; //Dictionaries::$aDataFinalPrices[$this->chosenProduct] ?></span>&nbsp;рублей
				</li>
				<li>Канал получения: <span class="cost channel"></span>
				</li>
				<li>Дата возврата займа: &nbsp;<span class="cost date"></span>
				</li>
				<li>Необходимо вернуть:
					<span class="cost price_count"><?= ""; //Dictionaries::$aDataPrices[$this->chosenProduct] ?></span>&nbsp;рублей
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'id' => 'submitNow',
		'buttonType' => 'submit',
		'type'       => 'primary',
		//'size'       => 'small',
		'label'      => 'Оформить сейчас!',
	)); ?>
</div>

<?php
$this->endWidget();

//эта функция предназначена для обработки нажатий на кнопки-переключатели, выбирающие канал
Yii::app()->clientScript->registerScript('radioButtonsTrigger', '
 var oChannelId = $("#' . get_class($model) . '_channel_id");
 oChannelId.on("change",function(){

		var sChannel = $("#' . get_class($model) . '").find("button[value*=" + this.value + "]").html();
		$(".cost.channel").html(sChannel);
		$("#amount").change();
		$("#time").change();
	});
oChannelId.change();
', CClientScript::POS_READY);
?>
