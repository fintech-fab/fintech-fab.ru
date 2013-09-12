<?php
/* @var DefaultController $this */
/* @var ClientSubscribeForm $model */
/* @var IkTbActiveForm $form */

?>
	<h4>Оформление подписки</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/doSubscribeSmsConfirm'),
));

$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => 'Подписка на продукт',
	'content' => $this->renderPartial('subscription/_product', array('model' => $model), true) //TODO сделать вьюху
));

?>

	<p>Для подтверждения подписки требуется подтверждение одноразовым СМС-кодом</p>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'label'      => 'Отправить СМС с кодом подтверждения на номер +7' . Yii::app()->user->getId(),
		)); ?>
	</div>

<?php

$this->endWidget();