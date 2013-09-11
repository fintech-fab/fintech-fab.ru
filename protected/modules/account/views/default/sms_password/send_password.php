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
<h5>Для доступа к закрытым данным требуется авторизоваться по одноразовому СМС-паролю </h5>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'          => 'smsPassAuth',
	'htmlOptions' => array(
		'class' => "span10",
	),
	'action'      => Yii::app()->createUrl('/account/smsPassAuth'),
));
?>
<div class="row">
	<?php

	$model->sendSmsPassword = 1;
	echo $form->hiddenField($model, 'sendSmsPassword');

	$this->widget('bootstrap.widgets.TbButton', array(
		'id'         => 'sendSms',
		'type'       => 'primary',
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
