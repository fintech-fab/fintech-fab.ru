<?php
/**
 * @var FormController           $this
 * @var IkTbActiveForm           $form
 * @var ClientCreateFormAbstract $oClientCreateForm
 */
?>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'type'                 => 'inline',
	'clientOptions'        => array(
		'hideErrorMessage' => true,
		'validateOnChange' => true,
		'validateOnSubmit' => true,
	),
	'action'               => Yii::app()->createUrl('/form'),
));
?>
	<div class="col-lg-6 col-md-7">

		<?= $form->phoneMaskedRow($oClientCreateForm, 'phone', array('class' => 'slider_input')); ?>

		<?= $form->textFieldRow($oClientCreateForm, 'email', array('class' => 'slider_input')); ?>

		<?= $form->dateMaskedRow($oClientCreateForm, 'birthday', array('class' => 'slider_input')); ?>

		<div class="input_check">
			<?php
			$oClientCreateForm->agree = false;
			echo $form->checkBox($oClientCreateForm, 'agree');
			//echo $form->label($oClientCreateForm, 'agree', array('class' => 'slider_check', 'style' => 'width: 320px;'));
			?>
			<span class="slidr_checkbox">Я подтверждаю достоверность введенных данных и даю согласие на обработку(<a href="#" onclick="return doOpenModalFrame('/pages/viewPartial/usloviya', 'Условия обслуживания и передачи информации')">подробная информация</a>)
				</span>
		</div>
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


	<div class="col-lg-6 col-md-5 visible-lg visible-md  hidden-xs hidden-sm">
		<div class="formErrors">
			<?= $form->errorSummary($oClientCreateForm, '', '', array('class' => 'alert alert-danger')); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>