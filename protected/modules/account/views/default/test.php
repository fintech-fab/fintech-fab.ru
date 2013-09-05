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

echo "<h4>Состояние подписки</h4>";

if (!$this->smsState['needSmsPass']) {

} else {
	echo "<h5>Для доступа к закрытым данным требуется авторизоваться по одноразовому СМС-паролю </h5>";
}

if (!$this->smsState['needSmsActionCode']) {

} else {
	echo "<h5>Для выполнения действия требуется одноразовый SMS-код</h5>";
}
echo $passFormRender;

echo $codeFormRender;

echo '<pre>' . "";
CVarDumper::dump($this->clientData);
echo '</pre>';
echo '<pre>' . "";
CVarDumper::dump($aTest);
echo '</pre>';

