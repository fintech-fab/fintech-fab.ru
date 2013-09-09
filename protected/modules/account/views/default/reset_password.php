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

$this->pageTitle = Yii::app()->name . " - Восстановить пароль";

$flagSmsAuthDone = !empty(Yii::app()->session['smsAuthDone']);

// форму отправки кода на телефон прячем, если не ввёден валидный телефон
$hideSmsSendButton = !empty($phoneEntered) || $flagSmsAuthDone; //($this->smsState['passSent'] || !$this->smsState['needSmsPass']);

// поле ввода кода и кнопку "далее" прячем, если не спрятана форма отправки КОДА либо
$flagHideFormCheckSMSCode = !$hideSmsSendButton || $flagSmsAuthDone; //(empty($this->smsState['passSent']) || $flagSmsAuthDone);

$smsPassLeftTime = Yii::app()->session['smsCodeLeftTime'];

$urlCheckSmsPass = Yii::app()->createUrl('/account/checkSmsCode');
$urlAjaxSendSMS = Yii::app()->createUrl('/account/ajaxSendSmsCode', array('resend' => 0));
$urlAjaxResendSMS = Yii::app()->createUrl('/account/ajaxSendSmsCode', array('resend' => 1));

$sessionPhone = !empty(Yii::app()->session['phoneResetPassword']) ? Yii::app()->session['phoneResetPassword'] : '';

$aParams = array(
	'minutesUntil'      => SiteParams::API_MINUTES_UNTIL_RESEND,
	'btnResendLabel'    => 'Выслать код на телефон повторно',
	'mainContentId'     => 'main-content',
	'enterSMSPassLabel' => 'Введите код из SMS:',
	'fieldSMSPassName'  => 'smsCode',
	'phone'             => $sessionPhone,
);
?>
<h2 class='pay_legend' style="margin-left: 20px;">Восстановить пароль</h2>
<div class="form">
	<div class="row">

		<div id="<?php echo get_class($model); ?>_alertSmsSent" class="alert in alert-success hide span10"><?php echo Dictionaries::C_SMS_SUCCESS; ?></div>
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
		?>

		<div class="well">
			Ваш телефон:
			<span id="<?php echo get_class($model); ?>_enteredPhone">+7<?php echo $aParams['phone']; ?></span>
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
				'type'     => "POST", //TODO: fadein убрать здесь и ниже
				'success'  => "function(data)  {
                               		if(!data) {
                               		    return;
                               		}
                                	if(data.type == 0) {
                                	    leftTime = new Date();
										leftTime.setTime(leftTime.getTime() + data.leftTime*1000);
										showUntilResend();
                                	    jQuery('#" . get_class($model) . "_alertSmsSent').fadeIn().delay(5000).fadeOut();
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
		<div id="<?php echo get_class($model); ?>_textUntilResend" class="hide">Повторно запросить SMS можно через:
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
				'id'          => get_class($model) . '_ajaxSendSms',
				'htmlOptions' => array(
					'class' => "span10",
				),
				'action'      => $urlAjaxSendSMS,
			));
			?>

			<p class="note">Для сброса пароля введите свой номер телефона</p>

			<?php echo $form2->phoneMaskedRow($model, 'phone', array('size' => '15')); ?>
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
									jQuery('#" . get_class($model) . "_actionAnswerSend').html(data.text).hide();
									var enteredPhone = jQuery('#" . get_class($model) . "_phone').val().replace(/\s/g, '');
                                	if(data.type == 0) { /* SMS успешно отправлено */
                                	    jQuery('#" . get_class($model) . "_enteredPhone').html(enteredPhone);
                                	    jQuery('#" . get_class($model) . "_ajaxSendSms').hide();
                                	    jQuery('#" . get_class($model) . "_textUntilResend').show();
                                	    jQuery('#" . get_class($model) . "_ajaxResendSms').show();
                                		jQuery('#" . get_class($model) . "_checkSmsPass').show();
                                		leftTime = new Date();
										leftTime.setTime(leftTime.getTime() + data.leftTime*1000);
                                		showUntilResend();
                               			jQuery('#" . get_class($model) . "_alertSmsSent').fadeIn().delay(3000).fadeOut();
                               		} else if(data.type == 2) { /* Общая ошибка */
                                		jQuery('#" . get_class($model) . "_actionAnswerSend').html(data.text).show();
                                	} else if(data.type == 1) { /* Ошибка - SMS уже было отправлено */
                                	    jQuery('#" . get_class($model) . "_ajaxSendSms').hide();
                                		jQuery('#" . get_class($model) . "_checkSmsPass').show();
                               			jQuery('#" . get_class($model) . "_actionAnswerSend').html(data.text).show();
                               		} else if(data.type == 3) { /* Ошибка при отправке SMS */
                                        jQuery('#" . get_class($model) . "_actionAnswerSend').html(data.text).show();
                                	} else {
                                	    jQuery('#" . get_class($model) . "_actionAnswerSend').html('Неизвестная ошибка!').show();
                                	}
                                } ",
				),
			));
			?>
			<div class="help-block error" id="<?php echo get_class($model); ?>_actionAnswerSend"></div>
			<?php

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
                                	    jQuery('#" . get_class($model) . "_ajaxSendSms').hide();
                                	    jQuery('#" . get_class($model) . "_ajaxResendSms').hide();
                                	    jQuery('#" . get_class($model) . "_alertSmsSent').hide();
                                	    jQuery('#" . get_class($model) . "_checkSmsPass').hide();
                                        jQuery('#" . get_class($model) . "_smsAuthDone').show();
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

		<div class="alert in alert-success span10<?php echo empty($flagSmsAuthDone) ? ' hide' : ''; ?>" id="<?php echo get_class($model); ?>_smsAuthDone">
			SMS с паролем отправлено на телефон. <br><br> <?php echo CHtml::link('Выполнить вход &raquo;', Yii::app()
				->createUrl('/account/login')); ?>
		</div>

	</div>
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
	leftTime.setTime(leftTime.getTime() + ' . $smsPassLeftTime . '*1000);
	showUntilResend();
', CClientScript::POS_LOAD);
}
?>
