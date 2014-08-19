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

<h4>Авторизация по SMS-паролю</h4>

<div id="alertSmsSent" class="alert in alert-success"><?= Dictionaries::C_SMS_PASS_SUCCESS; ?></div>
<div class="clearfix"></div>
<div class="well well-small span4">
	Твой номер телефона: +7<?= Yii::app()->user->getMaskedId(); ?>
</div>
<div class="clearfix"></div>
<div class="form" id="activeForm">
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



	<?php

	$model->sendSmsPassword = 1;
	echo $form->hiddenField($model, 'sendSmsPassword');

	$this->widget('bootstrap.widgets.TbButton', array(
		'id'         => 'btnResend',
		'buttonType' => 'submit',
		'icon'       => 'icon-refresh',
		'size'       => 'small',
		'label'      => 'Выслать пароль на телефон повторно',

		'disabled'   => true,
	));
	?>
	<div id="textUntilResend" class="span5 hide" style="margin-left: 0px;">Повторно запросить SMS с паролем можно через:
		<span id="untilResend"></span></div>
	<?php
	$this->endWidget();
	?>
	<div class="clearfix"></div>
	<?php

	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                     => "checkSmsPass",
		'enableClientValidation' => true,
		'htmlOptions'            => array(
			'class'        => "span4",
			'autocomplete' => 'off',
		),
		'clientOptions'          => array(
			'validateOnChange' => true,
			'validateOnSubmit' => true,
		),
		'action'                 => Yii::app()->createUrl('/account/smsPassAuth'),
	));
	?>

	<label>Введи пароль из SMS:</label>
	<?= $form->textField($model, 'smsPassword', array('class' => 'span4', 'autocomplete' => 'off')); ?>
	<?= $form->error($model, 'smsPassword'); ?>

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
</div>
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
