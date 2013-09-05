<?php
/**
 * @var $this DefaultController
 * @var $passFormRender
 * @var $history
 * @var $historyProvider
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - История операций';

$this->menu = array(
	array(
		'label' => 'Состояние подписки', 'url' => array(
		Yii::app()->createUrl('account')
	)

	),
	array(
		'label'  => 'История операций', 'url' => array(
		Yii::app()->createUrl('account/history')
	),
		'active' => true
	)
);

if ($this->smsState['smsAuthDone']) {
	$this->menu[] = array(
		'label' => 'Тестовое действие', 'url' => array(
			Yii::app()->createUrl('account/test')
		),
	);
}

$this->menu[] = array('label' => 'Выход', 'url' => array(Yii::app()->createUrl('account/logout')));

echo "<h4>История операций</h4>";


if ($this->smsState['needSmsPass']) {
	echo "<h5>Для доступа к закрытым данным требуется авторизоваться по одноразовому СМС-паролю </h5>";
} else {
	$this->widget('bootstrap.widgets.TbGridView', array(
		'id'           => 'history-grid',
		'dataProvider' => $historyProvider,
		'type'         => 'striped bordered condensed',
		'columns'      => array(
			array('name' => 'time', 'header' => 'Дата'),
			array(
				'name'  => 'type', 'header' => 'Тип',
				'value' => '($data["type"]=="invoice")?"Оплата услуги":"Получение денег"',
			),
			array(
				'name'  => 'type_id', 'header' => 'Тип',
				'value' => '($data["type"]=="invoice")?SiteParams::$aTypes[$data["type_id"]]:"Получение займа"',
			),
			array(
				'name'  => 'amount', 'header' => 'Сумма',
				'value' => 'abs($data["amount"])',
			),
		),
	));

}

echo $passFormRender;

//echo '<pre>';
//print_r($history);
//CVarDumper::dump($historyProvider);
//echo '</pre>';
