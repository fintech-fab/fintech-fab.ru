<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение пароля";
?>
<h4>Изменение пароля</h4>

<?php //TODO вынести сообщения в константы ?>
<div class="alert in alert-block alert-error">
	При попытке изменения пароля произошла ошибка. Возможно, неверно указан старый пароль.<br /> Попробуйте повторить
	операцию смены пароля.<br />В случае, если ошибка повторяется, обратитесь в контактный центр.
</div>
