<?
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm $form
 */

echo $form->label($oClientCreateForm, 'birthday', array( 'required' => $oClientCreateForm->isAttributeRequired('birthday') ));
echo $form->dateField($oClientCreateForm, 'birthday');
echo $form->error($oClientCreateForm, 'birthday');

echo '<br/>';

echo $form->label($oClientCreateForm, 'sex', array( 'required' => $oClientCreateForm->isAttributeRequired('sex') ));
echo $form->radioButtonList($oClientCreateForm, 'sex', Dictionaries::$aSexes );
echo $form->error($oClientCreateForm, 'sex');

echo '<br/>';
