<?php
/* @var DefaultController $this */
/* @var IkTbActiveForm $form */
/* @var string $sFormName */

$this->pageTitle = Yii::app()->name . " - Выбор канала";
?>
	<h4>Выбор канала</h4>

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
