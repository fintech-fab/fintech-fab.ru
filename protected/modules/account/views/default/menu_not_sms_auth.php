<?php
/**
 * @var $this Controller
 */

$this->menu = array(
	array(
		'label'  => 'Состояние подписки', 'url' => array(
		Yii::app()->createUrl('account')
	),
		'active' => (Yii::app()->controller->action->id == 'index') ? true : false,
	),
	array(
		'label'  => 'История операций', 'url' => array(
		Yii::app()->createUrl('account/history')
	),
		'active' => (Yii::app()->controller->action->id == 'history') ? true : false,
	)
);

$this->menu[] = array('label' => 'Выход', 'url' => array(Yii::app()->createUrl('account/logout')));

if (Yii::app()->adminKreddyApi->getIsDebt()) {
	$sDebtMessage = 'У вас есть задолженность по кредиту.';
} elseif (!Yii::app()->adminKreddyApi->getIsDebt()) {
	$sDebtMessage = 'У вас нет задолженности по кредиту.';
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
		<p><strong><?= $sDebtMessage ?></strong><br />Авторизуйтесь по SMS-паролю для получения подробной информации.
		</p>
	</div>
	<?php $this->endWidget(); ?>

</div>
