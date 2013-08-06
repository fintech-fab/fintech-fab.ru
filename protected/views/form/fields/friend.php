<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>
<span class="span10">
	<?= $form->textFieldRow($oClientCreateForm, 'friends_fio', array('class' => 'span8')); ?>
</span>

<span class="span10">
	<?= $form->phoneMaskedRow($oClientCreateForm, 'friends_phone', array('class' => 'span3')); ?>
</span>
