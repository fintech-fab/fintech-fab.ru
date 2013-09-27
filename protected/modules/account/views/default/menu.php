<?php
/**
 * @var $this Controller
 */

$this->menu = array(
	array(
		'label'  => 'Ваш Пакет займов', 'url' => array('/account/default/index')
	),
	array(
		'label'  => 'История операций', 'url' => array('/account/default/history')
	),
	array(
		'label'  => 'Подключение Пакета займов', 'url' => array('/account/default/subscribe'),
	),
	array(
		'label'  => 'Оформление займа', 'url' => array('/account/default/loan')
	),
	array(
		'label'  => 'Привязка пластиковой карты', 'url' => array('/account/default/addCard')
	),
	array(
		'label' => 'Выход', 'url' => array('/account/default/logout')
	),
);

if (Yii::app()->adminKreddyApi->getBalance() < 0) {
	$sBalanceMessage = '<strong>Задолженность:</strong> ' . Yii::app()->adminKreddyApi->getAbsBalance() . ' руб. <br/>';
	$sExpireToMessage = '<strong>Вернуть:</strong> ' . Yii::app()->adminKreddyApi->getActiveLoanExpiredTo() . '<br/>'; //TODO: убрать время 23.59 ?
} else {
	$sBalanceMessage = '<strong>Баланс:</strong> ' . Yii::app()->adminKreddyApi->getAbsBalance() . ' руб. <br/>';
	$sExpireToMessage = '';
}

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
		'activateItems'=>true,
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
