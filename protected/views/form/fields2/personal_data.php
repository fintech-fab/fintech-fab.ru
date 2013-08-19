<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */
?>
<div class="span5">
<?= $form->textFieldRow($oClientCreateForm, 'last_name'); ?>
<?= $form->textFieldRow($oClientCreateForm, 'first_name'); ?>
<?= $form->textFieldRow($oClientCreateForm, 'third_name'); ?>
<?= $form->dateMaskedRow($oClientCreateForm, 'birthday',array('size'=>'5','class'=>'inline')); ?>
</div>
<div class="span5">
<?= $form->phoneMaskedRow($oClientCreateForm, 'phone',array('size'=>'15')); ?>
<?= $form->textFieldRow($oClientCreateForm, 'email'); ?>
<?= $form->radioButtonListRow($oClientCreateForm, 'sex', Dictionaries::$aSexes); ?>
</div>