<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $sClientInfoRender
 * @var $sPassFormRender
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

<?= $sClientInfoRender ?>

<?= $sPassFormRender // отображаем форму запроса SMS-пароля ?>
	<div class="clearfix"></div>
<?php $this->renderPartial('app_info'); ?>