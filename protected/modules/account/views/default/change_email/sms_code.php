<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение адреса электронной почты";
?>
	<h4>Изменение адреса электронной почты</h4>
	<div class="alert in alert-block alert-warning">
		Для изменения адреса электронной почты требуется подтверждение одноразовым SMS-кодом
	</div>
<?php
$this->widget('SmsCodeWidget', array(
	'oModel'        => $oSmsCodeForm,
	'sType'         => SmsCodeComponent::C_TYPE_CHANGE_EMAIL,
	'oSmsComponent' => Yii::app()->smsCode,
));
?>