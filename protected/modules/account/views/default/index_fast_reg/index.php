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

$this->widget('application.modules.account.components.ClientKreddyLineInfoWidget', array('sClientInfoView' => $sClientInfoView));

?>

<?php
$this->renderPartial('need_continue_reg');
