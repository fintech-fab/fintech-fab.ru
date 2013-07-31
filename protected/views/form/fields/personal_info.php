<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

echo $form->dateFieldRow($oClientCreateForm, 'birthday');

echo '<br/>';

echo $form->radioButtonListRow($oClientCreateForm, 'sex', Dictionaries::$aSexes );

echo '<br/>';
