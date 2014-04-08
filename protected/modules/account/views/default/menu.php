<?php
/**
 * @var $this Controller
 */


if (Yii::app()->adminKreddyApi->isSubscriptionOldType()) {
	$this->widget('application.modules.account.components.AccountMenuWidget');
} else {
	$this->widget('application.modules.account.components.AccountKreddyLineMenuWidget');
}
