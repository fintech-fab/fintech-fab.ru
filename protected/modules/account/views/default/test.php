<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $passFormRender
 * @var $codeFormRender
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - Состояние подписки';

$this->menu = array(
	array(
		'label' => 'Состояние подписки', 'url' => array(
		Yii::app()->createUrl('account')
	),
	),
	array(
		'label' => 'История займов', 'url' => array(
		Yii::app()->createUrl('account/history')
	)
	)
);

if ($this->smsState['smsAuthDone']) {
	$this->menu[] = array(
		'label'  => 'Тестовое действие', 'url' => array(
			Yii::app()->createUrl('account/test')
		),
		'active' => true,
	);
}

$this->menu[] = array('label' => 'Выход', 'url' => array(Yii::app()->createUrl('account/logout')));

echo "<h4>Тестовое действие</h4>";

if (!$this->smsState['needSmsPass']) {

} else {
	echo "<h5>Для доступа к закрытым данным требуется авторизоваться по одноразовому СМС-паролю. Сделать это можно на главной странице личного кабинета.</h5>";
}

if (!$this->smsState['needSmsActionCode']) {

} else {
	echo "<h5>Для выполнения действия требуется одноразовый SMS-код.</h5>";
}
//echo $passFormRender;

echo $codeFormRender;

//echo '<pre>' . "";
//CVarDumper::dump($this->clientData);
//echo '</pre>';
//echo '<pre>' . "";
//CVarDumper::dump($aTest);
//echo '</pre>';

