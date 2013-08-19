<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */
?>
<div class="span6">
<?= $form->fieldPassportRow($oClientCreateForm, array('passport_series','passport_number'), array('passport_number'=>array('class' => 'span2', 'mask' => '999999', 'size'=>'6', 'maxlength'=>'6'),'passport_series'=>array('class' => 'span1', 'mask' => '9999', 'size'=>'4', 'maxlength'=>'4')));?>


<?= $form->dateMaskedRow($oClientCreateForm, 'passport_date');?>

<?= $form->fieldMaskedRow($oClientCreateForm, 'passport_code', array('mask' => '999-999', 'size'=>'7', 'maxlength'=>'7',));?>
<?= $form->textFieldRow($oClientCreateForm, 'passport_issued');?>
</div>

<div class="span5">
	<?=	$form->dropDownListRow($oClientCreateForm, 'document', Dictionaries::$aDocuments, array('class' => 'span4', 'empty' => '')); ?>
</div>

<div class="span3">
	<?= $form->textFieldRow($oClientCreateForm, 'document_number', array('class' => 'span3')); ?>
</div>

<?php
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
