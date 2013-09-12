<?php
/**
 * @var $this Controller
 */

//TODO сделать чтобы при 0 или положительном балансе не было "вернуть до"
$this->menu = array(
	array(
		'label'  => 'Состояние подписки', 'url' => array(
		Yii::app()->createUrl('account')
	),
		'active' => (Yii::app()->controller->action->id == 'index'),
	),
	array(
		'label'  => 'История операций', 'url' => array(
		Yii::app()->createUrl('account/history')
	),
		'active' => (Yii::app()->controller->action->id == 'history'),

	)
);

$this->menu[] = array('label' => 'Выход', 'url' => array(Yii::app()->createUrl('account/logout')));


if (Yii::app()->adminKreddyApi->getBalance() < 0) {
	$sBalanceMessage = '<strong>Задолженность:</strong> ' . Yii::app()->adminKreddyApi->getAbsBalance() . ' руб. <br/>';
} else {
	$sBalanceMessage = '<strong>Баланс:</strong> ' . Yii::app()->adminKreddyApi->getAbsBalance() . ' руб. <br/>';
}
$sExpireToMessage = '<strong>Вернуть до:</strong> ' . Yii::app()->adminKreddyApi->getActiveLoanExpired() . '<br/>';

if (Yii::app()->adminKreddyApi->getActiveLoanExpired()) {
	$sExpiredMessage = '<strong>Платеж просрочен!</strong><br/>';
} else {
	$sExpiredMessage = '';
}
?>

<div class="well" style="padding: 8px; 0; margin-top: 20px;">
	<?php

	$this->beginWidget('bootstrap.widgets.TbMenu', array(
		'type'        => 'pills', // '', 'tabs', 'pills' (or 'list')
		'stacked'     => true, // whether this is a stacked menu
		'items'       => $this->menu,
		'htmlOptions' => array('style' => 'margin-bottom: 0;'),
	));
	?>

	<div style="padding-left: 20px;">
		<h4><?= Yii::app()->adminKreddyApi->getClientFullName(); ?></h4>

		<p>
			<?= $sExpiredMessage; ?>
			<?= $sBalanceMessage; ?>
			<?= $sExpireToMessage ?>
		</p></div>
	<?php $this->endWidget(); ?>

</div>
