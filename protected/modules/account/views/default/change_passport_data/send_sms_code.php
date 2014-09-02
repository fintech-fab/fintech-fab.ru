<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение паспортных данных";
?>
	<h4>Изменение паспортных данных</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'send-sms-form',
	'action' => Yii::app()->createUrl('/account/changePassportSendSmsCode'),
));

$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => 'Проверь введенные данные',
	'content' => $this->renderPartial('change_passport_data/_data', array(), true)
));
$oSmsCodeForm->sendSmsCode = 1;
echo $form->hiddenField($oSmsCodeForm, 'sendSmsCode');
?>
	<div class="alert in alert-block alert-warning">
		Для подачи заявки на изменение паспортных данных требуется подтверждение одноразовым SMS-кодом
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
