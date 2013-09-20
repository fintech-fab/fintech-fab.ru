<?php
/**
 * @var $this DefaultController
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - Личный кабинет - Состояние подключения';

// подписка "висит" на скоринге
?>

<h4>Ваш пакет займов</h4>

<strong>Продукт:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionRequest() ?><br />
<strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?><br />
