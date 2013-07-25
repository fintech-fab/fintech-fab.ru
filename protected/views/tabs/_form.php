<?php
/* @var $this TabsController */
/* @var $model Tabs */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tabs-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'tab_name'); ?>
		<?php echo $form->textField($model,'tab_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'tab_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tab_title'); ?>
		<?php echo $form->textField($model,'tab_title',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'tab_title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tab_content'); ?>
		<?php echo $form->textArea($model,'tab_content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tab_content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tab_order'); ?>
		<?php echo $form->textField($model,'tab_order'); ?>
		<?php echo $form->error($model,'tab_order'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->