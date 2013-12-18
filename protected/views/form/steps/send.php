<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */
?>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
	),
	'action'               => Yii::app()->createUrl('/form#next'),
));
?>

<h4 id="sendHeading">Отправка</h4>
<?php
//отдельно задаем свойства для радиокнопок, для корректной отработки валидации и сопутствующих JS
$productHtmlOptions = array('errorOptions' => $htmlOptions['errorOptions'] + array('id' => get_class($oClientCreateForm) . '_product'), 'uncheckValue' => '999');

?>
<div class="span5">
	<?= $form->textFieldRow($oClientCreateForm, 'numeric_code', array('class' => 'span3')); ?>
	<?= $form->dropDownListRow($oClientCreateForm, 'secret_question', Dictionaries::$aSecretQuestions, array('class' => 'span3')); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'secret_answer', array('class' => 'span3')); ?>
</div>
<?php //отдельный DIV ID для радиокнопок, для обработки в JS ?>
<div class="span6">
	<?= $form->passwordFieldRow($oClientCreateForm, 'password', $htmlOptions + array('autocomplete' => 'off')); ?>
	<?= $form->passwordFieldRow($oClientCreateForm, 'password_repeat', $htmlOptions + array('autocomplete' => 'off')); ?>
</div>

<div class="clearfix"></div>
<div class="row span10">
	<div class="form-actions">
		<div class="row">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'         => 'submitButton',
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'      => 'Далее',
			)); ?>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>
