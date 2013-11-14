<?php
/* @var DefaultController $this */
/* @var ClientSubscribeForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Подключение Пакета";

// временно недоступно
?>
<h4>Подключение Пакета</h4>

<div class="alert alert-error"><?= Yii::app()->adminKreddyApi->getSubscriptionNotAvailableMessage() ?></div>
