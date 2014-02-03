<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $sContent
 * @var $passFormRender
 */

$this->breadcrumbs = array(
	$this->module->id,
);

if (SiteParams::getIsIvanovoSite()) {
	$this->pageTitle = Yii::app()->name . ' - Статус займа';
} else {
	$this->pageTitle = Yii::app()->name . ' - Ваш Пакет займов';
}
?>

<?= $sContent ?>

<?= $passFormRender // отображаем форму запроса SMS-пароля ?>

<?php $this->renderPartial('app_info'); ?>