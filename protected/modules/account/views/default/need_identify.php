<?php
/* @var DefaultController $this */
/* @var VideoIdentifyForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Оформление пакета";
?>
	<h4>Требуется идентификация</h4>

	<p>Для продолжения требуется пройти идентификацию.</p>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => 'login-form',
	'action'               => $model->video_url,
	'method'               => 'post',
	'enableAjaxValidation' => false,
	'clientOptions'        => array(
		'validateOnSubmit' => false,
	),
));
?>

<?= $form->hiddenField($model, 'client_code', array('name' => 'client_code')); ?>
<?= $form->hiddenField($model, 'service', array('name' => 'service')); ?>
<?= $form->hiddenField($model, 'signature', array('name' => 'signature')); ?>
<?= $form->hiddenField($model, 'timestamp', array('name' => 'timestamp')); ?>
<?= $form->hiddenField($model, 'redirect_back_url', array('name' => 'redirect_back_url')); ?>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
	'id'         => 'submitButton',
	'buttonType' => 'submit',
	'type'       => 'primary',
	'size'       => 'small',
	'label'      => 'Пройти идентификацию',
));

$this->endWidget();
