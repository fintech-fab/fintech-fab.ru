<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>
<span class="span3">
	<?= $form->textFieldRow($oClientCreateForm, 'relatives_one_fio', array('class' => 'span3')); ?>
</span>

<span class="span3">
	<?= $form->phoneMaskedRow($oClientCreateForm, 'relatives_one_phone', array('class' => 'span3', 'mask' => '8 999 999 99 99')); ?>
</span>

<span class="span2">
	<?=$form->radioButtonListInlineRow($oClientCreateForm, 'have_past_credit', Dictionaries::$aYesNo ); ?>
</span>
