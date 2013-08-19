<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */
?>
<div class="span5">
	<?= $form->textFieldRow($oClientCreateForm, 'numeric_code', array('class' => 'span3')); ?>
	<?= $form->dropDownListRow($oClientCreateForm, 'secret_question', Dictionaries::$aSecretQuestions, array('class' => 'span3')); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'secret_answer', array('class' => 'span3')); ?>
	<?= $form->checkBoxRow($oClientCreateForm, 'complete'); ?>
</div>

<div class="span5">
	<?php echo $form->radioButtonListRow($oClientCreateForm, 'product', Dictionaries::$aProducts, array("class" => "all")); ?>
</div>