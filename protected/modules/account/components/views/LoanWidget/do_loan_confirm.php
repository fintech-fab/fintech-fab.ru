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

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'loan-form',
	'action' => Yii::app()->createUrl('/account/doLoanConfirm'),
));

$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => $this->getInfoTitle(),
	'content' => $this->getFullInfo(),
));
$oModel->sendSmsCode = 1;
echo $form->hiddenField($oModel, 'sendSmsCode');
?>
<div class="alert in alert-block alert-warning">
	Чтобы принять условия, требуется подтверждение одноразовым SMS-кодом
</div>
<? $this->renderSendSmsButton(); ?>
<?php

$this->endWidget();
?>
<div class="center">
	<?=
	CHtml::link('отказаться', Yii::app()->createUrl('account/cancelLoan'), array(
		'style'   => 'color:red;text-decoration:underline;',
		'confirm' => "Ты действительно хочешь отменить перевод денег?",
	));
	?>
</div>