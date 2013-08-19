<?

/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */


?>

<span class="span10">
	<?= $form->dropDownListRow($oClientCreateForm, 'address_reg_region', Dictionaries::getRegions(), array('class' => 'span4', 'empty' => ''));	?>
</span>

<span class="span10">
	<?= $form->textFieldRow($oClientCreateForm, 'address_reg_city', array('class' => 'span3'));	?>
</span>

<span class="span10">
	<?= $form->textFieldRow($oClientCreateForm, 'address_reg_address', array('class' => 'span8'));?>
</span>
