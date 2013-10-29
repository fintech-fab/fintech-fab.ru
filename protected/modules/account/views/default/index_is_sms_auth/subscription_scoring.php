<?php
/**
 * @var $this DefaultController
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - Ваш Пакет займов';

// подписка "висит" на скоринге
?>

<h4>Ваш Пакет займов</h4>

<strong>Пакет:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionRequest() ?><br />
<?php
// если есть статус, выводим его
if (Yii::app()->adminKreddyApi->getStatusMessage()) {
	?>
	<strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?>
	<br />
<?php
}
?>
<?= $sIdentifyRender ?>