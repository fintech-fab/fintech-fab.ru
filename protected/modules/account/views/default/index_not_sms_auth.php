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

?>

<h4>Состояние подписки</h4>

<h5>Для доступа к закрытым данным требуется авторизоваться по одноразовому СМС-паролю </h5>

<?= $passFormRender // отображаем форму запроса СМС-пароля?>
