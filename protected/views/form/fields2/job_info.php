<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */
?>
<span class="span6">
	<?= $form->textFieldRow($oClientCreateForm, 'job_company', array('class' => 'span4')); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'job_position', array('class' => 'span4')); ?>
	<?= $form->phoneMaskedRow($oClientCreateForm, 'job_phone', array( 'class' => 'span2' ) ) ?>
</span>
<span class="span6">
	<?=$form->dropDownListRow($oClientCreateForm, 'job_time', Dictionaries::$aJobTimes, array( 'class' => 'span2') ); ?>
	<?=$form->dropDownListRow($oClientCreateForm, 'job_monthly_income', Dictionaries::$aMonthlyMoney, array( 'empty' => '', 'class' => 'span2') ); ?>
	<?=$form->dropDownListRow($oClientCreateForm, 'job_monthly_outcome', Dictionaries::$aMonthlyMoney, array( 'empty' => '', 'class' => 'span2') ); ?>
	<?=$form->radioButtonListInlineRow($oClientCreateForm, 'have_past_credit', Dictionaries::$aYesNo ); ?>
</span>