<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение адреса электронной почты";
?>
<h4>Изменение адреса электронной почты</h4>
<?php

?>
<div class="alert in alert-block alert-success">
	Код подтверждения операции успешно отправлен по SMS на номер +7<?= Yii::app()->user->getMaskedId() ?>
</div>
<div class="alert in alert-block alert-info">
	Для подтверждения операции введи код, отправленный по SMS
</div>

<div class="form" id="activeForm">
	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'          => 'check-sms-form',
		'action'      => Yii::app()->createUrl('/account/changeEmailCheckSmsCode'),
		'htmlOptions' => array(
			'class'        => "span4",
			'autocomplete' => 'off',
		),
	));

	?>
	<?= $form->textFieldRow($oSmsCodeForm, 'smsCode') ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'submit',
		'type'       => 'primary',
		'size'       => 'small',
		'label'      => 'Подтвердить',
	)); ?>

	<?php
	$this->endWidget();
	?>
</div>
