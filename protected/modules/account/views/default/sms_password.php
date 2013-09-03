<?php
/**
 * @var DefaultController $this
 * @var SMSPasswordForm   $form
 * @var                   $passForm
 * @var                   $needSmsPass
 * @var                   $act
 */

/*
 * Ввести пароль из SMS
 */
?>

<?php

// поле ввода кода и кнопку "далее" прячем, если не отправлено смс или исчерпаны все попытки ввода

$hideSmsSendButton = ($this->smsState['sent'] || !$this->smsState['needSmsPass']);
$flagSmsAuthDone = $this->smsState['smsAuthDone'];

$flagHideForm = (empty($this->smsState['sent']) || $flagSmsAuthDone);
?>

<div class="span10">
	<?php
	// если SMS на телефон ещё не отсылалось
	if (empty($hideSmsSendButton)) {
		?>
		<div id="send_sms">
			<?php
			$form2 = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
				'id'                     => get_class($passForm) . '_smsPassword',
				'enableClientValidation' => true,
				'clientOptions'          => array(
					'validateOnChange' => true,
					'validateOnSubmit' => true,
				),
				'action'                 => Yii::app()->createUrl('/account/ajaxsendsms'),
			));

			?>
			<? $this->widget('bootstrap.widgets.TbButton', array(
				'id'          => 'sendSms',
				'buttonType'  => 'ajaxSubmit',
				'url'         => Yii::app()->createUrl('/account/ajaxsendsms'),
				'size'        => 'small',
				'label'       => 'Отправить на +7' . Yii::app()->clientForm->getSessionPhone() . ' SMS с паролем',
				'ajaxOptions' => array(
					'dataType' => "json",
					'type'     => "POST",
					'success'  => "function(data)
                                {
									$('#actionAnswer').html(data.text).hide();
                                	if(data.type==0)
                                	{
                                	    $('#send_sms').hide();
                                		$('#sms_pass_row').show();
                                		$('.form-actions').show();
                               			$('#alertsmssent').fadeIn();
                                	} else if(data.type==1) {
                                	    $('#send_sms').hide();
                                		$('#sms_pass_row').show();
                                		$('.form-actions').show();
                               			$('#actionAnswer').html(data.text).show();
                                	}
                                	else if(data.type==2)
                                	{
                                	    $('#send_sms').hide();
                                		$('#actionAnswer').html(data.text).show();
                               		}
                               		else if(data.type==3)
                                	{
                                        $('#actionAnswer').html(data.text).show();
                                	}
                                	else
                                	{
                                        $('#actionAnswer').html('Неизвестная ошибка!').show();
                                	}
                                } ",
				),
			)); ?>
			<?

			$this->endWidget();
			?>
		</div>
	<?php } ?>
</div>

<?php

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                     => get_class($passForm),
	'enableClientValidation' => true,
	'clientOptions'          => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
	),
	'action'                 => Yii::app()->createUrl('/account/checksmspass'),
));

?>

<div class="span10<?php if ($flagHideForm) {
	//прячем если стоит флаг "спрятать" или если СМС-авторизация уже пройдена
	echo ' hide';
} ?>" id="sms_pass_row">
	<?php Yii::app()->user->setFlash('success', Dictionaries::C_SMS_SUCCESS); ?>
	<?php $this->widget('bootstrap.widgets.TbAlert', array(
		'block'       => true,
		'fade'        => false,
		'closeText'   => '&times;',
		'htmlOptions' => array('style' => 'display:none;', 'id' => 'alertsmssent'),
	)); ?>
	<label>Введите код из SMS:</label>
	<?php echo $form->textField($passForm, 'smsPassword', array('class' => 'span4')); ?>
	<?php echo $form->error($passForm, 'smsPassword'); ?>
</div>        <span class="span10 help-block error<?php if (empty($actionAnswer)) {
	echo " hide";
} ?>" id="actionAnswer"></span>


<div class="clearfix"></div>
<div class="row span11">
	<div class="form-actions<?php if ($flagHideForm) {
		//прячем если стоит флаг "спрятать" или если СМС-авторизация уже пройдена
		echo ' hide';
	} ?>">
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
				'success'  => "function(data)
                                {
                                	if(data.type==0)
                                	{
                                        $('#main-content').load(data.text);
                                	}
                                	else if(data.type==2)
                                	{
                                	    //ругаемся ошибкой
                               			$('#actionAnswer').html(data.text).show();
                                	}
                                	return;
                                } ",
			),

		)); ?>
	</div>
</div>
<?

$this->endWidget();
?>
