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
if (Yii::app()->adminKreddyApi->isSubscriptionOldType()) {
	$this->widget('application.modules.account.components.ClientInfoWidget', array('sClientInfoView' => $sClientInfoView));
} else {
	$this->widget('application.modules.account.components.ClientKreddyLineInfoWidget', array('sClientInfoView' => $sClientInfoView));
}
?>
	<br />
<?= $sIdentifyRender ?>
	<br />
<?
$this->widget('application.modules.account.components.AppInfoWidget');
?>