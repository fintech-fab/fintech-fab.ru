<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $sContent
 * @var $sIdentifyRender
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

<?= $sIdentifyRender ?>
	<br />
<?php $this->renderPartial('app_info'); ?>