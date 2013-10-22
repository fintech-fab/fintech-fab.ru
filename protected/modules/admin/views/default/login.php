<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Вход в личный кабинет';

?>
<h1>Вход в систему</h1>
<div class="form">
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'                     => 'login-form',
		'enableClientValidation' => true,
		'clientOptions'          => array(
			'validateOnSubmit' => true,
		),
	)); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span> , являются обязательными.</p>

	<div class="row">
		<?= $form->labelEx($model, 'username'); ?>
		<?= $form->textField($model, 'username'); ?>
		<?= $form->error($model, 'username'); ?>
	</div>

	<div class="row">
		<?= $form->labelEx($model, 'password'); ?>
		<?= $form->passwordField($model, 'password', array('autocomplete' => 'off')); ?>
		<?= $form->error($model, 'password'); ?>
	</div>

	<div class="row rememberMe">
		<?= $form->checkBox($model, 'rememberMe'); ?>
		<?= $form->label($model, 'rememberMe'); ?>
		<?= $form->error($model, 'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?= CHtml::submitButton('Вход'); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
