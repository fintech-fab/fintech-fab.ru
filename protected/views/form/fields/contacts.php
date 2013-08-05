<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>
<div class="span5">
<?php
	echo $form->fieldMaskedRow($oClientCreateForm, 'phone', array('class' => 'span2', 'mask' => '+7 999 999 99 99'));
	echo $form->textFieldRow( $oClientCreateForm, 'email', array( 'class' => 'span3' ) );
?>
</div>
