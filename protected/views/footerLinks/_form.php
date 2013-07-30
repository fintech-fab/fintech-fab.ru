<?php
/* @var $this FooterLinksController */
/* @var $model FooterLinks */
/* @var $form CActiveForm */

Yii::import('ext.imperavi-redactor-widget.ImperaviRedactorWidget');
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'footer-links-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span> , являются обязательными.</p>

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
		<?php

		Yii::app()->user->setFlash('info', 'Поддерживается 2 формата URL: абсолютный (<strong>http://site.ru</strong>) и относительный (<strong>/page/subpage</strong>).');

		$this->widget('bootstrap.widgets.TbAlert', array(
			'block'=>false, // display a larger alert block?
			'fade'=>true, // use transitions?
			'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
			'alerts'=>array( // configurations per alert type
				'info'=>array('block'=>false, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
			),
		)); ?>
	</div>

	<div class="row">
		<?php
		$this->widget('ImperaviRedactorWidget', array(
			// You can either use it for model attribute
			'model' => $model,
			'attribute' => 'link_content',

			// or just for input field
			'name' => 'link_content',

			// Some options, see http://imperavi.com/redactor/docs/
			'options' => array(
				'lang' => 'ru',
				'toolbar' => 'classic',
				'buttons'=>array('html', '|', 'formatting', '|', 'bold', 'italic', 'deleted', 'underline','|','alignleft', 'aligncenter', 'alignright', 'justify','|',
					'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
					'image', 'table', 'link', '|',
					'fontcolor', 'backcolor', '|', 'alignment', '|', 'horizontalrule'),
				'iframe' => true,
				'imageUpload' => Yii::app()->createUrl("footerLinks/imageUpload"),
				'imageUploadErrorCallback'=> 'js: function(json) { alert(json.error); }',
				'uploadFields'=>array(
					Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken,
				),
			),
			'htmlOptions' => array('style'=>"width: 100%; height: 400px;"),
		));
		?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
