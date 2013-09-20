<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $passFormRender
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - Личный кабинет - Состояние подключения';

?>

<h4>Состояние подключения</h4>

<?= $passFormRender // отображаем форму запроса SMS-пароля?>
