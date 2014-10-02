<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Оплата с банковской карты";
?>
	<h4>Оплата с банковской карты</h4>
<div class="alert in alert-block alert-warning">
	Для проведения оплаты требуется подтверждение одноразовым SMS-кодом
</div>
<?php
$this->widget('SmsCodeWidget', array(
	'oModel'        => $oSmsCodeForm,
	'sType'         => SmsCodeComponent::C_TYPE_PAY,
	'oSmsComponent' => Yii::app()->smsCode,
));
?>

