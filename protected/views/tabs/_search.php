<?php
/* @var $this TabsController */
/* @var $model Tabs */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'tab_id'); ?>
		<?php echo $form->textField($model,'tab_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tab_name'); ?>
		<?php echo $form->textField($model,'tab_name',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tab_title'); ?>
		<?php echo $form->textField($model,'tab_title',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tab_content'); ?>
		<?php echo $form->textArea($model,'tab_content',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tab_order'); ?>
		<?php echo $form->textField($model,'tab_order'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->