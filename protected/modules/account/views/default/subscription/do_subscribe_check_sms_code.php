<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Оформление подписки";
?>
<h4>Оформление подписки</h4>
<?php
$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => 'Подписка на продукт',
	'content' => $this->renderPartial('subscription/_product', array(), true)
));
?>
<div class="alert in alert-block alert-warning span7">
	Для подтверждения подписки введите код, пришедший Вам по SMS
</div>
<div class="form" id="activeForm">
	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'          => 'products-form',
		'action'      => Yii::app()->createUrl('/account/doSubscribeCheckSmsCode'),
		'htmlOptions' => array(
			'class' => "span4",
		),
	));

	?>
	<?= $form->textFieldRow($model, 'smsCode') ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'submit',
		'type'       => 'primary',
		'size'       => 'small',
		'label'      => 'Подтвердить',
	)); ?>

	<?php
	$this->endWidget();
	?>
</div>
