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

		if (Yii::app()->session['alertShown']) {
			return;
		}

		Yii::app()->clientScript->registerScript('userAlert', '
			doOpenModalFrame(\'' . Yii::app()->createUrl('/pages/viewPartial/alert') . '\',\'Внимание!\');
			', CClientScript::POS_READY);


		Yii::app()->session['alertShown'] = true;
	}
}
