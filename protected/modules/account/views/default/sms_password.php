<?php

/**
 * @var DefaultController $this
 * @var SMSPasswordForm   $passForm
 * @var                   $smsState
 * @var                   $needSmsPass
 * @var                   $smsLeftTime
 * @var                   $act
 * @var IkTbActiveForm    $form
 */

/*
 * Ввести пароль из SMS
 */

$this->pageTitle = Yii::app()->name;
$model = $passForm;

$hideSmsSendButton = (!empty($this->smsState['passSent']) || empty($this->smsState['needSmsPass']));
$flagSmsAuthDone = !empty($this->smsState['smsAuthDone']);

// поле ввода кода и кнопку "далее" прячем, если не отправлено смс или авторизация уже выполнена
$flagHideFormCheckSMSCode = (empty($this->smsState['passSent']) || $flagSmsAuthDone);

$urlCheckSmsPass = Yii::app()->createUrl('/account/checkSmsPass', array('act' => $act));
$urlAjaxSendSMS = Yii::app()->createUrl('/account/ajaxSendSms', array('resend' => 0));
$urlAjaxResendSMS = Yii::app()->createUrl('/account/ajaxSendSms', array('resend' => 1));
/**
 * сколько минут до того, как можно отправить новую SMS
 */

$aParams = array(
	'minutesUntil'      => SiteParams::API_MINUTES_UNTIL_RESEND,
	'btnResendLabel'    => 'Выслать пароль на телефон повторно',
	'mainContentId'     => 'main-content',
	'enterSMSPassLabel' => 'Введите пароль из SMS:',
	'fieldSMSPassName'  => 'smsPassword',
);
?>
<div class="row">
	<div id="<?php echo get_class($model); ?>_alertSmsSent" class="alert in alert-success hide span7"><?php echo Dictionaries::C_SMS_SUCCESS; ?></div>
	<?php
	$form3 = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                     => get_class($model) . '_ajaxResendSms',
		'enableClientValidation' => true,
		'clientOptions'          => array(
			'validateOnChange' => true,
			'validateOnSubmit' => true,
		),
		'htmlOptions'            => array(
			'class' => "span4" . ($flagHideFormCheckSMSCode ? ' hide' : ''),
		),
		'action'                 => $urlAjaxResendSMS,
	));
	$this->widget('bootstrap.widgets.TbButton', array(
		'id'          => get_class($model) . '_btnResend',
		'buttonType'  => 'ajaxSubmit',
		'icon'        => 'icon-refresh',
		'url'         => $urlAjaxResendSMS,
		'size'        => 'small',
		'label'       => $aParams['btnResendLabel'],
		'disabled'    => true,
		'ajaxOptions' => array(
			'dataType' => "json",
			'type'     => "POST",
			'success'  => "function(data)  {
                               		if(!data) {
                               		    return;
                               		}
                                	if(data.type == 0) {
                                	    leftTime = new Date();
										leftTime.setTime(leftTime.getTime() + data.leftTime*1000);
										showUntilResend();
                                	    jQuery('#" . get_class($model) . "_alertSmsSent').fadeIn().delay(30000).fadeOut();
                                	    jQuery('#" . get_class($model) . "_actionAnswerResend').hide();
                                	    jQuery('#" . get_class($model) . "_textUntilResend').show();
                                	    jQuery('#" . get_class($model) . "_btnResend').addClass('disabled').attr('disabled','disabled');
                                	} else if(data.type == 2) {
                                	    //ругаемся ошибкой
                               			jQuery('#" . get_class($model) . "_actionAnswerResend').html(data.text).show();
                                	} else {
                               			jQuery('#" . get_class($model) . "_actionAnswerResend').html(data.text).show();
                                	}
                                	return;
                                } ",
		),

	));?>
	<div id="<?php echo get_class($model); ?>_textUntilResend">Повторно запросить SMS можно через:
		<span id="<?php echo get_class($model); ?>_untilResend"><?php echo $aParams['minutesUntil']; ?>:00</span>

		<div class="help-block error hide" id="<?php echo get_class($model); ?>_actionAnswerResend"></div>
	</div>
	<?php
	$this->endWidget();
	?>
</div>
<div class="row">
	<?php
	// если SMS на телефон ещё не отсылалось
	if (empty($hideSmsSendButton)) {
		$form2 = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
			'id'                     => get_class($model) . '_ajaxSendSms',
			'enableClientValidation' => true,
			'clientOptions'          => array(
				'validateOnChange' => true,
				'validateOnSubmit' => true,
			),
			'htmlOptions'            => array(
				'class' => "span10",
			),
			'action'                 => $urlAjaxSendSMS,
		));

		$this->widget('bootstrap.widgets.TbButton', array(
			'id'          => get_class($model) . '_sendSms',
			'buttonType'  => 'ajaxSubmit',
			'url'         => $urlAjaxSendSMS,
			'size'        => 'small',
			'label'       => 'Отправить на +7' . Yii::app()->user->getId() . ' SMS с паролем',
			'ajaxOptions' => array(
				'dataType' => "json",
				'type'     => "POST",
				'success'  => "function(data) {
									jQuery('#" . get_class($model) . "_actionAnswer').html(data.text).hide();
                                	if(data.type == 0) { /* SMS успешно отправлено */
                                	    jQuery('#" . get_class($model) . "_ajaxSendSms').hide();
                                	    jQuery('#" . get_class($model) . "_textUntilResend').show();
                                	    jQuery('#" . get_class($model) . "_ajaxResendSms').show();
                                		jQuery('#" . get_class($model) . "_checkSmsPass').show();
                                		leftTime = new Date();
										leftTime.setTime(leftTime.getTime() + data.leftTime*1000);
                                		showUntilResend();
                               			jQuery('#" . get_class($model) . "_alertSmsSent').fadeIn().delay(30000).fadeOut();
                               		} else if(data.type == 2) { /* Общая ошибка */
                                	    jQuery('#" . get_class($model) . "_ajaxSendSms').hide();
                                		jQuery('#" . get_class($model) . "_actionAnswer').html(data.text).show();
                                	} else if(data.type == 1) { /* Ошибка - SMS уже было отправлено */
                                	    jQuery('#" . get_class($model) . "_ajaxSendSms').hide();
                                		jQuery('#" . get_class($model) . "_checkSmsPass').show();
                               			jQuery('#" . get_class($model) . "_actionAnswer').html(data.text).show();
                               		} else if(data.type == 3) { /* Ошибка при отправке SMS */
                                        jQuery('#" . get_class($model) . "_actionAnswer').html(data.text).show();
                                	} else {
                                	    jQuery('#" . get_class($model) . "_actionAnswer').html('Неизвестная ошибка!').show();
                                	}
                                } ",
			),
		));

		$this->endWidget();
	}

	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                     => get_class($model) . "_checkSmsPass",
		'enableClientValidation' => true,
		'htmlOptions'            => array(
			'class' => "span4" . ($flagHideFormCheckSMSCode ? ' hide' : ''), //прячем если стоит флаг "спрятать" или если СМС-авторизация уже пройдена
		),
		'clientOptions'          => array(
			'validateOnChange' => true,
			'validateOnSubmit' => true,
		),
		'action'                 => $urlCheckSmsPass,
	));
	?>

	<label><?php echo $aParams['enterSMSPassLabel']; ?></label>
	<?php echo $form->textField($model, $aParams['fieldSMSPassName'], array('class' => 'span4')); ?>
	<?php echo $form->error($model, $aParams['fieldSMSPassName']); ?>

	<div class="help-block error<?php echo empty($actionAnswer) ? ' hide' : ''; ?>" id="<?php echo get_class($model); ?>_actionAnswer">
		<?php if (!empty($actionAnswer)) {
			echo $actionAnswer;
		} ?>
	</div>

	<div class="clearfix"></div>

	<?php
	//TODO jQuery('#" . get_class($model) . "_ajaxresendsms').show(); => .hide()
	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'  => 'ajaxSubmit',
		'type'        => 'primary',
		'url'         => $urlCheckSmsPass,
		'size'        => 'small',
		'label'       => 'Отправить пароль',
		'ajaxOptions' => array(
			'dataType' => "json",
			'type'     => "POST",
			'success'  => "function(data)  {
                                	if(data.type == 0) {
                                        jQuery('#" . $aParams['mainContentId'] . "').load(data.text);
                                        jQuery('#" . get_class($model) . "_ajaxResendSms').show();
                                	} else if(data.type == 2) {
                                	    //ругаемся ошибкой
                               			jQuery('#" . get_class($model) . "_actionAnswer').html(data.text).show();
                                	}
                                } ",
		),

	));
	/**
	 * конец формы проверки пароля
	 */
	$this->endWidget();

	?>

</div>

<?php
Yii::app()->clientScript->registerScript('showUntilResend', '
	var leftTime;
	function showUntilResend() {
		iSecondsLeft = Math.floor((leftTime - (new Date()))/1000);
		if(iSecondsLeft < 0) {
			jQuery("#' . get_class($model) . '_btnResend").removeAttr("disabled").removeClass("disabled");
			jQuery("#' . get_class($model) . '_textUntilResend").fadeOut("slow");
			return;
		}
		jQuery("#' . get_class($model) . '_textUntilResend").show("fast");
		iMinutesLeft = Math.floor(iSecondsLeft/60);
		iSecondsLeft -= iMinutesLeft*60;
		if(iSecondsLeft < 10) {
			iSecondsLeft="0"+iSecondsLeft;
		}
		jQuery("#' . get_class($model) . '_untilResend").html(iMinutesLeft+":"+iSecondsLeft);
		setTimeout(showUntilResend,1000);
	}
', CClientScript::POS_HEAD);
if (!$flagHideFormCheckSMSCode) {
	Yii::app()->clientScript->registerScript('showUntilResend2', '
	leftTime = new Date();
	leftTime.setTime(leftTime.getTime() + ' . $smsLeftTime . '*1000);
	showUntilResend();
', CClientScript::POS_LOAD);
}
?>
