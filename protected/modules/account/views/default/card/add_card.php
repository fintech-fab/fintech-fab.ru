<?php
/* @var DefaultController $this */
/* @var AddCardForm $model */
/* @var IkTbActiveForm $form */
/* @var $sError */

$this->pageTitle = Yii::app()->name . " - Привязка пластиковой карты";
?>
	<h4>Привязка пластиковой карты</h4>

<?php if (!empty($sError)) { ?>
	<div class="alert alert-error"><?= $sError ?></div> <?php } ?>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'add-card',
	'action' => Yii::app()->createUrl('/account/addCard'),
));
?>

<?= $form->textFieldRow($model, 'sCardPan'); ?>
<?= $form->dropDownListRow($model, 'sCardMonth', Dictionaries::$aMonthsDigital); ?>
<?= $form->dropDownListRow($model, 'sCardYear', Dictionaries::getYears()); ?>
<?= $form->textFieldRow($model, 'sCardCvc'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Привязать карту',
		)); ?>
	</div>

<?php

$this->endWidget();
