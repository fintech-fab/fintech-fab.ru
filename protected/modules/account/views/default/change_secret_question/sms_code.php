<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение секретного вопроса";
?>
	<h4>Изменение секретного вопроса</h4>

<?php
$this->widget('SmsCodeWidget', array(
	'oModel'        => $oSmsCodeForm,
	'sType'         => SmsCodeComponent::C_TYPE_CHANGE_SECRET_QUESTION,
	'oSmsComponent' => Yii::app()->smsCode,
));
?>

