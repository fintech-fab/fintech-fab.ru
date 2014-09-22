<?php
/**
 * @var FormController           $this
 * @var IkTbActiveForm           $form
 * @var ClientCreateFormAbstract $oClientCreateForm
 */

$this->pageTitle = 'Кредди - Сервис в твоем формате';
?>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => false,
	'type'                 => 'inline',
	'clientOptions'        => array(
		'hideErrorMessage' => true,
		'validateOnChange' => false,
		'validateOnSubmit' => false,
	),
	'action'               => Yii::app()->createUrl('/form'),
));
?>
<div class="col-lg-6 col-md-7">

	<?= $form->textFieldRow($oClientCreateForm, 'last_name', array('class' => 'slider_input')); ?>

	<?= $form->textFieldRow($oClientCreateForm, 'first_name', array('class' => 'slider_input')); ?>

	<?= $form->textFieldRow($oClientCreateForm, 'third_name', array('class' => 'slider_input')); ?>

	<div class="clearfix"></div>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'  => 'submit',
		'type'        => 'danger',
		'label'       => 'Зарегистрироваться',

		'htmlOptions' => array(
			'class' => 'slider_submit',
		),
	)); ?>
</div>
<div class="col-lg-6 col-md-5 visible-lg visible-md hidden-xs hidden-sm">
	<div class="formErrors">
		<?= $form->errorSummary($oClientCreateForm, '', '', array('class' => 'alert alert-danger')); ?>
	</div>
</div>
<?php $this->endWidget(); ?>
