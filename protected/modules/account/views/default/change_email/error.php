<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение адреса электронной почты";
?>
	<h4>Изменение адреса электронной почты</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/changeEmailSendSmsCode'),
));

$oSmsCodeForm->sendSmsCode = 1;
echo $form->hiddenField($oSmsCodeForm, 'sendSmsCode');
?>


<?php //TODO вынести сообщения в константы ?>
	<div class="alert in alert-block alert-error">
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
