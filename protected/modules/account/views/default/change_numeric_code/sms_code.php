<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение цифрового кода";
?>
	<h4>Изменение цифрового кода</h4>

<?php
$this->widget('SmsCodeWidget', array(
	'oModel'        => $oSmsCodeForm,
	'sType'         => SmsCodeComponent::C_TYPE_CHANGE_NUMERIC_CODE,
	'oSmsComponent' => Yii::app()->smsCode,
));
?>