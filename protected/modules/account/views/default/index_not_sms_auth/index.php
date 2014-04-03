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

<?php
$this->widget('application.modules.account.components.ClientInfoWidget', array('sClientInfoView' => $sClientInfoView));
?>

<?= $sPassFormRender // отображаем форму запроса SMS-пароля ?>

<?php $this->renderPartial('app_info'); ?>