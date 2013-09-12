<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $model */
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
	'content' => $this->renderPartial('subscription/_product', array(), true)
));
$model->sendSmsCode = 1;
echo $form->hiddenField($model, 'sendSmsCode');
?>



	<div class="alert in alert-block alert-error span7">
		При отправке SMS с паролем произошла ошибка. Попробуйте снова запросить пароль.<br />В случае, если ошибка
		повторяется, позвоните на горячую линию.
	</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Отправить СМС с кодом подтверждения на номер +7' . Yii::app()->user->getId(),
		)); ?>
	</div>

<?php

$this->endWidget();