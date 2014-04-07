<?php
/**
 * @var SubscriptionWidget $this
 * @var SMSCodeForm        $oModel
 * @var IkTbActiveForm     $form
 */

?>
	<h4><?= $this->getHeader(); ?></h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/doSubscribeSmsConfirm'),
));

$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => $this->getInfoTitle(),
	'content' => $this->getFullInfo(),
));
$oModel->sendSmsCode = 1;
echo $form->hiddenField($oModel, 'sendSmsCode');
?>

	<div class="alert in alert-block alert-error">
		<?= $this->getSendSmsErrorMessage(); ?>
	</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => $this->getSendSmsButtonLabel(),
		)); ?>
	</div>

<?php

$this->endWidget();
