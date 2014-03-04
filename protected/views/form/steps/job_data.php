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


			<div id="employee" class="statusfields hide">

				<?= $form->textFieldRow($oClientCreateForm, 'job_company', SiteParams::getHintHtmlOptions($oClientCreateForm, 'job_company')); ?>
				<?= $form->textFieldRow($oClientCreateForm, 'job_position', SiteParams::getHintHtmlOptions($oClientCreateForm, 'job_position')); ?>
				<?= $form->phoneMaskedRow($oClientCreateForm, 'job_phone', SiteParams::getHintHtmlOptions($oClientCreateForm, 'job_phone')) ?>
				<?= $form->dropDownListRow2($oClientCreateForm, 'job_time', Dictionaries::$aJobTimes, SiteParams::getHintHtmlOptions($oClientCreateForm, 'job_time') + array('class' => 'span2')); ?>

			</div>

			<div id="student" class="statusfields hide">
				<?= $form->textFieldRow($oClientCreateForm, 'educational_institution_name', SiteParams::getHintHtmlOptions($oClientCreateForm, 'educational_institution_name')); ?>
				<?= $form->phoneMaskedRow($oClientCreateForm, 'educational_institution_phone', SiteParams::getHintHtmlOptions($oClientCreateForm, 'educational_institution_phone')); ?>
			</div>

			<div id="jobless" class="statusfields hide">
				<?= $form->textFieldRow($oClientCreateForm, 'income_source', SiteParams::getHintHtmlOptions($oClientCreateForm, 'income_source')); ?>
			</div>

			<?= $form->dropDownListRow2($oClientCreateForm, 'job_monthly_income', Dictionaries::$aMonthlyMoney, SiteParams::getHintHtmlOptions($oClientCreateForm, 'job_monthly_income') + array('empty' => '')); ?>
			<?= $form->dropDownListRow2($oClientCreateForm, 'job_monthly_outcome', Dictionaries::$aMonthlyMoney, SiteParams::getHintHtmlOptions($oClientCreateForm, 'job_monthly_outcome') + array('empty' => '')); ?>

			<?= $form->dropDownListRow2($oClientCreateForm, 'loan_purpose', Dictionaries::$aLoanPurposes, SiteParams::getHintHtmlOptions($oClientCreateForm, 'loan_purpose') + array('empty' => '')); ?>

			<?= $form->radioButtonListInlineRow($oClientCreateForm, 'have_past_credit', Dictionaries::$aYesNo, SiteParams::getHintHtmlOptions($oClientCreateForm, 'have_past_credit') + array('empty' => '')); ?>
		</div>
	</div>
	<div class="span6">
		<div class="row">
			<h5>Контакты родственников/друзей</h5>
			<?= $form->textFieldRow($oClientCreateForm, 'relatives_one_fio', SiteParams::getHintHtmlOptions($oClientCreateForm, 'relatives_one_fio')); ?>
			<?= $form->phoneMaskedRow($oClientCreateForm, 'relatives_one_phone', SiteParams::getHintHtmlOptions($oClientCreateForm, 'relatives_one_phone')); ?>

			<h5>Дополнительный контакт<br />(повышает вероятность одобрения)</h5>
			<?= $form->textFieldRow($oClientCreateForm, 'friends_fio', SiteParams::getHintHtmlOptions($oClientCreateForm, 'friends_fio')); ?>
			<?= $form->phoneMaskedRow($oClientCreateForm, 'friends_phone', SiteParams::getHintHtmlOptions($oClientCreateForm, 'friends_phone')); ?>
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

<?php
//todo: место работы для предпринимателя по-другому называется
//при изменении статуса выводим дополнительные поля, если нужно.
Yii::app()->clientScript->registerScript('loadExtraFields', '
	var oStudent = jQuery("#student");
	oStudent.find("label").append(" <span class=\"required\">*</span>");
	var oJobless = jQuery("#jobless");
	oJobless.find("label").append(" <span class=\"required\">*</span>");
	var oEmployee = jQuery("#employee");
	oEmployee.find("label").append(" <span class=\"required\">*</span>");

    var oStatusField = jQuery("#' . get_class($oClientCreateForm) . '_status");
	oStatusField.change(function()	{
	    sStatus = oStatusField.val();
	    if(sStatus == "") {
		   oStudent.hide();
		   oJobless.hide();
		   oEmployee.hide();
		} else if((sStatus == 1) || (sStatus == 2)) {
		   oStudent.hide();
		   oJobless.hide();
		   oEmployee.show();
		} else if(sStatus == 3) {
		   oStudent.show();
		   oJobless.hide();
		   oEmployee.hide();
		} else if(sStatus == 4) {
		   oStudent.hide();
		   oJobless.hide();
		   oEmployee.hide();
		} else if((sStatus == 5) || (sStatus == 6)) {
		   oJobless.show();
		   oStudent.hide();
		   oEmployee.hide();
		}
	});

	oStatusField.change();
');

?>

