<?php
/* @var DefaultController $this */
/* @var VideoIdentifyForm $model */
/* @var IkTbActiveForm $form */

?>

	<br/><br/>
	<div class="alert in alert-block alert-warning span7">
		<h4>Вам необходимо пройти идентификацию!</h4>
	</div>
<div class="clearfix"></div>
<?php
$this->widget("CheckBrowserWidget");

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
<div class="center">
<?php
$this->widget('bootstrap.widgets.TbButton', array(
	'id'         => 'submitButton',
	'buttonType' => 'submit',
	'type'       => 'primary',
	'size'       => 'large',
	'label'      => 'Пройти идентификацию',
));
?>
</div>
<?php

$this->endWidget();
