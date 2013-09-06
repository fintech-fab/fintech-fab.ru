<?php
/**
 * @var AccountResetPasswordForm  $model
 * @var DefaultController         $this
 * @var                           $smsState
 * @var                           $needSmsPass
 * @var                           $act
 * @var IkTbActiveForm            $form
 * @var IkTbActiveForm            $form2
 * @var IkTbActiveForm            $form3
 */

/*
 * Ввести пароль из SMS
 */

$this->pageTitle = Yii::app()->name . " - Напомнить пароль";

// форму отправки кода на телефон прячем, если не ввёден валидный телефон
$hideSmsSendButton = !empty($phoneEntered); //($this->smsState['passSent'] || !$this->smsState['needSmsPass']);

// поле ввода кода и кнопку "далее" прячем, если не спрятана форма отправки КОДА
$flagHideFormCheckSMSCode = !$hideSmsSendButton; //(empty($this->smsState['passSent']) || $flagSmsAuthDone);

$smsPassSentTime = Yii::app()->session['smsCodeSentTime'];

$urlCheckSmsPass = Yii::app()->createUrl('/account/checksmscode');
$urlAjaxSendSMS = Yii::app()->createUrl('/account/ajaxsendsmscode', array('resend' => 0));
$urlAjaxResendSMS = Yii::app()->createUrl('/account/ajaxsendsmscode', array('resend' => 1));

$sessionPhone = !empty(Yii::app()->session['phoneResetPassword']) ? Yii::app()->session['phoneResetPassword'] : '';
echo '<pre>' . "";
CVarDumper::dump($sessionPhone);
echo '</pre>';

$aParams = array(
	'minutesUntil'      => 1,
	'btnResendLabel'    => 'Выслать код на телефон повторно',
	'mainContentId'     => 'main-content',
	'enterSMSPassLabel' => 'Введите код из SMS:',
	'fieldSMSPassName'  => 'code',
	'phone'             => $sessionPhone,
);
?>
<div class="row">
	<div id="<?php echo get_class($model); ?>_alertsmssent" class="alert in alert-success hide span10"><?php echo Dictionaries::C_SMS_SUCCESS; ?></div>
	<?php
	$form3 = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                     => get_class($model) . '_ajaxresendsms',
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
	?>

	<div>
		Ваш телефон: <span id="<?php echo get_class($model); ?>_enteredPhone"><?php echo $aParams['phone']; ?></span>
	</div>

	<?php
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
                                	    sendTime = new Date();
                                	    showUntilResend();
                                	    jQuery('#" . get_class($model) . "_alertsmssent').fadeIn().delay(5000).fadeOut();
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

	));

	?>
	<div id="<?php echo get_class($model); ?>_textUntilResend">Повторно запросить SMS можно через:
		<span id="<?php echo get_class($model); ?>_untilResend">1:00</span>

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
			'id'          => get_class($model) . '_ajaxsendsms',
			'htmlOptions' => array(
				'class' => "span10",
			),
			'action'      => $urlAjaxSendSMS,
		));

		echo $form2->phoneMaskedRow($model, 'phone', array('size' => '15'));
		?>
		<br>

		<?php
		$this->widget('bootstrap.widgets.TbButton', array(
			'id'          => get_class($model) . '_sendSms',
			'type'        => 'primary',
			'buttonType'  => 'ajaxSubmit',
			'url'         => $urlAjaxSendSMS,
			'label'       => 'Отправить SMS с кодом',
			'ajaxOptions' => array(
				'dataType' => "json",
				'type'     => "POST",
				'success'  => "function(data) {
									jQuery('#" . get_class($model) . "_actionAnswer').html(data.text).hide();
									var enteredPhone = jQuery('#" . get_class($model) . "_phone').val();
                                    jQuery('#" . get_class($model) . "_enteredPhone').html(enteredPhone);
                                	if(data.type == 0) { /* SMS успешно отправлено */
                                	    jQuery('#" . get_class($model) . "_enteredPhone').val(enteredPhone);
                                	    jQuery('#" . get_class($model) . "_ajaxsendsms').hide();
                                	    jQuery('#" . get_class($model) . "_textUntilResend').show();
                                	    jQuery('#" . get_class($model) . "_ajaxresendsms').show();
                                		jQuery('#" . get_class($model) . "_checksmspass').show();
                                		sendTime = new Date();
                                		showUntilResend();
                               			jQuery('#" . get_class($model) . "_alertsmssent').fadeIn().delay(3000).fadeOut();
                               		} else if(data.type == 2) { /* Общая ошибка */
                                	    jQuery('#" . get_class($model) . "_ajaxsendsms').hide();
                                		jQuery('#" . get_class($model) . "_actionAnswer').html(data.text).show();
                                	} else if(data.type == 1) { /* Ошибка - SMS уже было отправлено */
                                	    jQuery('#" . get_class($model) . "_ajaxsendsms').hide();
                                		jQuery('#" . get_class($model) . "_checksmspass').show();
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
		'id'                     => get_class($model) . "_checksmspass",
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
	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'  => 'ajaxSubmit',
		'type'        => 'primary',
		'url'         => $urlCheckSmsPass,
		'size'        => 'small',
		'label'       => 'Получить пароль',
		'ajaxOptions' => array(
			'dataType' => "json",
			'type'     => "POST",
			'success'  => "function(data)  {
                                	if(data.type == 0) {
                                        jQuery('#" . $aParams['mainContentId'] . "').load(data.text);
                                        jQuery('#" . get_class($model) . "_ajaxresendsms').show();
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
	var sendTime;
	var iMinutesUntil = ' . $aParams['minutesUntil'] . ';
	function showUntilResend() {
		iSecondsLeft = Math.floor((sendTime - (new Date())) / 1000 + iMinutesUntil*60);
		if(iSecondsLeft < 0) {
			jQuery("#' . get_class($model) . '_btnResend").removeAttr("disabled").removeClass("disabled");
			jQuery("#' . get_class($model) . '_textUntilResend").fadeOut("slow");
			return;
		}
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
	sendTime = new Date();
	sendTime.setTime((' . $smsPassSentTime . '+70)*1000);
	showUntilResend();
', CClientScript::POS_LOAD);
}
?>
