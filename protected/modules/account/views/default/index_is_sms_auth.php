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

<h4>Состояние подписки</h4>

<?php
if (!Yii::app()->adminKreddyApi->getSubscriptionProduct()) { //если нет подписки
	?>
	<h5>Нет активных подписок</h5>
<?php
} else {
	?>
	<strong>Баланс:</strong>  <?= Yii::app()->adminKreddyApi->getBalance(); ?> руб. <br />
	<strong>Продукт:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionProduct() ?><br />
	<strong>Подписка активна до:</strong>  <?= Yii::app()->adminKreddyApi->getSubscriptionActivity(); ?> <br />
	<strong>Доступно займов:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionAvailableLoans(); ?><br />
<?php
}
if (Yii::app()->adminKreddyApi->getSubscriptionMoratorium()) {
	?>
	<strong>Мораторий на получение займа до:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionMoratorium() ?>
	<br />
<?php
}

?>
