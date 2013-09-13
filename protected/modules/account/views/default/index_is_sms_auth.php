<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $passFormRender
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - Личный кабинет - Состояние подписки';

?>

	<h4>Ваш пакет займов</h4>

<?php
//если нет подписки
if (!Yii::app()->adminKreddyApi->getSubscriptionProduct() && !Yii::app()->adminKreddyApi->getSubscriptionRequest()) {
	?>
	<h5>Нет активных пакетов</h5>
<?php
//если подписка "висит" на скоринге
} elseif (Yii::app()->adminKreddyApi->getSubscriptionRequest()) {
	?>
	<strong>Продукт:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionRequest() ?><br />
	<strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?><br />
<?php
//если подписка есть
} else {
	?>
	<strong>Баланс:</strong>  <?= Yii::app()->adminKreddyApi->getBalance(); ?> руб. <br />
	<strong>Продукт:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionProduct() ?><br />
	<strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?><br />
	<strong>Пакет активен до:</strong>  <?=
	(Yii::app()->adminKreddyApi->getSubscriptionActivity()) ?
		Yii::app()->adminKreddyApi->getSubscriptionActivity()
		: "&mdash;"; ?>
	<br />
	<strong>Доступно займов:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionAvailableLoans(); ?><br />
<?php
}
if (Yii::app()->adminKreddyApi->getSubscriptionMoratorium()) {
	?>
	<strong>Мораторий на получение займа до:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionMoratorium() ?>
	<br />
<?php
}
