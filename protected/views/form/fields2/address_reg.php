<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>
<div class="span5">
	<?= $form->dropDownListRow($oClientCreateForm, 'address_reg_region', Dictionaries::getRegions(), array('empty' => '','class'=>'span3'));	?>
	<?= $form->textFieldRow($oClientCreateForm, 'address_reg_city');	?>
	<?= $form->textFieldRow($oClientCreateForm, 'address_reg_address');?>

	<?= $form->dropDownListRow($oClientCreateForm, 'address_res_region', Dictionaries::getRegions(), array('class' => 'span3', 'empty' => ''));	?>
	<?= $form->textFieldRow($oClientCreateForm, 'address_res_city', array('class' => 'span3'));	?>
	<?= $form->textFieldRow($oClientCreateForm, 'address_res_address', array('class' => 'span3'));?>
</div>

<div class="span5 offset1">
	<?= $form->textFieldRow($oClientCreateForm, 'relatives_one_fio',array('class' => 'span3')); ?>
	<?= $form->phoneMaskedRow($oClientCreateForm, 'relatives_one_phone',array('class' => 'span3')); ?>

	<?= $form->textFieldRow($oClientCreateForm, 'friends_fio',array('class' => 'span3')); ?>
	<?= $form->phoneMaskedRow($oClientCreateForm, 'friends_phone',array('class' => 'span3')); ?>
</div>