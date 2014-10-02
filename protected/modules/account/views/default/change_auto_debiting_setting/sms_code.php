<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */


$this->pageTitle = Yii::app()->name . " - Настройка профиля";
?>

	<h4>Настройка профиля</h4>
	<div class="alert in alert-block alert-warning">
		Для изменения параметра автосписания требуется подтверждение одноразовым SMS-кодом
	</div>
<?php
$this->widget('SmsCodeWidget', array(
	'oModel'        => $oSmsCodeForm,
	'sType'         => SmsCodeComponent::C_TYPE_CHANGE_AUTO_DEBITING_SETTING,
	'oSmsComponent' => Yii::app()->smsCode,
));
?>