<?php
/* @var DefaultController $this */
/* @var IkTbActiveForm $form */
/* @var string $sFormName */

$this->pageTitle = Yii::app()->name . " - Выберите канал получения займа";
?>
	<h4>Выберите канал получения займа</h4>

	<div class="alert alert-info">Ваш пакет займов -
		&quot;<?= Yii::app()->adminKreddyApi->getProductNameById(Yii::app()->adminKreddyApi->getSubscribeSelectedProduct()) ?>
		&quot;<br /> Размер займа
		- <?= Yii::app()->adminKreddyApi->getProductLoanAmountById(Yii::app()->adminKreddyApi->getSubscribeSelectedProduct()) ?>
		руб.
	</div>

<?php

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/doSubscribe'),
));

$this->widget('application.modules.account.components.ShowChannelsWidget', array(
		'sFormName'             => $sFormName,
		'aAllChannelNames' => Yii::app()->adminKreddyApi->getProductsChannels(),
		'aAvailableChannelKeys' => Yii::app()->adminKreddyApi->getSelectedProductChannelsList(),
	)
);

$this->endWidget();
