<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Выбор суммы займа
 */

$this->pageTitle = Yii::app()->name;


$aCrumbs = array(
	array('Выбор пакета', 1),
	array('Заявка на займ', 2),
	array('Подтверждение номера телефона', 3),
	array('Идентификация', 4)
);

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

	<div class="row offset1 span4 conditions">

		<div class="alert alert-success" style="margin-top: 20pt;">
			<h5 class="pay_legend">Выбранные условия</h5>
			<?php

		?>
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

	<div class="clearfix"></div>
	<div class="row span11">
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
				'size'  => 'large',
				'label' => 'Оформить сейчас!',
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
Yii::app()->clientScript->registerScript('radioButtonsTrigger', '
 var oChannelId = $("#' . get_class($oClientCreateForm) . '_channel_id");
 oChannelId.on("change",function(){

		var sChannel = $("#' . get_class($oClientCreateForm) . '").find("button[value*=" + this.value + "]").html();
		$(".cost.channel").html(sChannel);
	});
oChannelId.change();
', CClientScript::POS_LOAD);
?>
