<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */
?>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'hideErrorMessage' => true,
		'validateOnChange' => true,
		'validateOnSubmit' => true,
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
$this->widget('FormProgressBarWidget', array('aSteps' => Yii::app()->clientForm->getFormWidgetSteps(), 'iCurrentStep' => Yii::app()->clientForm->getCurrentStep()));
?>
<h4 id="jobInfoHeading">Дополнительная информация</h4>
<div class="row">
	<div class="span6">
		<h5>Место работы</h5>

		<div class="row">
			<?= $form->dropDownListRow2($oClientCreateForm, 'status', Dictionaries::$aStatuses, SiteParams::getHintHtmlOptions($oClientCreateForm, 'status') + array('empty' => '')); ?>

			<?= $form->dropDownListRow2($oClientCreateForm, 'loan_purpose', Dictionaries::$aLoanPurposes, SiteParams::getHintHtmlOptions($oClientCreateForm, 'loan_purpose') + array('empty' => '')); ?>

			<?= $form->radioButtonListInlineRow($oClientCreateForm, 'have_past_credit', Dictionaries::$aYesNo, SiteParams::getHintHtmlOptions($oClientCreateForm, 'have_past_credit') + array('empty' => '')); ?>
		</div>
	</div>
	<div class="span6">
		<div class="row">
			<h5>Дополнительные контакты</h5>
			<?= $form->textFieldRow($oClientCreateForm, 'relatives_one_fio', SiteParams::getHintHtmlOptions($oClientCreateForm, 'relatives_one_fio')); ?>
			<?= $form->phoneMaskedRow($oClientCreateForm, 'relatives_one_phone', SiteParams::getHintHtmlOptions($oClientCreateForm, 'relatives_one_phone')); ?>
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
					'update' => '#formBody',
				),
				'url'         => Yii::app()
						->createUrl('/form/ajaxForm/' . Yii::app()->clientForm->getCurrentStep()),
				'label'       => SiteParams::C_BUTTON_LABEL_BACK,
			)); ?>
		</div>
		<div class="span2 offset2">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'          => 'submitButton',
				'buttonType'  => 'ajaxSubmit',
				'ajaxOptions' => array(
					'complete' => 'checkBlankResponse',
					'type'   => 'POST',
					'update' => '#formBody',
				),
				'url'         => Yii::app()->createUrl('/form/ajaxForm'),
				'type'        => 'primary',
				'label'       => SiteParams::C_BUTTON_LABEL_NEXT,
			)); ?>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>

