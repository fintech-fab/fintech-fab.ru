<?php
/* @var DefaultController $this */
/* @var SMSCodeForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Оформление займа";
?>
	<h4>Оформление займа</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'loan-form',
	'action' => Yii::app()->createUrl('/account/doLoanSmsConfirm'),
));

$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => 'Информация о займе',
	'content' => $this->renderPartial('loan/_loan', array(), true)
));
$model->sendSmsCode = 1;
echo $form->hiddenField($model, 'sendSmsCode');
?>



	<div class="alert in alert-block alert-error span7">
		При отправке SMS с паролем произошла ошибка. Попробуйте снова запросить пароль.<br />В случае, если ошибка
		повторяется, обратитесь в контактный центр.
	</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Отправить SMS с кодом подтверждения на номер +7' . Yii::app()->user->getId(),
		)); ?>
	</div>

<?php

$this->endWidget();
