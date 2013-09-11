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

<?= $passFormRender // отображаем форму запроса СМС-пароля?>
