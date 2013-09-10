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

$this->pageTitle = Yii::app()->name;
?>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'          => 'ajaxSendSms',
	'htmlOptions' => array(
		'class' => "span10",
	),
	'action'      => Yii::app()->createUrl('/account/ajaxSendSms'),
));
?>
<div class="row">
	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'id'          => 'sendSms',
		'type'        => 'primary',
		'buttonType'  => 'ajaxSubmit',
		'url'         => Yii::app()->createUrl('/account/ajaxSendSms'),
		'label'       => 'Отправить на +7' . Yii::app()->user->getId() . ' SMS с паролем',
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
</div>

<div class="row hide">
	<div class="help-block error" id="actionAnswer"></div>
</div>

<?php
// конец формы
$this->endWidget();
?>
