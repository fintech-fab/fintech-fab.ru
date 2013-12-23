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
	),
	'action'               => Yii::app()->createUrl('/form'),
));

//снимаем все эвенты с кнопки, т.к. после загрузки ajax-ом содержимого эвент снова повесится на кнопку
//сделано во избежание навешивания кучи эвентов
Yii::app()->clientScript->registerScript('ajaxForm', '
		updateAjaxForm();
		');

?>

<?php $this->widget('YaMetrikaGoalsWidget'); ?>

<?php
$this->widget('FormProgressBarWidget', array('aSteps' => SiteParams::$aFormWidgetSteps, 'iCurrentStep' => Yii::app()->clientForm->getCurrentStep()));
?>
<div class="clearfix"></div><h4>Паспортные данные</h4>

<div class="span5">

	<div class="row">
		<div class="span5">
			<h5>Паспорт</h5>
		</div>
	</div>
	<?= $form->maskedTextFieldRow($oClientCreateForm, 'passport_full_number', '9999 / 999999', SiteParams::getHintHtmlOptions($oClientCreateForm, 'passport_number')); ?>

	<?= $form->dateMaskedRow($oClientCreateForm, 'passport_date', SiteParams::getHintHtmlOptions($oClientCreateForm, 'passport_date')); ?>

	<?= $form->fieldMaskedRow($oClientCreateForm, 'passport_code', SiteParams::getHintHtmlOptions($oClientCreateForm, 'passport_code') + array('mask' => '999-999', 'size' => '7', 'maxlength' => '7',)); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'passport_issued', SiteParams::getHintHtmlOptions($oClientCreateForm, 'passport_issued')); ?>
</div>

<div class="span5 offset1">
	<h5>Второй документ</h5>
	<?= $form->dropDownListRow2($oClientCreateForm, 'document', Dictionaries::$aDocuments, SiteParams::getHintHtmlOptions($oClientCreateForm, 'document') + array('class' => 'span3', 'empty' => '')); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'document_number', SiteParams::getHintHtmlOptions($oClientCreateForm, 'document_number') + array('class' => 'span3')); ?>
</div>

<div class="clearfix"></div>
<div class="row span10">
	<div class="form-actions">
		<div class="row">
			<div class="span1">
				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'id'          => 'backButton',
					'buttonType'  => 'ajaxButton',
					'ajaxOptions' => array(
						'update' => '#formBody',
					),
					'url'         => Yii::app()
							->createUrl('/form/ajaxForm/' . Yii::app()->clientForm->getCurrentStep()),
					'label'       => 'Назад',
				)); ?>
			</div>
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
<?php $this->endWidget();
//при изменении типа документа заново валидировать поле с номером документа.

Yii::app()->clientScript->registerScript('validate_document_number', '
	jQuery("#' . get_class($oClientCreateForm) . '_document").change(function()
	{
		var form=$("#' . get_class($oClientCreateForm) . '");
        var settings = form.data("settings");
        $.each(settings.attributes, function () {
	        if(this.name == "' . get_class($oClientCreateForm) . '[document_number]"){
	            this.status = 2; // force ajax validation
	        }
	    });
	    form.data("settings", settings);

	    // trigger ajax validation
	    $.fn.yiiactiveform.validate(form, function (data) {
	        $.each(settings.attributes, function () {

				if(this.name == "' . get_class($oClientCreateForm) . '[document_number]"){
	                $.fn.yiiactiveform.updateInput(this, data, form);
	            }
	        });
	    });
	});
');

?>
