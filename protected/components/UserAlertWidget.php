<?php

/**
 * Class UserAlertWidget
 */
class UserAlertWidget extends CWidget
{
	public function run()
	{
		if (!Yii::app()->params['bShowAlert']) {
			return;
		}

		$bAlertShown = Cookie::getDataFromCookie('alertShown');

		if ($bAlertShown) {
			return;
		}

		Yii::app()->clientScript->registerScript('userAlert', '
			doOpenModalFrame(\'' . Yii::app()->createUrl('/pages/viewPartial/alert') . '\',\'Внимание!\');
			', CClientScript::POS_READY);

		Cookie::saveDataToCookie('alertShown', array(true), time() + 60 * 60 * 24 * 7);
	}
}
