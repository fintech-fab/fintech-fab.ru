<?php

/**
 * @var DefaultController $this
 * @var SMSPasswordForm   $passForm
 * @var                   $smsState
 * @var                   $needSmsPass
 * @var                   $act
 * @var IkTbActiveForm    $form
 */

/*
 * Ввести пароль из SMS
 */

$this->pageTitle = Yii::app()->name;

$hideSmsSendButton = ($this->smsState['passSent'] || !$this->smsState['needSmsPass']);
$flagSmsAuthDone = $this->smsState['smsAuthDone'];

// поле ввода кода и кнопку "далее" прячем, если не отправлено смс или авторизация уже выполнена
$flagHideFormCheckSMSCode = (empty($smsState['passSent']) || $flagSmsAuthDone);
?>

<div class="row">
	<?php
	// если SMS на телефон ещё не отсылалось
	if (empty($hideSmsSendButton)) {
		$form2 = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
			'id'                     => get_class($passForm) . '_ajaxsendsms',
			'enableClientValidation' => true,
			'clientOptions'          => array(
				'validateOnChange' => true,
				'validateOnSubmit' => true,
			),
			'htmlOptions'            => array(
				'class' => "span10",
			),
			'action'                 => Yii::app()->createUrl('/account/ajaxsendsms', array('act' => $act)),
		));

		$this->widget('bootstrap.widgets.TbButton', array(
			'id'          => 'sendSms',
			'buttonType'  => 'ajaxSubmit',
			'url'         => Yii::app()->createUrl('/account/ajaxsendsms', array('act' => $act)),
			'size'        => 'small',
			'label'       => 'Отправить на +7' . Yii::app()->user->getId() . ' SMS с паролем',
			'ajaxOptions' => array(
				'dataType' => "json",
				'type'     => "POST",
				'success'  => "function(data) {
									jQuery('#actionAnswer').html(data.text).hide();
                                	if(data.type == 0) { /* SMS успешно отправлено */
                                	    jQuery('#" . get_class($passForm) . "_ajaxsendsms').hide();
                                	    jQuery('#textUntilResend').show();
                                	    jQuery('#" . get_class($passForm) . "_ajaxresendsms').show();
                                		jQuery('#" . get_class($passForm) . "_checksmspass').show();
                                		sendTime = new Date();
                                		showUntilResend();
                               			jQuery('#alertsmssent').fadeIn().delay(5000).fadeOut();
                               		} else if(data.type == 2) { /* Общая ошибка */
                                	    jQuery('#" . get_class($passForm) . "_ajaxsendsms').hide();
                                		jQuery('#actionAnswer').html(data.text).show();
                                	} else if(data.type == 1) { /* Ошибка - SMS уже было отправлено */
                                	    jQuery('#" . get_class($passForm) . "_ajaxsendsms').hide();
                                		jQuery('#" . get_class($passForm) . "_checksmspass').show();
                               			jQuery('#actionAnswer').html(data.text).show();
                               		} else if(data.type == 3) { /* Ошибка при отправке SMS */
                                        jQuery('#actionAnswer').html(data.text).show();
                                	} else {
                                	    jQuery('#actionAnswer').html('Неизвестная ошибка!').show();
                                	}
                                } ",
			),
		));

		$this->endWidget();
	}
	?>

	<div id="alertsmssent" class="alert in alert-success hide span7"><?php echo Dictionaries::C_SMS_SUCCESS; ?></div>
	<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                     => get_class($passForm) . "_checksmspass",
		'enableClientValidation' => true,
		'htmlOptions'            => array(
			'class' => "span4" . ($flagHideFormCheckSMSCode ? ' hide' : ''), //прячем если стоит флаг "спрятать" или если СМС-авторизация уже пройдена
		),
		'clientOptions'          => array(
			'validateOnChange' => true,
			'validateOnSubmit' => true,
		),
		'action'                 => Yii::app()->createUrl('/account/checksmspass'),
	));
	?>

	<label>Введите пароль из SMS:</label>
	<?php echo $form->textField($passForm, 'smsPassword', array('class' => 'span4')); ?>
	<?php echo $form->error($passForm, 'smsPassword'); ?>

	<div class="help-block error<?= empty($actionAnswer) ? ' hide' : ''; ?>" id="actionAnswer">
		<?php if (!empty($actionAnswer)) {
			echo $actionAnswer;
		} ?>
	</div>

	<div class="clearfix"></div>
	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'  => 'ajaxSubmit',
		'type'        => 'primary',
		'url'         => Yii::app()->createUrl('/account/checksmspass', array('act' => $act)),
		'size'        => 'small',
		'label'       => 'Отправить пароль',
		'ajaxOptions' => array(
			'dataType' => "json",
			'type'     => "POST",
			'success'  => "function(data)  {
                                	if(data.type==0) {
                                        jQuery('#main-content').load(data.text);
                                        jQuery('" . get_class($passForm) . "_ajaxresendsms').show();
                                	} else if(data.type==2) {
                                	    //ругаемся ошибкой
                               			jQuery('#actionAnswer').html(data.text).show();
                                	}
                                } ",
		),

	));
	/**
	 * конец формы проверки пароля
	 */
	$this->endWidget();

	$form3 = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                     => get_class($passForm) . '_ajaxresendsms',
		'enableClientValidation' => true,
		'clientOptions'          => array(
			'validateOnChange' => true,
			'validateOnSubmit' => true,
		),
		'htmlOptions'            => array(
			'class' => "span4" . ($flagHideFormCheckSMSCode ? ' hide' : ''),
		),
		'action'                 => Yii::app()->createUrl('/account/ajaxsendsms', array('resend' => 1)),
	));
	$this->widget('bootstrap.widgets.TbButton', array(
		'id'          => 'btnResend',
		'buttonType'  => 'ajaxSubmit',
		'icon'        => 'icon-refresh',
		'url'         => Yii::app()->createUrl('/account/ajaxsendsms', array('resend' => 1)),
		'size'        => 'small',
		'label'       => 'Выслать пароль на телефон повторно', //TODO: подумать над текстом
		'disabled'    => true,
		'ajaxOptions' => array(
			'dataType' => "json",
			'type'     => "POST",
			'success'  => "function(data)  {
                               		if(!data) {
                               		    return;
                               		}
                                	if(data.type==0) {
                                	    sendTime = new Date();
                                	    showUntilResend();
                                	    jQuery('#alertsmssent').fadeIn().delay(5000).fadeOut();
                                	    jQuery('#actionAnswerResend').hide();
                                	    jQuery('#textUntilResend').show();
                                	    jQuery('#btnResend').addClass('disabled').attr('disabled','disabled');
                                	} else if(data.type==2) {
                                	    //ругаемся ошибкой
                               			jQuery('#actionAnswerResend').html(data.text).show();
                                	} else {
                               			jQuery('#actionAnswerResend').html(data.text).show();
                                	}
                                	return;
                                } ",
		),

	));?>
	<div id="textUntilResend">Повторно запросить SMS можно через: <span id="untilResend">1:00</span>

		<div class="help-block error hide" id="actionAnswerResend"></div>
	</div>
	<?php
	$this->endWidget();
	?>

</div>

<?php
Yii::app()->clientScript->registerScript('showUntilResend', '
	var iSecondsLeft, iMinutesLeft;
	var sendTime;
	function showUntilResend() {
		iSecondsLeft = Math.floor((sendTime - (new Date())) / 1000 + 1*60);
		if(iSecondsLeft < 0) {
			jQuery("#btnResend").removeAttr("disabled").removeClass("disabled");
			jQuery("#textUntilResend").fadeOut("slow");
			return;
		}
		iMinutesLeft = Math.floor(iSecondsLeft/60);
		iSecondsLeft -= iMinutesLeft*60;
		if(iSecondsLeft < 10) {
			iSecondsLeft="0"+iSecondsLeft;
		}
		jQuery("#untilResend").html(iMinutesLeft+":"+iSecondsLeft);
		setTimeout(showUntilResend,1000);
	}
', CClientScript::POS_HEAD);
?>
