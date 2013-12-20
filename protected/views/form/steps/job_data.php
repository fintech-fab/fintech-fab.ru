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

?>
<?php
$this->widget('FormProgressBarWidget', array('aSteps' => SiteParams::$aFormWidgetSteps, 'iCurrentStep' => Yii::app()->clientForm->getCurrentStep()));
?>
<h4 id="jobInfoHeading">Место работы</h4>

<div class="span10">
	<?= $form->dropDownListRow($oClientCreateForm, 'status', Dictionaries::$aStatuses, array('empty' => '')); ?>
</div>

<div id="employee" class="statusfields hide">
	<div class="span10">
		<?= $form->textFieldRow($oClientCreateForm, 'job_company', array('class' => 'span3')); ?>
		<?= $form->textFieldRow($oClientCreateForm, 'job_position', array('class' => 'span3')); ?>
		<?= $form->phoneMaskedRow($oClientCreateForm, 'job_phone', array('class' => 'span3')) ?>
		<?= $form->dropDownListRow($oClientCreateForm, 'job_time', Dictionaries::$aJobTimes, array('class' => 'span2')); ?>
	</div>
</div>

<div id="student" class="statusfields hide">
	<div class="span10"><?= $form->textFieldRow($oClientCreateForm, 'educational_institution_name', array('class' => 'span3')); ?>
		<?= $form->phoneMaskedRow($oClientCreateForm, 'educational_institution_phone', array('class' => 'span3')); ?></div>
</div>

<div id="jobless" class="statusfields hide">
	<div class="span10"><?= $form->textFieldRow($oClientCreateForm, 'income_source', array('class' => 'span3')); ?></div>
</div>

<div class="span10">
	<?= $form->dropDownListRow($oClientCreateForm, 'job_monthly_income', Dictionaries::$aMonthlyMoney, array('empty' => '')); ?>
	<?= $form->dropDownListRow($oClientCreateForm, 'job_monthly_outcome', Dictionaries::$aMonthlyMoney, array('empty' => '')); ?>
</div>

<div class="clearfix"></div>
<div class="row span10">
	<div class="form-actions">
		<div class="row">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'          => 'submitButton',
				'buttonType'  => 'ajaxSubmit',
				'ajaxOptions' => array(
					'type'     => 'POST',
					'update'   => '#formBody',
				),
				'url'         => Yii::app()->createUrl('/form/ajaxForm'),
				'type'        => 'primary',
				'label'       => 'Далее',
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

