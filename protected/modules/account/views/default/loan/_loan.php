<?php
//* @var DefaultController $this */
/* @var ClientSubscribeForm $model */

$iChannelId = Yii::app()->adminKreddyApi->getLoanSelectedChannel();

?>

<ul>
	<li>
		<strong>Пакет:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscriptionProduct() ?>
	</li>
	<li><strong>Сумма займа:</strong>&nbsp; <?= Yii::app()->adminKreddyApi->getSubscriptionLoanAmount() ?>
		&nbsp;рублей
	</li>
	<li><strong>Срок займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscriptionLoanLifetime() ?>
		&nbsp;дней
	</li>
	<li><strong>Способ получения займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelNameById($iChannelId) ?>
	</li>
	<li><strong>Время перечисления займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelSpeed($iChannelId); ?>
	</li>
</ul>
