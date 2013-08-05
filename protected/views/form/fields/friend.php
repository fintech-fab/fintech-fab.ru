<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

?>
<span class="span10">
	<?= $form->textFieldRow($oClientCreateForm, 'friend_fio', array('class' => 'span8')); ?>
</span>

<span class="span10">
	<?= $form->phoneMaskedRow($oClientCreateForm, 'friend_phone', array('class' => 'span3')); ?>
</span>
