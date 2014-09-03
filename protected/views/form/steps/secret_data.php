<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
		'hideErrorMessage' => true,
	),
	'action'               => Yii::app()->createUrl('/form'),
));

//снимаем все эвенты с кнопки, т.к. после загрузки ajax-ом содержимого эвент снова повесится на кнопку
//сделано во избежание навешивания кучи эвентов
Yii::app()->clientScript->registerScript('ajaxForm', '
		updateAjaxForm();
		');
Yii::app()->clientScript->registerScript('scrollAndFocus', '
		scrollAndFocus();
		', CClientScript::POS_LOAD);
?>

<?php $this->widget('YaMetrikaGoalsWidget'); ?>

<?php
//TODO сделать getProgressBarStep()
$this->widget('FormProgressBarWidget', array('aSteps' => Yii::app()->clientForm->getFormWidgetSteps(), 'iCurrentStep' => Yii::app()->clientForm->getCurrentStep()));
?>
<h4>Отправка заявки</h4>
<div class="row">
	<div class="span6">
		<div class="row">
			<?= $form->textFieldRow($oClientCreateForm, 'numeric_code', SiteParams::getHintHtmlOptions($oClientCreateForm, 'numeric_code') + array('maxlength' => 4)); ?>
			<?= $form->dropDownListRow2($oClientCreateForm, 'secret_question', Dictionaries::$aSecretQuestions, SiteParams::getHintHtmlOptions($oClientCreateForm, 'secret_question')); ?>
			<?= $form->textFieldRow($oClientCreateForm, 'secret_answer', SiteParams::getHintHtmlOptions($oClientCreateForm, 'secret_answer')); ?>
		</div>
	</div>
	<div class="span6">
		<div class="row">
			<?= $form->passwordFieldRow($oClientCreateForm, 'password', SiteParams::getHintHtmlOptions($oClientCreateForm, 'password') + array('autocomplete' => 'off')); ?>
			<?= $form->passwordFieldRow($oClientCreateForm, 'password_repeat', SiteParams::getHintHtmlOptions($oClientCreateForm, 'password_repeat') + array('autocomplete' => 'off')); ?>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="span12">
	<div class="form-actions row">
		<div class="span2">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'          => 'backButton',
				'buttonType'  => 'ajaxButton',
				'ajaxOptions' => array(
					'complete' => 'checkBlankResponse',
					'type'     => 'POST',
					'update'   => '#formBody',
				),
				'url'         => Yii::app()
						->createUrl('/form/ajaxForm/' . Yii::app()->clientForm->getCurrentStep()),
				'label'       => SiteParams::C_BUTTON_LABEL_BACK,
			)); ?>
		</div>

		<div class="span2 offset2">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'         => 'submitButton',
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'      => SiteParams::C_BUTTON_LABEL_NEXT,
			)); ?>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>
