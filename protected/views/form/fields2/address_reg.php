<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>
<span class="span6">
	<?= $form->dropDownListRow($oClientCreateForm, 'address_reg_region', Dictionaries::getRegions(), array('empty' => ''));	?>
	<?= $form->textFieldRow($oClientCreateForm, 'address_reg_city');	?>
	<?= $form->textFieldRow($oClientCreateForm, 'address_reg_address');?>

	<?= $form->dropDownListRow($oClientCreateForm, 'address_res_region', Dictionaries::getRegions(), array('class' => 'span4', 'empty' => ''));	?>
	<?= $form->textFieldRow($oClientCreateForm, 'address_res_city', array('class' => 'span3'));	?>
	<?= $form->textFieldRow($oClientCreateForm, 'address_res_address', array('class' => 'span8'));?>
</span>

<span class="span6">
	<?= $form->textFieldRow($oClientCreateForm, 'relatives_one_fio'); ?>
	<?= $form->phoneMaskedRow($oClientCreateForm, 'relatives_one_phone'); ?>

	<?= $form->textFieldRow($oClientCreateForm, 'friends_fio'); ?>
	<?= $form->phoneMaskedRow($oClientCreateForm, 'friends_phone'); ?>
</span>