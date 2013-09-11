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

?>
<h4>История операций</h4>

<?= $passFormRender // отображаем форму запроса СМС-пароля ?>
