<?php
//* @var DefaultController $this */
/* @var ClientSubscribeForm $model */

$iProductId = 16;//Yii::app()->adminKreddyApi->getSubscriptionProductId();
$aChannelId = Yii::app()->adminKreddyApi->getLoanSelectedChannel();

?>

<ul>
	<li>
		<strong>Пакет:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductNameById($iProductId) ?>
	</li>
	<li><strong>Сумма займа:</strong>&nbsp; <?= Yii::app()->adminKreddyApi->getProductLoanAmountById($iProductId) ?>
		&nbsp;рублей
	</li>
	<li><strong>Срок займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLoanLifetimeById($iProductId) ?>
		&nbsp;дней
	</li>
	<li><strong>Способ получения займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelNameById($aChannelId) ?>
	</li>
</ul>
