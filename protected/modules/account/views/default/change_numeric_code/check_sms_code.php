<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение цифрового кода";
?>
<h4>Изменение цифрового кода</h4>
<?php

/* $this->widget('bootstrap.widgets.TbBox', array(
	'title'   => 'Информация о подключении',
	'content' => $this->renderPartial('change_passport_data/_data', array(), true)
));*/
?>
<div class="alert in alert-block alert-success span7">
	Код подтверждения операции успешно отправлен по SMS на номер +7<?= Yii::app()->user->getMaskedId() ?>
</div>
<div class="alert in alert-block alert-info span7">
	Для подтверждения операции введите код, отправленный Вам по SMS
</div>
<div class="form" id="activeForm">
	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'          => 'check-sms-form',
		'action'      => Yii::app()->createUrl('/account/changeNumericCodeCheckSmsCode'),
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
