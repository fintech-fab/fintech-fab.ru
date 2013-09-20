<?php
/**
 * @var $this DefaultController
 *
 * Нет подписки
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - Личный кабинет - Состояние подключения';

?>

<h4>Ваш пакет займов</h4>

<h5>Нет активных пакетов</h5><strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?>
