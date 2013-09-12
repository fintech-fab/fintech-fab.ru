<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $model */
/* @var IkTbActiveForm $form */

?>
	<h4>Оформление подписки</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/doSubscribeCheckSmsCode'),
));

$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => 'Подписка на продукт',
	'content' => $this->renderPartial('subscription/_product', array(), true)
));
?>

	<p>Для подтверждения подписки требуется подтверждение одноразовым СМС-кодом</p>
	<div class="form-actions">
		<?= $form->textFieldRow($model, 'smsCode') ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'label'      => 'Подтвердить',
		)); ?>
	</div>

<?php

$this->endWidget();