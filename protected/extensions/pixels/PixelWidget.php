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

			return;
		}

		// Отображение пикселя по старой схеме. Раньше мы сохраняли куку TrackingID, теперь это кука lead_generator
		$oCookie = Yii::app()->request->cookies['TrackingID'];
		if ($oCookie) {
			$sUid = (string)$oCookie;

			if (!$sUid || !array_key_exists($sUid, PixelFilter::$aAdditionalFields)) {
				Yii::log('Не найден лидогенератор: ' . $sUid, CLogger::LEVEL_INFO);

				return;
			}

			$oLead = new LeadsHistory();
			$oLead->lead_name = $sUid;
			$oLead->dt_add = SiteParams::EMPTY_DATETIME;
			$oLead->save();


			$this->render($sUid, ['iOrderId' => $oLead->id]);

			return;
		}

	}

} 