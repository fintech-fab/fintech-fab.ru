<?php
/* @var $this PagesController */
/* @var $model Pages */
/* @var $form CActiveForm */

Yii::import('ext.imperavi-redactor-widget.ImperaviRedactorWidget');

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pages-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'page_name'); ?>
		<?php echo $form->textField($model,'page_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'page_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'page_title'); ?>
		<?php echo $form->textField($model,'page_title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'page_title'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'page_content'); ?>
		<?php //echo $form->textArea($model,'page_content',array('rows'=>6, 'cols'=>50)); ?>
		<?php //echo $form->error($model,'page_content'); ?>
		<?php
		$this->widget('ImperaviRedactorWidget', array(
		// You can either use it for model attribute
		'model' => $model,
		'attribute' => 'page_content',

		// or just for input field
		'name' => 'page_content',

		// Some options, see http://imperavi.com/redactor/docs/
		'options' => array(
		'lang' => 'ru',
		'toolbar' => 'classic',
		'iframe' => true,
		'imageUpload' => '/file_upload.php',
		//'css' => 'wym.css',
		),
		));
		?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->