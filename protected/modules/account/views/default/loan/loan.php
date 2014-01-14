<?php
/* @var DefaultController $this */
/* @var ClientLoanForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Оформление займа - Выберите канал получения займа";
?>
	<h4>Оформление займа - Выберите канал получения займа</h4>

<?php

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'loan-form',
	'action' => Yii::app()->createUrl('/account/doLoan'),
));
?>

	<div class="alert alert-info">Ваш пакет займов - &quot;<?= Yii::app()->adminKreddyApi->getSubscriptionProduct() ?>
		&quot;<br /> Размер второго займа - <?= Yii::app()->adminKreddyApi->getSubscriptionLoanAmount(); ?> руб.
	</div>

<?php

$this->widget('application.modules.account.components.ShowChannelsWidget', array(
		'sFormName'          => get_class($model),
		'aAvailableChannels' => Yii::app()->adminKreddyApi->getAvailableChannelValues(),
	)
);

$this->endWidget();
