<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $sIdentifyRender
 */

$this->breadcrumbs = array(
	$this->module->id,
);

if (SiteParams::getIsIvanovoSite()) {
	$this->pageTitle = Yii::app()->name . ' - Статус займа';
} else {
	$this->pageTitle = Yii::app()->name . ' - Ваш Пакет займов';
}

//количество доступных займов
$iAvailableLoans = Yii::app()->adminKreddyApi->getSubscriptionAvailableLoans();

// мораторий на займ
?>

<?php if (SiteParams::getIsIvanovoSite()): ?>
	<h4>Статус займа</h4>
<?php endif; ?>
<?php if (!SiteParams::getIsIvanovoSite()): ?>
	<h4>Ваш Пакет займов</h4>
<?php endif; ?>


<?php if (Yii::app()->adminKreddyApi->getBalance() != 0) {
	// выводим сообщение, если баланс не равен 0
	?>
	<strong>Баланс:</strong>  <?= Yii::app()->adminKreddyApi->getBalance(); ?> руб. <br />
<?php } ?>

<?php if (!SiteParams::getIsIvanovoSite()): ?>
	<strong>Пакет:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionProduct() ?><br />
<?php endif; ?>

<?php if (SiteParams::getIsIvanovoSite()): ?>
	<strong>Сумма займа:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionLoanAmount() ?><br />
<?php endif; ?>

<strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?><br />


<?php if ($iAvailableLoans > 0): ?>
	<strong>Пакет активен до:</strong>  <?=
	(Yii::app()->adminKreddyApi->getSubscriptionActivity()) ?
		Yii::app()->adminKreddyApi->getSubscriptionActivity()
		: "&mdash;"; ?>
	<br />

	<strong>Доступно займов:</strong> <?= $iAvailableLoans; ?><br />
<?php endif; ?>

<div class="clearfix"></div>
<div class="well">
	Вы можете оформить займ <?= Yii::app()->adminKreddyApi->getMoratoriumLoan() ?>
	<br />
</div>

<?= $sIdentifyRender ?>
