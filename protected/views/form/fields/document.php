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
//TODO: при изменении типа документа заново валидировать поле с номером документа
/*
Yii::app()->clientScript->registerScript('validate', "
	jQuery('#".get_class($oClientCreateForm)."_document').change(function(){
		jQuery('input#".get_class($oClientCreateForm)."_document_number').focus().blur().change();
	});
");*/

?>
