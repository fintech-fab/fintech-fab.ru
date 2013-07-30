<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>

<span class="span4"><?
	echo $form->dropDownListRow($oClientCreateForm, 'document', Dictionaries::$aDocuments, array( 'class' => 'span4', 'empty' => '' ) );
?></span>

<span class="span2"><?
	echo $form->textFieldRow($oClientCreateForm, 'document_number', array( 'class' => 'span2' ) );
?></span>