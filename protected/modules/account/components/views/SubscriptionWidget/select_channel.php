<?php
/**
 * @var SubscriptionWidget  $this
 * @var IkTbActiveForm      $form
 * @var ClientSubscribeForm $oModel
 */

$iProductId = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();
?>
	<h4><?= $this->getSelectChannelHeader(); ?></h4>

	<div class="alert alert-info">
		<?= $this->getProductInfo(); ?>
	</div>

<?php

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/doSubscribe'),
));

$this->widget('application.modules.account.components.ShowChannelsWidget', array(
		'sFormName'          => get_class($oModel),
		'aAvailableChannels' => Yii::app()->adminKreddyApi->getAvailableChannelValues(),
	)
);

$this->endWidget();
