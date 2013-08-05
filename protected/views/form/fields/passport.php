<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

echo $form->fieldMaskedRow($oClientCreateForm, 'passport_series', array('class' => 'span1', 'mask' => '9999', 'size'=>'4', 'maxlength'=>'4',));
echo $form->fieldMaskedRow($oClientCreateForm, 'passport_number', array('class' => 'span2', 'mask' => '999999', 'size'=>'6', 'maxlength'=>'6',));

echo $form->dateMaskedRow($oClientCreateForm, 'passport_date');

echo $form->fieldMaskedRow($oClientCreateForm, 'passport_code', array('class' => 'span2', 'mask' => '999-999', 'size'=>'7', 'maxlength'=>'7',));
echo $form->textFieldRow($oClientCreateForm, 'passport_issued', array('class' => 'span3'));
