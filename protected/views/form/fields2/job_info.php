<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */
?>
<div class="span5">
	<?= $form->textFieldRow($oClientCreateForm, 'job_company', array('class' => 'span3')); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'job_position', array('class' => 'span3')); ?>
	<?= $form->phoneMaskedRow($oClientCreateForm, 'job_phone', array( 'class' => 'span3' ) ) ?>
</div>
<div class="span5 offset1">
	<?=$form->dropDownListRow($oClientCreateForm, 'job_time', Dictionaries::$aJobTimes, array( 'class' => 'span2') ); ?>
	<?=$form->dropDownListRow($oClientCreateForm, 'job_monthly_income', Dictionaries::$aMonthlyMoney, array( 'empty' => '', 'class' => 'span2') ); ?>
	<?=$form->dropDownListRow($oClientCreateForm, 'job_monthly_outcome', Dictionaries::$aMonthlyMoney, array( 'empty' => '', 'class' => 'span2') ); ?>
	<?=$form->radioButtonListInlineRow($oClientCreateForm, 'have_past_credit', Dictionaries::$aYesNo ); ?>
</div>