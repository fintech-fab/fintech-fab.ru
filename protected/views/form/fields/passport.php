<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

echo $form->textFieldRow($oClientCreateForm, 'passport_series', array('class' => 'span1'));
echo $form->textFieldRow($oClientCreateForm, 'passport_number', array('class' => 'span2'));

echo $form->fieldMaskedRow($oClientCreateForm, 'passport_date', array('mask' => '99.99.9999'));

echo $form->fieldMaskedRow($oClientCreateForm, 'passport_code', array('class' => 'span2', 'mask' => '999-999'));
echo $form->textFieldRow($oClientCreateForm, 'passport_issued', array('class' => 'span3'));
