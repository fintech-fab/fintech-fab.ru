<?php
/* @var $this FooterLinksController */
/* @var $model FooterLinks */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'footer-links-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'link_name'); ?>
		<?php echo $form->textField($model,'link_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'link_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'link_title'); ?>
		<?php echo $form->textField($model,'link_title',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'link_title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'link_url'); ?>
		<?php echo $form->textField($model,'link_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'link_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'link_content'); ?>
		<?php echo $form->textArea($model,'link_content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'link_content'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->