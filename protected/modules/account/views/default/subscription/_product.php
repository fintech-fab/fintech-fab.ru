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
		<strong>Пакет:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductNameById($iProductId) ?>
	</li>
	<li><strong>Сумма займа:</strong>&nbsp; <?= Yii::app()->adminKreddyApi->getProductLoanAmountById($iProductId) ?>
		&nbsp;рублей
	</li>
	<li><strong>Количество займов в
			Пакете:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLoanCountById($iProductId) ?></li>
	<li>
		<strong>Размер пакета:</strong>&nbsp;<?= $iPacketSize; ?>&nbsp;рублей
	</li>
	<li><strong>Срок займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLoanLifetimeById($iProductId) ?>
		&nbsp;дней
	</li>
	<li>
		<strong>Способ получения займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelNameById($iChannelId) ?>
	</li>
	<li><strong>Стоимость пакета:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductCostById($iProductId) ?>
		&nbsp;рублей
	</li>
	<li><strong>Срок действия
			подключения:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLifetimeById($iProductId) ?>
		&nbsp;дней
	</li>
</ul>
