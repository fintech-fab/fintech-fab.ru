<?php
/**
 * @var $form  IkTbActiveForm
 * @var $model faqGroup
 */
?>
<div class="form">
	<?php
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   => 'faq-group-form',
		'enableAjaxValidation' => false,
	)); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span> , являются обязательными.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model, 'title', array('class' => 'span5', 'maxlength' => 100)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'label'      => $model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

	<?php $this->endWidget(); ?>
</div>
