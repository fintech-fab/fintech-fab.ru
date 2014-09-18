<?php
/**
 * @var SMSCodeForm       $model
 * @var DefaultController $this
 * @var IkTbActiveForm    $form
 */

/*
 * Пользователь вводит телефон, и, если он валидный и есть в базе Кредди, на него отправляется SMS с кодом.
 * Затем идёт перенаправление на форму проверки кода.
 *
 * //Также есть возможность повторно отправить SMS - с новым кодом.
 */

$this->pageTitle = Yii::app()->name;
?>
<div class="alert in alert-block alert-warning">
	Для получения подробной информации и совершения иных действий требуется авторизоваться по одноразовому SMS-паролю
</div>
<?php
$this->widget('SmsCodeWidget', array(
	'oModel'        => $model,
	'sType'         => SmsCodeComponent::C_TYPE_SITE_AUTH,
	'oSmsComponent' => Yii::app()->smsCode,
));
?>
<div class="row hide">
	<div class="help-block error" id="actionAnswer"></div>
</div>
