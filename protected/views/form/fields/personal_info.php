<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

echo $form->dateMaskedRow($oClientCreateForm, 'birthday');

echo '<br/>';

echo $form->radioButtonListRow($oClientCreateForm, 'sex', Dictionaries::$aSexes );

echo '<br/>';
