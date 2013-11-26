<?php
/**
 * @var $form  IkTbActiveForm
 * @var $model FaqQuestion
 */

Yii::import('ext.imperavi-redactor-widget.ImperaviRedactorWidget');
?>
<div class="form">
	<?php
	$form = $this->beginWidget('bootstrap.widgets.IkTbActiveForm', array(
		'id'                   => 'faq-group-form',
		'enableAjaxValidation' => false,
	)); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span> , являются обязательными.</p>

	<div class="row">
		<?php echo $form->errorSummary($model); ?>
	</div>

	<div class="row">
		<?php echo $form->dropDownListRow($model, 'group_id', CHtml::listData(FaqGroup::model()
			->findAll(), 'id', 'title'), array('class' => 'span4')); ?>
	</div>

	<div class="row">
		<?php echo $form->textFieldRow($model, 'title', array('class' => 'span4', 'maxlength' => 500)); ?>
	</div>

	<div class="row">
		<?= $form->checkBoxRow($model, 'show_site1'); ?>
		<?= $form->checkBoxRow($model, 'show_site2'); ?>
	</div>

	<div class="row">
		<?php
		$this->widget('ImperaviRedactorWidget', array(
			// You can either use it for model attribute
			'model'       => $model,
			'attribute'   => 'answer',

			// or just for input field
			'name'        => 'answer',

			// Some options, see http://imperavi.com/redactor/docs/
			'options'     => array(
				'lang'                     => 'ru',
				'toolbar'                  => 'classic',
				'convertDivs'              => false,
				'paragraphy'               => false,
				'autoresize'               => false,
				'removeEmptyTags'          => false,
				'imageGetJson'             => Yii::app()->getBaseUrl() . '/admin/files/imagesList',
				'buttons'                  => array(
					'html', '|', 'formatting', '|', 'bold', 'italic', 'deleted', 'underline', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|',
					'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
					'image', 'table', 'link', '|',
					'fontcolor', 'backcolor', '|', 'alignment', '|', 'horizontalrule'
				),
				'iframe'                   => true,
				'css'                      => array(
					'/static/css/bootstrap4redactor.min.css',
					'/static/css/main.css',
					'/static/css/bootstrap-overload.css',
					'/static/css/form.css',
					'/static/css/style.css',
					'/static/css/payment.css',
					'/static/css/redactor-table.css'
				),
				'imageUpload'              => Yii::app()->createUrl("admin/files/imageUpload"),
				'imageUploadErrorCallback' => 'js: function(json) { alert(json.error); }',
				'uploadFields'             => array(
					Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken,
				),
			),
			'htmlOptions' => array('style' => "width: 100%; height: 400px;"),
		));
		?>
	</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'label'      => $model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

	<?php $this->endWidget(); ?>
</div>
