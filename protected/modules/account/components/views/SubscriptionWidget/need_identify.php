<?php
/**
 * @var SubscriptionWidget $this
 * @var VideoIdentifyForm  $oModel
 * @var IkTbActiveForm     $form
 */

?>
	<h4><?= $this->getNeedIdentifyHeader(); ?></h4>

	<div class="alert in alert-block alert-warning">
		<h4><?= $this->getNeedIdentifyMessage() ?></h4>
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
	'action'               => $oModel->video_url,
	'method'               => 'post',
	'enableAjaxValidation' => false,
	'clientOptions'        => array(
		'validateOnSubmit' => false,
	),
));
?>

<?= $form->hiddenField($oModel, 'client_code', array('name' => 'client_code')); ?>
<?= $form->hiddenField($oModel, 'service', array('name' => 'service')); ?>
<?= $form->hiddenField($oModel, 'signature', array('name' => 'signature')); ?>
<?= $form->hiddenField($oModel, 'timestamp', array('name' => 'timestamp')); ?>
<?= $form->hiddenField($oModel, 'redirect_back_url', array('name' => 'redirect_back_url')); ?>
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
		<?php
		$this->widget('bootstrap.widgets.TbButton', array(
			'size'        => 'large',
			'label'       => 'Инструкция',
			'htmlOptions' => array(
				'class'   => 'btn-warning',
				'onClick' => 'return doOpenModalFrame(\'/pages/viewPartial/videoInstruction\', \'Инструкция\')',
			)
		));

		?>
	</div>
<?


$this->endWidget();
?>
	<br />
<?

$this->widget('application.modules.account.components.AppInfoWidget');

?>
