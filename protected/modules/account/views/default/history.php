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

$this->pageTitle = Yii::app()->name . ' - Личный кабинет - История операций';

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

/*if ($this->smsState['smsAuthDone']) {
	$this->menu[] = array(
		'label' => 'Тестовое действие', 'url' => array(
			Yii::app()->createUrl('account/test')
		),
	);
}*/

$this->menu[] = array('label' => 'Выход', 'url' => array(Yii::app()->createUrl('account/logout')));
?>
<h4>История операций</h4>

<?php
if ($this->smsState['needSmsPass']) {
	?>
	<h5>Для доступа к закрытым данным требуется авторизоваться по одноразовому СМС-паролю </h5>
<?php
} else {
	$this->widget('bootstrap.widgets.TbGridView', array(
		'id'           => 'history-grid',
		'dataProvider' => $historyProvider,
		'type'         => 'striped bordered condensed',
		'columns'      => array(
			array(
				'name'  => 'time', 'header' => 'Дата и время',
				'value' => 'date("d.m.Y H:i",strtotime($data["time"]))',
			),
			array(
				'name'  => 'type', 'header' => 'Тип операции',
				'value' => '($data["type"]=="invoice")?"Оплата услуги":"Получение денег"',
			),
			array(
				'name'  => 'type_id', 'header' => 'Операция',
				'value' => '($data["type"]=="invoice")?SiteParams::$aTypes[$data["type_id"]]:"Получение займа"',
			),
			array(
				'name'  => 'amount', 'header' => 'Сумма',
				'value' => 'abs($data["amount"])',
			),
		),
	));
}
?>

<?= $passFormRender // отображаем форму запроса СМС-пароля ?>
