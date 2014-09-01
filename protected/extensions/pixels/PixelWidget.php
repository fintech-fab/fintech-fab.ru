<?php
Yii::import('ext.pixels.PixelCookie');

/**
 * Class PixelWidget
 */
class PixelWidget extends CWidget
{

	public function init()
	{

	}

	public function run()
	{

		$oCookie = Yii::app()->request->cookies['lead_generator'];

		if ($oCookie && isset($oCookie->value['sUid']) && array_key_exists($oCookie->value['sUid'], PixelFilter::$aAdditionalFields)) {

			$iOrderId = $oCookie->value['iOrderId'];
			$oLeadsHistory = LeadsHistory::model()->findByPk($iOrderId);
			if ($oLeadsHistory) {
				$oLeadsHistory->flag_showed = 1;
				$oLeadsHistory->dt_show = date('Y-m-d H:i:s', SiteParams::getTime());
				$oLeadsHistory->save();
			}

			Yii::app()->request->cookies->remove('lead_generator');

			$this->render($oCookie->value['sUid'], ['aParams' => $oCookie->value]);
		}
	}

} 