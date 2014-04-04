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
$this->widget('application.modules.account.components.ClientInfoWidget', array('sClientInfoView' => $sClientInfoView));
?>

<?= $sPassFormRender // отображаем форму запроса SMS-пароля ?>

<?
$this->widget('application.modules.account.components.AppInfoWidget');
?>