<?php
/* @var DefaultController $this */
/* @var ClientSubscribeForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Оформление займа";

// временно недоступно
?>
<h4>Оформление займа</h4>

<?php //TODO сменить сообщение для иваново ?>
<div class="alert alert-error"><?= Yii::app()->adminKreddyApi->getSubscriptionNotAvailableMessage() ?></div>
