<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>

<span class="span2">
	<?= $form->checkboxRow( $oClientCreateForm, 'job_less', array( 'uncheckValue' => 0, 'class' => 'trigger-job_less' ) ); ?>
</span>

<div class="clearfix"></div>

<span class="span2">
	<?=$form->dropDownListRow($oClientCreateForm, 'job_time', Dictionaries::$aJobTimes, array( 'class' => 'span2') ); ?>
</span>
<span class="span2">
	<?=$form->phoneMaskedRow($oClientCreateForm, 'job_director_phone', array( 'class' => 'span2', 'mask' => '8 999 999 99 99') ); ?>
</span>
<span class="span3">
	<?=$form->textFieldRow($oClientCreateForm, 'job_director_name', Dictionaries::$aJobTimes, array( 'class' => 'span3') ); ?>
</span>
