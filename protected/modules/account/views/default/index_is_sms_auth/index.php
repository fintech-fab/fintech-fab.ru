<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $sClientInfoRender
 * @var $sIdentifyRender
 * @var $sClientInfoView
 */

$this->breadcrumbs = array(
	$this->module->id,
);

?>

<?
if (SiteParams::getIsIvanovoSite()) {
	$this->widget('application.modules.account.components.ClientInfoIvanovoWidget', array('sClientInfoView' => $sClientInfoView));
} else {
	$this->widget('application.modules.account.components.ClientInfoWidget', array('sClientInfoView' => $sClientInfoView));
}

?>
	<br />
<?= $sIdentifyRender ?>
	<br />
<?
$this->widget('application.modules.account.components.AppInfoWidget');
?>