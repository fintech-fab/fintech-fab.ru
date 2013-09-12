<?php
//* @var DefaultController $this */
/* @var ClientSubscribeForm $model */

$iProductId = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();

?>

<ul>
	<li>
		<strong>Продукт:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductNameById($iProductId) ?>
	</li>
	<li><strong>Сумма займа:</strong>&nbsp; <?= Yii::app()->adminKreddyApi->getProductLoanAmountById($iProductId) ?>
		&nbsp;рублей
	</li>
	<li><strong>Срок займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLoanLifetimeById($iProductId) ?>
		&nbsp;дней
	</li>
	<li><strong>Стоимость подписки:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductCostById($iProductId) ?>
		&nbsp;рублей
	</li>
	<li><strong>Срок подписки:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLifetimeById($iProductId) ?>
		&nbsp;дней
	</li>
	<li><strong>Количество займов по
			подписке:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLoanCountById($iProductId) ?></li>
</ul>