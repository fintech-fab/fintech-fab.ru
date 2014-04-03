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

<?php
if (SiteParams::getIsIvanovoSite()) {
	$this->widget('application.modules.account.components.ClientInfoIvanovoWidget', array('sClientInfoView' => $sClientInfoView));
} else {
	$this->widget('application.modules.account.components.ClientInfoWidget', array('sClientInfoView' => $sClientInfoView));
}

?>
	<br />
<?= $sIdentifyRender ?>
	<br />
<?php $this->renderPartial('app_info'); ?>