<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */


$this->pageTitle = Yii::app()->name . " - Настройка безопасности";
?>

	<h4>Настройка безопасности</h4>

<?php
$this->widget('SmsCodeWidget', array(
	'oModel'        => $oSmsCodeForm,
	'sType'         => SmsCodeComponent::C_TYPE_CHANGE_SMS_AUTH_SETTING,
	'oSmsComponent' => Yii::app()->smsCode,
));
?>

