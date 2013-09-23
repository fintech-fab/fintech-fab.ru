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

<?php if (Yii::app()->adminKreddyApi->getBalance() != 0) {
	// выводим сообщение, если баланс не равен 0
	?>
	<strong>Баланс:</strong>  <?= Yii::app()->adminKreddyApi->getBalance(); ?> руб. <br />
<?php } ?>
<strong>Пакет:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionProduct() ?><br />
<strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?><br />    <strong>Пакет активен
	до:</strong>  <?=
(Yii::app()->adminKreddyApi->getSubscriptionActivity()) ?
	Yii::app()->adminKreddyApi->getSubscriptionActivity()
	: "&mdash;"; ?>
<br /><strong>Доступно займов:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionAvailableLoans(); ?><br />
<?php //TODO: проверять, что есть доступные займы + время убрать
// если есть мораторий на займ и ещё есть доступные займы, выводим соответствующее сообщение
if (Yii::app()->adminKreddyApi->getMoratoriumLoan() && Yii::app()->adminKreddyApi->getSubscriptionAvailableLoans() > 0) {
	?>
	Вы можете отправить заявку <?= Yii::app()->adminKreddyApi->getMoratoriumLoan() ?>
	<br />
<?php
}
?>

