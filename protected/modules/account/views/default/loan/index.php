<?php
/**
 * @var $this DefaultController
 * @var $oModel
 * @var $sView
 */


if (Yii::app()->adminKreddyApi->isSubscriptionOldType()) {
	$this->widget('application.modules.account.components.LoanWidget', array('sView' => $sView, 'oModel' => $oModel));
} else {
	$this->widget('application.modules.account.components.KreddyLineLoanWidget', array('sView' => $sView, 'oModel' => $oModel));
}