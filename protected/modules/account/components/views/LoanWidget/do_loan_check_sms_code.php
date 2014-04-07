<?php
/**
 * @var LoanWidget     $this
 * @var SMSCodeForm    $oModel
 * @var IkTbActiveForm $form
 */

?>
<h4><?= $this->getHeader(); ?></h4>
<?php
$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => $this->getInfoTitle(),
	'content' => $this->getFullInfo(),
));
?>
<div class="alert in alert-block alert-success">
	<?= $this->getSentSmsSuccessMessage(); ?>
</div>
<div class="alert in alert-block alert-info">
	<?= $this->getEnterCodeMessage(); ?>
</div>
<div class="form" id="activeForm">
	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'          => 'loan-form',
		'action'      => Yii::app()->createUrl('/account/doLoanCheckSmsCode'),
		'htmlOptions' => array(
			'class'        => "span4",
			'autocomplete' => 'off',
		),
	));

	?>
	<?= $form->textFieldRow($oModel, 'smsCode') ?>
	<?
	$this->renderSmsCodeSubmitButton();
	?>

	<?php
	$this->endWidget();
	?>
</div>
