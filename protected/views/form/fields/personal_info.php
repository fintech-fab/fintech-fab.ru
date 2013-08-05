<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

echo $form->fieldMaskedRow($oClientCreateForm, 'birthday', array('mask' => '99.99.9999'));

echo '<br/>';

echo $form->radioButtonListRow($oClientCreateForm, 'sex', Dictionaries::$aSexes );

echo '<br/>';
