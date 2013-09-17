<?php
/* @var FormController $this */
/* @var ClientConfirmPhoneViaSMSForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm
 */

/*
 * Ввести код подтверждения из SMS
 */

$this->pageTitle = Yii::app()->name;

$aCrumbs = array(
	array('Выбор пакета', 1),
	array('Заявка на займ', 2),
	array('Подтверджение номера телефона', 4, 3)
);
?>

<div class="row">

	<?php $this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs)); ?>

	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                     => "checkSmsCode",
		'enableClientValidation' => true,
		'htmlOptions'            => array(
			'class' => "span10",
		),
		'clientOptions'          => array(
			'validateOnChange' => true,
			'validateOnSubmit' => true,
		),
		'action'                 => Yii::app()->createUrl('/form/checkSmsCode'),
	));
	?>
	<div id="alertsmssent" class="alert in alert-success"><?= Dictionaries::C_SMS_SUCCESS; ?></div>

	<div class="clearfix"></div>

	<label>Введите код из SMS:</label>
	<?= $form->textField($oClientCreateForm, 'sms_code', array('class' => 'span4')); ?>
	<?= $form->error($oClientCreateForm, 'sms_code'); ?>

	<div class="clearfix"></div>

	<div class="form-actions">
		<?php
		$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'label'      => 'Далее →',
		)); ?>
	</div>

	<?php
	$this->endWidget();
	?>

	<?php $this->widget('YaMetrikaGoalsWidget', array(
		'iDoneSteps'    => Yii::app()->clientForm->getCurrentStep(),
		'iSkippedSteps' => 2,
	)); ?>
</div>
