<?php
/* @var $this TabsController */
/* @var $model Tabs */
/* @var $form CActiveForm */

Yii::import('ext.imperavi-redactor-widget.ImperaviRedactorWidget');
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tabs-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span> , являются обязательными.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'tab_name'); ?>
		<?php echo $form->textField($model,'tab_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'tab_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tab_title'); ?>
		<?php echo $form->textField($model,'tab_title',array('size'=>20,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'tab_title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tab_order'); ?>
		<?php echo $form->textField($model,'tab_order'); ?>
		<?php echo $form->error($model,'tab_order'); ?>
	</div>

	<?php
	$this->widget('ImperaviRedactorWidget', array(
		// You can either use it for model attribute
		'model' => $model,
		'attribute' => 'tab_content',

		// or just for input field
		'name' => 'tab_content',

		// Some options, see http://imperavi.com/redactor/docs/
		'options' => array(
			'lang' => 'ru',
			'toolbar' => 'classic',

			'buttons'=>array('html', '|', 'formatting', '|', 'bold', 'italic', 'deleted', 'underline','|','alignleft', 'aligncenter', 'alignright', 'justify','|',
				'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
				'image', 'file', 'table', 'link', '|',
				'fontcolor', 'backcolor', '|', 'alignment', '|', 'horizontalrule'),
			'iframe' => true,
			'imageUpload' => Yii::app()->createUrl("pages/imageUpload"),
			'imageUploadErrorCallback'=> 'js: function(json) { alert(json.error); }',
			'uploadFields'=>array(
				Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken,
			),
		),
		'htmlOptions' => array('style'=>"width: 100%; height: 400px;"),
	));
	?>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->