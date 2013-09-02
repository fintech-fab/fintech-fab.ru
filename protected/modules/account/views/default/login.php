<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form IkTbActiveForm */

$this->pageTitle = Yii::app()->name . ' - Вход';
$this->breadcrumbs = array(
	'Вход',
);
?>
<h2 class='pay_legend'>Вход в личный кабинет</h2>
<div class="form">
	<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                   => 'login-form',
		'enableAjaxValidation' => false,
		'clientOptions'        => array(
			'validateOnSubmit' => false,
		),
	)); ?>

	<p class="note">Для входа в личный кабинет введите свой номер телефона и пароль</p>

	<div class="row">
		<?= $form->phoneMaskedRow($model, 'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'password'); ?>
		<?php echo $form->passwordField($model, 'password'); ?>
		<?php echo $form->error($model, 'password'); ?>
	</div>

	<div class="row buttons">
		<? $this->widget('bootstrap.widgets.TbButton', array(
			'id'         => 'submitButton',
			'buttonType' => 'submit',
			'type'       => 'primary',
			'label'      => 'Войти',
		)); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
