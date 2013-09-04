<?php
/**
 * @var $this DefaultController
 * @var $passForm
 * @var $passFormRender
 * @var $history
 * @var $historyProvider
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->menu[] = array(
	'label' => 'Состояние подписки', 'url' => array(
		Yii::app()->createUrl('account')
	)

);
$this->menu[] = array(
	'label'  => 'История займов', 'url' => array(
		Yii::app()->createUrl('account/history')
	),
	'active' => true
);
$this->menu[] = array('label' => 'Выход', 'url' => array(Yii::app()->createUrl('account/logout')));

if ($this->smsState['needSmsPass']) {
	echo "<h5>Для доступа к закрытым данным требуется авторизоваться по одноразовому СМС-паролю </h5>";
} else {
	$this->widget('bootstrap.widgets.TbGridView', array(
		'id'           => 'history-grid',
		'dataProvider' => $historyProvider,
		'type'         => 'striped bordered condensed',
		'columns'      => array(
			'id',
			'client_product_id',
			'transfer_group_id',
			'transfer_type_id',
			'amount_system',
			'amount_actual',
			'dt_add',
			'dt_update'
		),
	));

}

echo $passFormRender;

echo '<pre>';
print_r($history);
CVarDumper::dump($historyProvider);
echo '</pre>';
