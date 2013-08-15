<?php
/* @var $this PagesController */
/* @var $model Pages */
/* @var $form CActiveForm */

Yii::import('ext.imperavi-redactor-widget.ImperaviRedactorWidget');

?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'                   => 'pages-form',
		'enableAjaxValidation' => true,
	)); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span> , являются обязательными.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'page_name'); ?>
		<?php echo $form->textField($model, 'page_name', array('size' => 20, 'maxlength' => 20)); ?>
		<?php echo $form->error($model, 'page_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'page_title'); ?>
		<?php echo $form->textField($model, 'page_title', array('size' => 60, 'maxlength' => 100)); ?>
		<?php echo $form->error($model, 'page_title'); ?>
	</div>

	<div class="row">
		<?php
		$this->widget('ImperaviRedactorWidget', array(
			// You can either use it for model attribute
			'model'       => $model,
			'attribute'   => 'page_content',

			// or just for input field
			'name'        => 'page_content',

			// Some options, see http://imperavi.com/redactor/docs/

			'options'     => array(
				'lang'                     => 'ru',
				//'deniedTags' => array('html', 'head', 'link', 'body', 'meta', 'script', 'style', 'applet'),
				'removeEmptyTags'=>false,
				'convertDivs' => false,
				'paragraphy' => false,
				'autoresize' => false,
				'imageGetJson' => Yii::app()->getBaseUrl().'/admin/files/imagesList',
				'toolbar'                  => 'classic',
				'buttons'                  => array(
					'html', '|', 'formatting', '|', 'bold', 'italic', 'deleted', 'underline', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|',
					'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
					'image', 'video','table', 'link', '|',
					'fontcolor', 'backcolor', '|', 'alignment', '|', 'horizontalrule'
				),
				'iframe'                   => true,
				'css' => array('/static/css/bootstrap4redactor.min.css','/static/css/main.css','/static/css/style.css','/static/css/payment.css','/static/css/form.css','/static/css/bootstrap-overload.css'),
				'imageUpload'              => Yii::app()->createUrl("admin/files/imageUpload"),
				'imageUploadErrorCallback' => 'js: function(json) { alert(json.error); }',
				'uploadFields'             => array(
					Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken,
				),
			),
			'htmlOptions' => array('style' => "width: 100%; height: 500px;"),
		));
		?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->
