<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Настройка безопасности";
?>
	<h4>Настройка безопасности</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'send-sms-form',
	'action' => Yii::app()->createUrl('/account/changeSmsAuthSettingSendSmsCode'),
));

$oSmsCodeForm->sendSmsCode = 1;
?>

<?= $form->hiddenField($oSmsCodeForm, 'sendSmsCode'); ?>

	<div class="alert in alert-block alert-error">
		При отправке SMS с кодом произошла ошибка. Попробуй снова запросить код.<br />В случае, если ошибка повторяется,
		обратись в контактный центр.
	</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Отправить SMS с кодом подтверждения на номер +7' . Yii::app()->user->getMaskedId(),
		)); ?>
	</div>

<?php

$this->endWidget();
