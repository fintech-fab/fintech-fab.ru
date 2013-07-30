<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>
<span class="span3">
	<?= $form->textFieldRow($oClientCreateForm, 'job_company', array('class' => 'span3')); ?>
</span>

<span class="span3">
	<?= $form->textFieldRow($oClientCreateForm, 'job_position', array('class' => 'span3')); ?>
</span>

<span class="span3">
	<?= $form->phoneMaskedRow($oClientCreateForm, 'job_phone', array('mask' => '8 999 999 99 99') ) ?>
</span>
