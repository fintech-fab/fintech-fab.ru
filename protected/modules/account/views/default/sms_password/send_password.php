<?php
/**
 * @var SMSPasswordForm   $model
 * @var DefaultController $this
 * @var IkTbActiveForm    $form
 */

/*
 * Пользователь вводит телефон, и, если он валидный и есть в базе Кредди, на него отправляется SMS с кодом.
 * Затем идёт перенаправление на форму проверки кода.
 *
 * //Также есть возможность повторно отправить SMS - с новым кодом.
 */

$this->pageTitle = Yii::app()->name;
?>
<div class="alert in alert-block alert-warning span7">
	Для доступа к закрытым данным и действиям требуется авторизоваться по одноразовому SMS-паролю
</div>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'          => 'smsPassAuth',
	'htmlOptions' => array(
		'class' => "span7",
	),
	'action'      => Yii::app()->createUrl('/account/sendSmsPass'),
));
?>
<div class="center">
	<?php

	$model->sendSmsPassword = 1;
	echo $form->hiddenField($model, 'sendSmsPassword');

	$this->widget('bootstrap.widgets.TbButton', array(
		'id'         => 'sendSms',
		'type'       => 'primary',
		'size'       => 'small',
		'buttonType' => 'submit',
		'label'      => 'Отправить на +7' . Yii::app()->user->getId() . ' SMS с паролем',
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
