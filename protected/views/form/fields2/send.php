<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */
?>
<span class="span6">
	<?= $form->textFieldRow($oClientCreateForm, 'numeric_code'); ?>
	<?= $form->dropDownListRow($oClientCreateForm, 'secret_question', Dictionaries::$aSecretQuestions, array('class' => 'span4')); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'secret_answer', array('class' => 'span4')); ?>
	<?= $form->checkBoxRow($oClientCreateForm, 'complete'); ?>
</span>

<span class="span6">
	<?php echo $form->radioButtonListRow($oClientCreateForm, 'product', Dictionaries::$aProducts, array("class" => "all")); ?>
</span>