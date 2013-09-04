<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $passForm
 * @var $passFormRender
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->menu[] = array(
	'label'  => 'Состояние подписки', 'url' => array(
		Yii::app()->createUrl('account')
	),
	'active' => true,
);
$this->menu[] = array(
	'label' => 'История займов', 'url' => array(
		Yii::app()->createUrl('account/history')
	)
);
$this->menu[] = array('label' => 'Выход', 'url' => array(Yii::app()->createUrl('account/logout')));

//echo '<pre>';
//print_r($this->clientData);
//echo '</pre>';
if ($this->clientData['code'] == 0) {
	echo '<strong>Подписка активна до:</strong> ' . (@$this->clientData['subscription']['activity_to']) . ' <br/>';
	echo '<strong>Баланс:</strong> ' . (@$this->clientData['subscription']['balance']) . ' руб. <br/>';
	echo '<strong>Доступно займов:</strong> ' . (@$this->clientData['subscription']['available_loans']) . '<br/>';

} else {
	echo "<h5>Для доступа к закрытым данным требуется авторизоваться по одноразовому СМС-паролю </h5>";
}
echo $passFormRender;
