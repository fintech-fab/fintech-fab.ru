<?php
/**
 * @var IkTbActiveForm $form
 * @var SMSCodeForm    $oModel
 * @var string         $sType
 * @var string         $sAction
 */
?>

<div id="alertSmsSent" class="alert in alert-success"><?= Dictionaries::C_SMS_SUCCESS; ?></div>
<div class="clearfix"></div>
<div class="well well-small span4">
	Твой номер телефон: +7<?= Yii::app()->user->getMaskedId(); ?>
</div>
<div class="clearfix"></div>
<div class="form" id="activeForm">
	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                     => get_class($oModel),
		'enableClientValidation' => true,
		'clientOptions'          => array(
			'validateOnChange' => true,
			'validateOnSubmit' => true,
		),
		'htmlOptions'            => array(
			'class'        => "span4",
			'autocomplete' => 'off',
		),
		'action'                 => Yii::app()->createUrl($sAction),
	));

	echo $form->hiddenField($oModel, 'sendSmsCode', array('value' => 1));
	echo $form->hiddenField($oModel, 'smsResend', array('value' => 1));

	$this->widget('bootstrap.widgets.TbButton', array(
		'id'         => 'btnResend',
		'buttonType' => 'submit',
		'icon'       => 'icon-refresh',
		'size'       => 'small',
		'label'      => 'Выслать код на телефон повторно',
		'disabled'   => true,
	));
	?>
	<div id="textUntilResend" class="span5 hide" style="margin-left: 0;">Повторно запросить SMS с паролем можно через:
		<span id="untilResend"></span>
	</div>
	<?php
	$this->endWidget();
	?>
	<div class="clearfix"></div>
	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                     => get_class($oModel),
		'enableClientValidation' => true,
		'htmlOptions'            => array(
			'class' => "span4",
		),
		'clientOptions'          => array(
			'validateOnChange' => true,
			'validateOnSubmit' => true,
		),
		'action'                 => Yii::app()->createUrl($sAction),
	));
	?>

	<label>Введи код из SMS:</label>
	<?= $form->textField($oModel, 'smsCode', array('class' => 'span4')); ?>
	<?= $form->error($oModel, 'smsCode'); ?>

	<div class="clearfix"></div>

	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'submit',
		'type'       => 'primary',
		'size'       => 'small',
		'label'      => 'Получить пароль',
	));
	/**
	 * конец формы проверки пароля
	 */
	$this->endWidget();

	//подключаем JS с таймером для кнопки
	$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/sms_countdown.js';
	Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_HEAD);
	//передаем данные для JS-таймера
	Yii::app()->clientScript->registerScript('showUntilResend2', '
	leftTime = new Date();
	leftTime.setTime(leftTime.getTime() + ' . Yii::app()->adminKreddyApi->getResetSmsCodeLeftTime() . '*1000);
	showUntilResend();'
		, CClientScript::POS_LOAD);
	?>
</div>
<div class="clearfix"></div>