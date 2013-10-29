<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $oSmsCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение пароля";
?>
	<h4>Изменение пароля</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/changePasswordSendSmsCode'),
));

$oSmsCodeForm->sendSmsCode = 1;
echo $form->hiddenField($oSmsCodeForm, 'sendSmsCode');
?>


	<?php //TODO вынести сообщения в константы ?>
	<div class="alert in alert-block alert-error span7">
		При попытке изменения пароля произошла ошибка. Возможно, неверно указан старый пароль.<br/>
		Попробуйте снова запросить пароль.<br />В случае, если ошибка
		повторяется, обратитесь в контактный центр.
	</div>
<?php

$this->endWidget();
