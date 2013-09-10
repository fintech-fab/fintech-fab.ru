<?php
/**
 * @var AccountResetPasswordForm $model
 * @var DefaultController        $this
 * @var IkTbActiveForm           $form
 * @var integer                  $leftTime
 */

/*
 * Ввести пароль из SMS
 */

$this->pageTitle = Yii::app()->name . " - Восстановить пароль";
?>
<h2 class='pay_legend' style="margin-left: 20px;">Восстановить пароль</h2>
<div class="form" id="activeForm">
	<div class="row">

		<div id="alertSmsSent" class="alert in alert-success hide span10"><?php echo Dictionaries::C_SMS_SUCCESS; ?></div>

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
				->createUrl('/account/ajaxResetPassSendSmsCode', array('bResend' => true)),
		));
		?>

		<div class="well">
			Ваш телефон: +7<?php echo $model->phone; ?>
		</div>

		<?php
		$this->widget('bootstrap.widgets.TbButton', array(
			'id'          => 'btnResend',
			'buttonType'  => 'ajaxSubmit',
			'icon'        => 'icon-refresh',
			'url'         => Yii::app()->createUrl('/account/ajaxResetPassSendSmsCode', array('bResend' => true)),
			'size'        => 'small',
			'label'       => 'Выслать код на телефон повторно',
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
                                	    jQuery('#alertSmsSent').fadeIn();
                                	    jQuery('#actionAnswerResend').hide();
                                	    jQuery('#textUntilResend').show();
                                	    jQuery('#btnResend').addClass('disabled').attr('disabled','disabled');
                                	} else if(data.type == 2) {
                                	    //ругаемся ошибкой
                               			jQuery('#actionAnswerResend').html(data.text).show();
                                	} else {
                               			jQuery('#actionAnswerResend').html(data.text).show();
                                	}
                                	return;
                                } ",
			),

		));
		?>
		<div id="textUntilResend" class="hide">Повторно запросить SMS можно через: <span id="untilResend"></span></div>
		<div id="actionAnswerResend" class="help-block error hide"></div>
		<?php
		$this->endWidget();
		?>

	</div>

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
		'action'                 => Yii::app()->createUrl('/account/resetPassSendSmsPass'),
	));
	?>

	<label>Введите код из SMS:</label>
	<?php echo $form->textField($model, 'smsCode', array('class' => 'span4')); ?>
	<?php echo $form->error($model, 'smsCode'); ?>

	<div class="help-block error<?php echo empty($actionAnswer) ? ' hide' : ''; ?>" id="actionAnswer">
		<?php if (!empty($actionAnswer)) {
			echo $actionAnswer;
		} ?>
	</div>

	<div class="clearfix"></div>

	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'  => 'ajaxSubmit',
		'type'        => 'primary',
		'url'         => Yii::app()->createUrl('/account/resetPassSendSmsPass'),
		'size'        => 'small',
		'label'       => 'Получить пароль',
		'ajaxOptions' => array(
			'dataType' => "json",
			'type'     => "POST",
			'success'  => "function(data)  {
                                	if(data.type == 0) {
                                	    jQuery('#ajaxSendSms').hide();
                                	    jQuery('#ajaxResendSms').hide();
                                	    jQuery('#alertSmsSent').hide();
                                	    jQuery('#checkSmsPass').hide();
                                        jQuery('#smsAuthDone').show();
                                	} else if(data.type == 2) {
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
	?>

	<div class="alert in alert-success span10 hide" id="smsAuthDone">
		SMS с паролем отправлено на телефон. <br><br> <?php echo CHtml::link('Выполнить вход &raquo;', Yii::app()
			->createUrl('/account/login')); ?>
	</div>

</div>

<?php
Yii::app()->clientScript->registerScript('showUntilResend', '
	var leftTime;
	function showUntilResend() {
		iSecondsLeft = Math.floor((leftTime - (new Date()))/1000);
		if(iSecondsLeft < 0) {
			jQuery("#btnResend").removeAttr("disabled").removeClass("disabled");
			jQuery("#textUntilResend").hide();
			return;
		}
		jQuery("#textUntilResend").show("fast");
		iMinutesLeft = Math.floor(iSecondsLeft/60);
		iSecondsLeft -= iMinutesLeft*60;
		if(iSecondsLeft < 10) {
			iSecondsLeft="0"+iSecondsLeft;
		}
		jQuery("#untilResend").html(iMinutesLeft+":"+iSecondsLeft);
		setTimeout(showUntilResend,1000);
	}
', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript('showUntilResend2', '
leftTime = new Date();
leftTime.setTime(leftTime.getTime() + ' . $leftTime . '*1000);
showUntilResend();
', CClientScript::POS_LOAD);
?>
