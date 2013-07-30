<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

echo $form->textFieldRow($oClientCreateForm, 'passport_series', array('class' => 'span1'));
echo $form->textFieldRow($oClientCreateForm, 'passport_number', array('class' => 'span2'));
echo $form->label($oClientCreateForm, 'passport_date', array( 'required' => $oClientCreateForm->isAttributeRequired('passport_date') ) );
echo $form->dateField($oClientCreateForm, 'passport_date');
echo $form->error($oClientCreateForm, 'passport_date');
echo $form->textFieldRow($oClientCreateForm, 'passport_code', array('class' => 'span2'));
echo $form->textFieldRow($oClientCreateForm, 'passport_issued', array('class' => 'span3'));
