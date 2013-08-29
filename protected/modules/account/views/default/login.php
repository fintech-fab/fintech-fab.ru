<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Вход';
$this->breadcrumbs = array(
	'Вход',
);
?>
<h2 class='pay_legend'>Вход в личный кабинет</h2>
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
		<?php echo $form->labelEx($model, 'username'); ?>
		<?php echo $form->textField($model, 'username'); ?>
		<?php echo $form->error($model, 'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'password'); ?>
		<?php echo $form->passwordField($model, 'password'); ?>
		<?php echo $form->error($model, 'password'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Вход'); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
