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
		'type'        => 'horizontal',
		'htmlOptions' => array(
			'class'        => "span10",
			'autocomplete' => 'off',
		),
		'action'      => Yii::app()->createUrl('/account/resetPassword'),
	));
	?>

	<p class="note">Для получения нового пароля введите свой номер телефона, серию и номер паспорта.<br />На указанный
		номер телефона будет отправлено SMS с кодом для подтверждения получения нового пароля.</p>

	<div class="row">
		<div class="span5">
			<?= $form->phoneMaskedRow($model, 'phone', array('size' => '15')); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="row">
			<div class="span3">
				<?= $form->labelEx($model, 'passport_number', array('class' => 'control-label')); ?>
				<div class="controls"><?= $form->maskedTextField($model, 'passport_series', '9999', array('style' => 'width: 40px;', 'size' => '4', 'maxlength' => '4')); ?></div>
			</div>
			<div class="span2">
				<span>/</span>
				<?= $form->maskedTextField($model, 'passport_number', '999999', array('style' => 'width: 60px;', 'size' => '6', 'maxlength' => '6')); ?>
			</div>
		</div>
		<div class="row">
			<div class="span5">
				<div style="margin-left: 180px;">
					<?= $form->error($model, 'passport_series'); ?>
					<?= $form->error($model, 'passport_number'); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>

	<div class="row">
		<div class="span5">
			<div class="form-actions">
				<?php
				$this->widget('bootstrap.widgets.TbButton', array(
					'type'       => 'primary',
					'size'       => 'small',
					'buttonType' => 'submit',
					'label'      => 'Отправить SMS с кодом',
				));
				?>
			</div>
		</div>
	</div>

	<div class="row hide">
		<div class="help-block error" id="actionAnswer"></div>
	</div>

	<?php
	// конец формы
	$this->endWidget();
	?>

	<div class="clearfix"></div>

	<div class="row">
		<div class="span4"><?=
			CHtml::link('&laquo; Вернуться к форме входа', Yii::app()
				->createUrl('/account/login')); ?>
		</div>
	</div>
</div>
