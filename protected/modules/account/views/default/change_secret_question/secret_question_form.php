<?php
/* @var DefaultController $this */
/* @var ChangeSecretQuestionForm $oChangeSecretQuestionForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение секретного вопроса";
?>
<h4>Изменение секретного вопроса</h4>

<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => 'secret-question-form',
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
	),
	'action'               => Yii::app()->createUrl('/account/changeSecretQuestion'),
));
?>

<div class="row">
	<div class="span5">
		<?= $form->dropDownListRow($oChangeSecretQuestionForm, 'secret_question',Dictionaries::$aSecretQuestions);  ?>
		<?= $form->textFieldRow($oChangeSecretQuestionForm, 'secret_answer');  ?>
	</div>
</div>

<div class="clearfix"></div>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'submit',
		'type'       => 'primary',
		'size'       => 'small',
		'label'      => 'Отправить заявку',
	)); ?>
</div>

<?php
$this->endWidget();