<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $passFormRender
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - Личный кабинет - Состояние подключения';

//подписка есть
?>

<h4>Ваш пакет займов</h4>

<strong>Баланс:</strong>  <?= Yii::app()->adminKreddyApi->getBalance(); ?> руб. <br />
<strong>Продукт:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionProduct() ?><br />
<strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?><br />    <strong>Пакет активен
	до:</strong>  <?=
(Yii::app()->adminKreddyApi->getSubscriptionActivity()) ?
	Yii::app()->adminKreddyApi->getSubscriptionActivity()
	: "&mdash;"; ?>
<br /><strong>Доступно займов:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionAvailableLoans(); ?><br />
<?php if (Yii::app()->adminKreddyApi->getSubscriptionLoanMoratorium()) {
	?>
	<strong>Мораторий на получение займа до:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionLoanMoratorium() ?>
	<br />
<?php
}
?>

