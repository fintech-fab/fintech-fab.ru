<?php
//* @var DefaultController $this */
/* @var ClientSubscribeForm $model */

$iProductId = Yii::app()->adminKreddyApi->getSubscribeSelectedProductId();

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
	<li><strong>Стоимость подключения:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductCostById($iProductId) ?>
		&nbsp;рублей
	</li>
	<li><strong>Срок действия
			подключения:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLifetimeById($iProductId) ?>
		&nbsp;дней
	</li>
	<li><strong>Количество займов в
			пакете:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLoanCountById($iProductId) ?></li>
</ul>
