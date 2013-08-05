<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>
<div class="span5">
<?php
	echo $form->phoneMaskedRow($oClientCreateForm, 'phone', array('class' => 'span2'));
	echo $form->textFieldRow( $oClientCreateForm, 'email', array( 'class' => 'span3' ) );
?>
</div>
