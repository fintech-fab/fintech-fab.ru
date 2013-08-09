<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */

?>

<span class="span5"><?
	echo $form->dropDownListRow($oClientCreateForm, 'document', Dictionaries::$aDocuments, array('class' => 'span4', 'empty' => ''));
	?></span>

<span class="span3"><?
	echo $form->textFieldRow($oClientCreateForm, 'document_number', array('class' => 'span3'));
	?></span>

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
