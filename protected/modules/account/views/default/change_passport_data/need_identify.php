<?php
/* @var DefaultController $this */
/* @var VideoIdentifyForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение паспортных данных";
?>
	<h4>Изменение паспортных данных</h4>

	<div class="alert in alert-block alert-warning">
		<h4>Для изменения паспортных данных необходимо пройти идентификацию. После идентификации потребуется ввести
			данные документов, использованных при идентификации.</h4>
	</div>
	<div class="clearfix"></div>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => 'identify-form',
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
			'id'          => 'submitButton',
			'type'        => 'primary',
			'size'        => 'large',
			'label'       => 'Пройти идентификацию',
			'url' => $this->createUrl('identify'),
		));
		?>
	</div>
<?php
$this->endWidget();

?>
	<br />
<?php
$this->widget('application.modules.account.components.AppInfoWidget');
?>
