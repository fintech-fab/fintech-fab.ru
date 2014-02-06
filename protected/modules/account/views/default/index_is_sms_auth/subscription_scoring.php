<?php
/**
 * @var $this DefaultController
 */

// подписка "висит" на скоринге
?>

<?php if (SiteParams::getIsIvanovoSite()): ?>
	<h4>Статус займа</h4>
<?php endif; ?>
<?php if (!SiteParams::getIsIvanovoSite()): ?>
	<h4>Ваш Пакет займов</h4>
<?php endif; ?>

<?php if (SiteParams::getIsIvanovoSite()): ?>
	<strong>Сумма займа:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionRequestLoan() ?><br />
<?php endif; ?>
<?php if (!SiteParams::getIsIvanovoSite()): ?>
	<strong>Пакет:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionRequest() ?><br />
<?php endif; ?>

<?php
// если есть статус, выводим его
if (Yii::app()->adminKreddyApi->getStatusMessage()) {
	?>
	<strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?>
	<br />
<?php
}
?>
