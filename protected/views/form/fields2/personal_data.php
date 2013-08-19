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
<?= $form->dateMaskedRow($oClientCreateForm, 'birthday',array('size'=>'5','style'=>'width: 100px;','class'=>'inline')); ?>
</div>
<div class="span6">
<?= $form->phoneMaskedRow($oClientCreateForm, 'phone',array('size'=>'15','style'=>'width: 150px;')); ?>
<?= $form->textFieldRow($oClientCreateForm, 'email'); ?>
<?= $form->radioButtonListRow($oClientCreateForm, 'sex', Dictionaries::$aSexes); ?>
</div>