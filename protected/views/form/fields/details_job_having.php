<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>

<span class="span2">
	<?=$form->radioButtonListInlineRow($oClientCreateForm, 'have_car', Dictionaries::$aYesNo ); ?>
</span>
<span class="span2">
	<?=$form->radioButtonListInlineRow($oClientCreateForm, 'have_estate', Dictionaries::$aYesNo ); ?>
</span>
<span class="span2">
	<?=$form->radioButtonListInlineRow($oClientCreateForm, 'have_credit', Dictionaries::$aYesNo ); ?>
</span>
<span class="span2">
	<?=$form->radioButtonListInlineRow($oClientCreateForm, 'have_dependents', Dictionaries::$aYesNo ); ?>
</span>
<span class="span2">
	<?=$form->dropDownListRow($oClientCreateForm, 'liabilities', Dictionaries::$aLiabilities, array( 'empty' => '', 'class' => 'span2') ); ?>
</span>
