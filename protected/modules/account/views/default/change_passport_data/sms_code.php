<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение паспортных данных";
?>
	<h4>Изменение паспортных данных</h4>
<div class="alert in alert-block alert-warning">
	Для подачи заявки на изменение паспортных данных требуется подтверждение одноразовым SMS-кодом
</div>
<?php
$this->widget('SmsCodeWidget', array(
	'oModel'        => $oSmsCodeForm,
	'sType'         => SmsCodeComponent::C_TYPE_CHANGE_PASSPORT,
	'oSmsComponent' => Yii::app()->smsCode,
));
?>
