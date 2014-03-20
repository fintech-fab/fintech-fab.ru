<?php
/* @var DefaultController $this */
/* @var VideoIdentifyForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Подключение Пакета";
?>
	<h4>Требуется идентификация</h4>

	<div class="alert in alert-block alert-warning">
		<h4>Для подключения пакета займов, необходимо пройти идентификацию.</h4>
	</div>
	<div class="clearfix"></div>
<?php
$this->widget("CheckBrowserWidget");

Yii::app()->clientScript->registerScript('goIdentify', '
	//по нажатию кнопки отправляем эвент ajax-ом, затем сабмитим форму
	function goIdentify()
	{
		$.ajax({url: "/account/goIdentify"}).done(function() {
			$("#identify-form").submit();
		});
	}
	', CClientScript::POS_HEAD);

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
			'htmlOptions' => array(
				'onclick' => 'js: goIdentify()'
			)
		));
		?>
	</div>
<?php


$this->endWidget();
?>
	<br />
<?php $this->renderPartial('app_info'); ?>