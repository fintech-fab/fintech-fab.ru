<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $passFormRender
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - Личный кабинет - Состояние подписки';

$this->menu = array(
	array(
		'label'  => 'Состояние подписки', 'url' => array(
		Yii::app()->createUrl('account')
	),
		'active' => true,
	),
	array(
		'label' => 'История операций', 'url' => array(
		Yii::app()->createUrl('account/history')
	)
	)
);

$this->menu[] = array('label' => 'Выход', 'url' => array(Yii::app()->createUrl('account/logout')));

?>

<h4>Состояние подписки</h4>

<h5>Для доступа к закрытым данным требуется авторизоваться по одноразовому СМС-паролю </h5>

<?= $passFormRender // отображаем форму запроса СМС-пароля?>
