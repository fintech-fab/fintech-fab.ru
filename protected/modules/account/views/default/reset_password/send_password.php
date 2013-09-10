<?php
/**
 * @var AccountResetPasswordForm $model
 * @var DefaultController        $this
 * @var IkTbActiveForm           $form
 */

/*
 * Ввести пароль из SMS
 */

$this->pageTitle = Yii::app()->name . " - Восстановить пароль";
?>
<h2 class='pay_legend' style="margin-left: 20px;">Восстановить пароль</h2>
<div class="form" id="activeForm">
	<div class="row">

		<div id="alertSmsSent" class="alert in alert-success span10"><?php echo Dictionaries::C_SMS_SUCCESS; ?></div>

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
				->createUrl('/account/ajaxResetPassSendSmsCode', array('resend' => 1)),
		));
		?>

		<div class="well">
			Ваш телефон: +7<?php echo Yii::app()->adminKreddyApi->getResetPassPhone(); ?>
		</div>

		<?php
		$this->widget('bootstrap.widgets.TbButton', array(
			'id'          => 'btnResend',
			'buttonType'  => 'ajaxSubmit',
			'icon'        => 'icon-refresh',
			'url'         => Yii::app()->createUrl('/account/ajaxResetPassSendSmsCode', array('resend' => 1)),
			'size'        => 'small',
			'label'       => 'Выслать код на телефон повторно',
			'disabled'    => true,
			'ajaxOptions' => array(
				'dataType' => "json",
				'type'     => "POST",
				'success'  => "function(data)  {
                                	if(data.sms_code == 0) { // если успешно отправлено (код - 0)
                                	    // показываем сообщение, что SMS отправлено
                                	    jQuery('#alertSmsSent').fadeOut(5000).fadeIn();

                                	    // запускаем счётчик оставшихся секунд
                                	    leftTime = new Date();
										leftTime.setTime(leftTime.getTime()+data.sms_left_time*1000);
										showUntilResend();

										// блокируем кнопку повторно отправки
                                	    jQuery('#btnResend').addClass('disabled').attr('disabled','disabled');
                                	} else if(data.sms_code == 2) {
                                	    //ругаемся ошибкой
                               			jQuery('#actionAnswerResend').html(data.sms_message).parent().show();
                                	} else {
                               			jQuery('#actionAnswerResend').html('Произошла неизвестная ошибка. Обратитесь в горячую линию').parent().show();
                                	}
                                	return;
                                } ",
			),

		));
		?>
		<div id="textUntilResend" class="hide">Повторно запросить SMS можно через: <span id="untilResend"></span></div>
		<div id="actionAnswerResend" class="help-block error"></div>
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

	<div class="help-block error hide" id="actionAnswer"></div>

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
                                	if(data.sms_code == 0) {
										// загрузка следующей формы
										window.location.replace(data.sms_message);
                                	} else if(data.sms_code == 2 && data.sms_message) { // если есть текст ответа, то выводим его
                               			jQuery('#actionAnswer').html(data.sms_message).show();
                                	} else {
                               			jQuery('#actionAnswer').html('Произошла неизвестная ошибка. Обратитесь в горячую линию').show();
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
$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/sms_countdown.js';
Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript('showUntilResend2', '
	leftTime = new Date();
	leftTime.setTime(leftTime.getTime() + ' . Yii::app()->adminKreddyApi->getResetPassSmsCodeLeftTime() . '*1000);
	showUntilResend();'
	, CClientScript::POS_LOAD);
?>
