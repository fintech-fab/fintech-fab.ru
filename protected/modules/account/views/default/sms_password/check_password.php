<?php
/**
 * @var AccountResetPasswordForm $model
 * @var DefaultController        $this
 * @var IkTbActiveForm           $form
 */

/*
 * Ввести пароль из SMS
 */

$this->pageTitle = Yii::app()->name . " - Личный кабинет";
?>


	<div id="alertSmsSent" class="alert in alert-success span4"><?= Dictionaries::C_SMS_SUCCESS; ?></div>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                     => 'ajaxResendSms',
	'enableClientValidation' => true,
	'clientOptions'          => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
	),
	'htmlOptions'            => array(
		'class' => "span4",
	),
	'action'                 => Yii::app()
		->createUrl('/account/smsPassResend'),
));
?>

	<div class="well">
		Ваш телефон: +7<?= Yii::app()->user->getId(); ?>
	</div>

<?php

$model->sendSmsPassword = 1;
echo $form->hiddenField($model, 'sendSmsPassword');

$this->widget('bootstrap.widgets.TbButton', array(
	'id'         => 'btnResend',
	'buttonType' => 'submit',
	'icon'       => 'icon-refresh',
	'size'       => 'small',
	'label'      => 'Выслать код на телефон повторно',
	'disabled'   => true,
));
?>
	<div id="textUntilResend" class="hide">Повторно запросить SMS можно через: <span id="untilResend"></span></div>
	<div id="actionAnswerResend" class="help-block error"></div>
<?php
$this->endWidget();
?>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                     => "checkSmsPass",
	'enableClientValidation' => true,
	'htmlOptions'            => array(
		'class' => "span4",
	),
	'clientOptions'          => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
	),
	'action'                 => Yii::app()->createUrl('/account/smsPassAuth'),
));
?>

	<label>Введите пароль из SMS:</label>
<?= $form->textField($model, 'smsPassword', array('class' => 'span4')); ?>
<?= $form->error($model, 'smsPassword'); ?>

	<div class="help-block error hide" id="actionAnswer"></div>

	<div class="clearfix"></div>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit',
	'type'       => 'primary',
	'size'       => 'small',
	'label'      => 'Отправить',
));
/**
 * конец формы проверки пароля
 */
$this->endWidget();
?>

<?php
//подключаем JS с таймером для кнопки
$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/sms_countdown.js';
Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_HEAD);
//передаем данные для JS-таймера
Yii::app()->clientScript->registerScript('showUntilResend2', '
	leftTime = new Date();
	leftTime.setTime(leftTime.getTime() + ' . Yii::app()->adminKreddyApi->getSmsPassLeftTime() . '*1000);
	showUntilResend();'
	, CClientScript::POS_LOAD);
?>