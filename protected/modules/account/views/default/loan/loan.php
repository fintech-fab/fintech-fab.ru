<?php
/* @var DefaultController $this */
/* @var ClientSubscribeForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Оформление займа";
?>
	<h4>Оформление займа</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'loan-form',
	'action' => Yii::app()->createUrl('/account/doLoan'),
));

?>

<?=
$form->radioButtonListRow($model, 'channel_id', Yii::app()->adminKreddyApi->getClientProductsChannelsList(), array("class" => "all"));

/**
 *
 *
 */

// если есть доступные пакеты для данного пользователя
$aClientProductsChannelsList = Yii::app()->adminKreddyApi->getClientProductsChannelsList();

if (!empty($aClientProductsChannelsList)) {

	echo $form->radioButtonListRow($model, 'channel_id', Yii::app()->adminKreddyApi->getClientProductsChannelsList(), array("class" => "all"));

	?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Оформить займ',
		)); ?>
	</div>

<?php

} else { // если доступных пакетов нет - выводим соответствующее сообщение
	?>
	<div class="alert alert-info"><?= Yii::app()->adminKreddyApi->getNoAvailableProductsMessage() ?></div>

<?php
}

$this->endWidget();
