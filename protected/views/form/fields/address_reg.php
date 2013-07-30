<?

/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */


?>

<span class="span3"><?
	echo $form->dropDownListRow( $oClientCreateForm, 'address_reg_region', Dictionaries::getRegions(), array( 'empty' => '', 'class' => 'span3' ) );
?></span>

<span class="span4"><?
	echo $form->textFieldRow( $oClientCreateForm, 'address_reg_city', array( 'class' => 'span4' ) );
?></span>

<span class="span10"><?
	echo $form->textFieldRow( $oClientCreateForm, 'address_reg_address', array( 'class' => 'span6' ) );
?></span>
