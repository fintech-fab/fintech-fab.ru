<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>
<span class="span2">
	<?=$form->dropDownListRow($oClientCreateForm, 'secret_question', Dictionaries::$aSecretQuestions, array( 'class' => 'span4') ); ?>
</span>
<span class="span4">
	<?php echo $form->textFieldRow( $oClientCreateForm, 'secret_answer', array( 'class' => 'span4' ) ); ?>
</span>
