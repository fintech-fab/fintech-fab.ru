<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>

<span class="span2">
	<?=$form->dropDownListRow($oClientCreateForm, 'job_monthly_income', Dictionaries::$aMonthlyMoney, array( 'empty' => '', 'class' => 'span2') ); ?>
</span>
<span class="span2">
	<?=$form->dropDownListRow($oClientCreateForm, 'job_monthly_outcome', Dictionaries::$aMonthlyMoney, array( 'empty' => '', 'class' => 'span2') ); ?>
</span>
<span class="span2">
	<?=$form->dropDownListRow($oClientCreateForm, 'job_salary_date', Dictionaries::$aMoneyDays, array( 'empty' => '', 'class' => 'span2') ); ?>
</span>
<span class="span2">
	<?=$form->dropDownListRow($oClientCreateForm, 'job_prepay_date', Dictionaries::$aMoneyDays, array( 'empty' => '', 'class' => 'span2') ); ?>
</span>
<span class="span2">
	<?=$form->dropDownListRow($oClientCreateForm, 'job_income_add', Dictionaries::$aOverMoney, array( 'empty' => '', 'class' => 'span2') ); ?>
</span>
