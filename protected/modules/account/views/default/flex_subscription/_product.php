<?php
//* @var DefaultController $this */
/* @var ClientSubscribeForm $model */

$iProductId = Yii::app()->adminKreddyApi->getSubscribeSelectedProductId();
$iChannelId = Yii::app()->adminKreddyApi->getSubscribeSelectedChannelId();

$iPacketSize = Yii::app()->adminKreddyApi->getProductLoanAmountById($iProductId) *
	Yii::app()->adminKreddyApi->getProductLoanCountById($iProductId);
?>

<ul>
	<li>
		<strong>Размер займа:</strong>&nbsp; <?= Yii::app()->adminKreddyApi->getSubscriptionFlexAmount() ?>
		&nbsp;рублей
	</li>
	<li>
		<strong>Канал получения займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelNameById($iChannelId) ?>
	</li>
	<li>
		<strong>Дата возврата займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscriptionFlexTime() ?>
	</li>
	<li><strong>Необходимо
			вернуть:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscriptionFlexCost($iChannelId) ?>
		&nbsp;рублей
	</li>
</ul>
