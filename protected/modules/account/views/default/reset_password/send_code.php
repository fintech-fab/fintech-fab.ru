<?php
/**
 * @var AccountResetPasswordForm $model
 * @var DefaultController        $this
 * @var IkTbActiveForm           $form
 */

/*
 * Пользователь вводит телефон, и, если он валидный и есть в базе Кредди, на него отправляется SMS с кодом.
 * Затем идёт перенаправление на форму проверки кода.
 *
 * //Также есть возможность повторно отправить SMS - с новым кодом.
 */

$this->pageTitle = Yii::app()->name . " - Восстановить пароль";
?>

<h2 class='pay_legend' style="margin-left: 20px;">Восстановить пароль</h2>
<div class="form" id="activeForm">
	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'          => 'ajaxSendSms',
		'htmlOptions' => array(
			'class' => "span10",
		),
		'action'      => Yii::app()->createUrl('/account/ajaxResetPassSendSmsCode'),
	));
	?>

	<p class="note">Для получения нового пароля введите свой номер телефона.<br />На указанный номер будет отправлено
		SMS с кодом для подтверждения получения нового пароля.</p>

	<div class="row">
		<?php echo $form->phoneMaskedRow($model, 'phone', array('size' => '15')); ?>
	</div>

	<div class="clearfix"></div>

	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'id'          => 'sendSms',
		'type'        => 'primary',
		'buttonType'  => 'ajaxSubmit',
		'url'         => Yii::app()->createUrl('/account/ajaxResetPassSendSmsCode', array('resend' => 0)),
		'label'       => 'Отправить SMS с кодом',
		'ajaxOptions' => array(
			'dataType' => "json",
			'type'     => "POST",
			'success'  => "function(data) {
									if(data.sms_code == 0) {
										// загрузка следующей формы
										window.location.replace(data.sms_message);
                                	} else if(data.sms_code == 2 && data.sms_message) { // если есть текст ответа, то выводим его
                               			jQuery('#actionAnswer').html(data.sms_message).parent().show();
                                	} else {
                               			jQuery('#actionAnswer').html('Произошла неизвестная ошибка. Обратитесь в горячую линию').parent().show();
                                	}
                                } ",
		),
	));
	?>

	<div class="row hide">
		<div class="help-block error" id="actionAnswer"></div>
	</div>

	<?php
	// конец формы
	$this->endWidget();
	?>

	<div class="clearfix"></div>

	<div class="row">
		<div class="span4"><?php echo CHtml::link('&laquo; Вернуться к форме входа', Yii::app()
				->createUrl('/account/login')); ?>
		</div>
	</div>
</div>
