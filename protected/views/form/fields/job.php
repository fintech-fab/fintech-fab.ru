<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>
<span class="span8">
	<?= $form->textFieldRow($oClientCreateForm, 'job_company', array('class' => 'span4')); ?>
</span>

<span class="span8">
	<?= $form->textFieldRow($oClientCreateForm, 'job_position', array('class' => 'span4')); ?>
</span>

<span class="span8">
	<?= $form->phoneMaskedRow($oClientCreateForm, 'job_phone', array('mask' => '+7 999 999 99 99') ) ?>
</span>

<span class="span8">
	<?=$form->dropDownListRow($oClientCreateForm, 'job_time', Dictionaries::$aJobTimes, array( 'class' => 'span2') ); ?>
</span>

<span class="span8">
	<?=$form->dropDownListRow($oClientCreateForm, 'job_monthly_income', Dictionaries::$aMonthlyMoney, array( 'empty' => '', 'class' => 'span2') ); ?>
</span>

<span class="span8">
	<?=$form->dropDownListRow($oClientCreateForm, 'job_monthly_outcome', Dictionaries::$aMonthlyMoney, array( 'empty' => '', 'class' => 'span2') ); ?>
</span>

<span class="span8">
	<?=$form->radioButtonListInlineRow($oClientCreateForm, 'have_past_credit', Dictionaries::$aYesNo ); ?>
</span>
