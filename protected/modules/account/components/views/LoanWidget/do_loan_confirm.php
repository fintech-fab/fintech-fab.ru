<?php
/**
 * @var LoanWidget     $this
 * @var SMSCodeForm    $oModel
 * @var IkTbActiveForm $form
 */

?>
	<h4>Индивидуальные условия договора потребительского займа</h4>
<?php
echo $this->getIndividualConditionInfo();
$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => $this->getInfoTitle(),
	'content' => $this->getFullInfo(),
));
?>
<div class="alert in alert-block alert-warning">
	Чтобы принять условия, требуется подтверждение одноразовым SMS-кодом
</div>
<?php
$this->widget('SmsCodeWidget', array(
	'oModel'        => $oModel,
	'sType'         => SmsCodeComponent::C_TYPE_LOAN,
	'oSmsComponent' => Yii::app()->smsCode,
));
?>
<div class="center">
	<?=
	CHtml::link('отказаться', Yii::app()->createUrl('account/cancelLoan'), array(
		'style'   => 'color:red;text-decoration:underline;',
		'confirm' => "Ты действительно хочешь отменить перевод денег?",
	));
	?>
</div>
