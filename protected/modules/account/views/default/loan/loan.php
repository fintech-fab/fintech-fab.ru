<?php
/* @var DefaultController $this */
/* @var ClientLoanForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Оформление займа";
?>
	<h4>Оформление займа</h4>

<?php

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'loan-form',
	'action' => Yii::app()->createUrl('/account/doLoan'),
));
?>

	<div class="alert alert-info">Размер займа: <b><?= Yii::app()->adminKreddyApi->getSubscriptionLoanAmount(); ?>
			рублей</b></div>

<?php

$this->widget('application.modules.account.components.ShowChannelsWidget',
	array(
		'sFormName'             => get_class($model),
		'aAllChannelNames' => Yii::app()->adminKreddyApi->getProductsChannels(),
		'aAvailableChannelKeys' => Yii::app()->adminKreddyApi->getClientSubscriptionChannels(),
	)
);

$this->endWidget();
