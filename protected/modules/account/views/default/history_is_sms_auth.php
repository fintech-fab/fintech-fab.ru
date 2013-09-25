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

?>
<h4>История операций</h4>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
	'id'           => 'history-grid',
	'dataProvider' => $historyProvider,
	'type'         => 'striped bordered condensed',
	'emptyText'    => 'История операций пуста',
	'columns'      => array(
		array(
			'name'  => 'time', 'header' => 'Дата и время',
			'value' => 'date("d.m.Y H:i",strtotime($data["time"]))',
		),
		/*array(
			'name'  => 'type', 'header' => 'Тип операции',
			'value' => '($data["type"]=="invoice")?"Оплата услуги":"Получение денег"',
		),*/
		array(
			'name'  => 'type_id', 'header' => 'Операция',
			'value' => '($data["type"]=="invoice")?SiteParams::$aTypes[$data["type_id"]]:"Получение займа"',
		),
		array(
			'name'  => 'amount', 'header' => 'Сумма',
			'type'  => 'raw',
			'value' => '($data["type"]=="invoice")?"<span>".abs($data["amount"])."</span>":"<span style=\"color: green\">".abs($data["amount"])."</span>"',
		),
	),
));
?>
