<?php
/* @var DefaultController $this */
/* @var AddCardForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Привязка банковской карты";
?>
	<h4>Привязка банковской карты</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'add-card',
	'action' => Yii::app()->createUrl('/account/verifyCard'),
));
?>

<?= $form->textFieldRow($model, 'sCardVerifyAmount'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Потдвердить',
		)); ?>
	</div>

<?php

$this->endWidget();
