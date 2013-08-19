<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */
?>
<div class="span6">
<?= $form->textFieldRow($oClientCreateForm, 'last_name'); ?>
<?= $form->textFieldRow($oClientCreateForm, 'first_name'); ?>
<?= $form->textFieldRow($oClientCreateForm, 'third_name'); ?>
<?= $form->dateMaskedRow($oClientCreateForm, 'birthday'); ?>
</div>
<div class="span6">
<?= $form->phoneMaskedRow($oClientCreateForm, 'phone'); ?>
<?= $form->textFieldRow($oClientCreateForm, 'email'); ?>
<?= $form->radioButtonListRow($oClientCreateForm, 'sex', Dictionaries::$aSexes); ?>
</div>