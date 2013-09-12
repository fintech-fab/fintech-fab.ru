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

$this->pageTitle = Yii::app()->name . " - Восстановление пароля";
?>

<h2 class='pay_legend' style="margin-left: 20px;">Восстановить пароль</h2>
<div class="form" id="activeForm">
	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'          => 'resetPasswordForm',
		'htmlOptions' => array(
			'class' => "span10",
		),
		'action'      => Yii::app()->createUrl('/account/resetPassword'),
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
		'type'       => 'primary',
		'size'       => 'small',
		'buttonType' => 'submit',
		'label'      => 'Отправить SMS с кодом',
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
