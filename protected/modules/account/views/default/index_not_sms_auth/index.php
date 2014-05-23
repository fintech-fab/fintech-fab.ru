<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $sClientInfoRender
 * @var $sPassFormRender
 * @var $sClientInfoView
 */

$this->breadcrumbs = array(
	$this->module->id,
);


?>

<?
if (Yii::app()->adminKreddyApi->isSubscriptionOldType()) {
	$this->widget('application.modules.account.components.ClientInfoWidget', array('sClientInfoView' => $sClientInfoView));
} else {
	$this->widget('application.modules.account.components.ClientKreddyLineInfoWidget', array('sClientInfoView' => $sClientInfoView));
}
?>

<?= $sPassFormRender // отображаем форму запроса SMS-пароля ?>

<?
$this->widget('application.modules.account.components.BannerWidget');
?>