<?php
/**
 * @var LoanWidget     $this
 * @var ClientLoanForm $oModel
 * @var IkTbActiveForm $form
 */

?>
	<h4><?= $this->getHeader(); ?> - <?= $this->getSelectChannelMessage(); ?></h4>

<?php

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'loan-form',
	'action' => Yii::app()->createUrl('/account/doLoan'),
));
?>

	<div class="alert alert-info">
		<?= $this->getLoanInfo(); ?>
	</div>

<?php

$this->widget('application.modules.account.components.ShowChannelsWidget', array(
		'sFormName'          => get_class($oModel),
		'aAvailableChannels' => Yii::app()->adminKreddyApi->getAvailableChannelValues(),
	)
);

$this->endWidget();
