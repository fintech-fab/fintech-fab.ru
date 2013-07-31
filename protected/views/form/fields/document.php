<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>

<span class="span5"><?
	echo $form->dropDownListRow($oClientCreateForm, 'document', Dictionaries::$aDocuments, array( 'class' => 'span4', 'empty' => '' ) );
?></span>

<span class="span3"><?
	echo $form->textFieldRow($oClientCreateForm, 'document_number', array( 'class' => 'span3' ) );
?></span>
