<?php
/**
 * @var LoanWidget     $this
 * @var SMSCodeForm    $oModel
 * @var IkTbActiveForm $form
 */

?>
	<h4><?= $this->getHeader(); ?></h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'loan-form',
	'action' => Yii::app()->createUrl('/account/doLoanSmsConfirm'),
));

$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => $this->getInfoTitle(),
	'content' => $this->getFullInfo(),
));
$oModel->sendSmsCode = 1;
echo $form->hiddenField($oModel, 'sendSmsCode');
?>
	<div class="alert in alert-block alert-warning">
		<?= $this->getNeedSmsMessage(); ?>
	</div>
<? $this->renderSendSmsButton(); ?>
<?php

$this->endWidget();
