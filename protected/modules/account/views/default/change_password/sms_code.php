<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение пароля";
?>
	<h4>Изменение пароля</h4>
<div class="alert in alert-block alert-warning">
	Для изменения пароля требуется подтверждение одноразовым SMS-кодом
</div>
<?php
$this->widget('SmsCodeWidget', array(
	'oModel'        => $oSmsCodeForm,
	'sType'         => SmsCodeComponent::C_TYPE_CHANGE_PASSWORD,
	'oSmsComponent' => Yii::app()->smsCode,
));
?>

