<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Подключение Пакета";
?>
	<h4>Подключение Пакета</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/doSubscribeSmsConfirm'),
));

$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => 'Информация о подключении',
	'content' => $this->renderPartial('subscription/_product', array(), true)
));
$model->sendSmsCode = 1;
echo $form->hiddenField($model, 'sendSmsCode');
?>


	<?php //TODO вынести сообщения в константы ?>
	<div class="alert in alert-block alert-error">
		При отправке SMS с кодом подтверждения произошла ошибка. Попробуйте снова запросить код подтверждения.<br />В
		случае, если ошибка повторяется, обратитесь в контактный центр.
	</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Отправить SMS с кодом подтверждения на номер +7' . Yii::app()->user->getMaskedId(),
		)); ?>
	</div>

<?php

$this->endWidget();
