<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Включение/Выключение дополнительной аутентификации по смс";
?>
	<h4>Включение/Выключение дополнительной аутентификации по смс</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/changeSmsAuthSettingSendSmsCode'),
));

$oSmsCodeForm->sendSmsCode = 1;
echo $form->hiddenField($oSmsCodeForm, 'sendSmsCode');
?>

	<div class="alert in alert-block alert-error span7">
		При отправке SMS с кодом произошла ошибка. Попробуйте снова запросить код.<br />В случае, если ошибка
		повторяется, обратитесь в контактный центр.
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
