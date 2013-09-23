<?php
/**
 * @var $this Controller
 */

$this->menu = array(
	array(
		'label'  => 'Ваш пакет займов', 'url' => array(
		Yii::app()->createUrl('account')
	),
		'active' => (Yii::app()->controller->action->getId() == 'index') ? true : false,
	),
	array(
		'label'  => 'История операций', 'url' => array(
		Yii::app()->createUrl('account/history')
	),
		'active' => (Yii::app()->controller->action->getId() == 'history') ? true : false,
	)
);

$this->menu[] = array('label' => 'Выход', 'url' => array(Yii::app()->createUrl('account/logout')));

if (Yii::app()->adminKreddyApi->getIsDebt()) {
	$sDebtMessage = 'У вас есть задолженность по займу.';
} elseif (!Yii::app()->adminKreddyApi->getIsDebt()) {
	$sDebtMessage = 'У вас нет задолженности по займу.';
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
