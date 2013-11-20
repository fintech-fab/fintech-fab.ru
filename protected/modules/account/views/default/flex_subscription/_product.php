<?php
//* @var DefaultController $this */
/* @var ClientSubscribeForm $model */

$iChannelId = Yii::app()->adminKreddyApi->getSubscribeFlexChannelId();

?>

<ul>
	<li>
		<strong>Сумма займа:</strong>&nbsp; <?= Yii::app()->adminKreddyApi->getSubscribeFlexAmount() ?>
		&nbsp;рублей
	</li>
	<li>
		<strong>Канал получения займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelNameById($iChannelId) ?>
	</li>
	<li>
		<strong>Дата возврата займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscribeFlexTime(true) ?>
	</li>
	<li><strong>Необходимо вернуть:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscribeFlexCost($iChannelId) ?>
		&nbsp;рублей
	</li>
	<li><strong>Время зачисления займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelSpeed($iChannelId); ?>
	</li>
</ul>
