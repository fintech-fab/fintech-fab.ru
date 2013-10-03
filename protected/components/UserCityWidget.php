<?php
class UserCityWidget extends CWidget
{
	public $sCityName = "не определён";

	public function run()
	{
		$oCityIdCookie = Yii::app()->request->cookies['city_id'];

		/**
		 * TODO прочитать комментарий ниже
		 * в куки писать ВСЮ инфу, в т.ч. название города и региона, и когда в куке инфа есть - не читать из БД!
		 */

		if (!empty($oCityIdCookie)) { // если в куках есть id города, выводим его
			$this->sCityName = CitiesRegions::getCityNameById($oCityIdCookie->value);
		} elseif (ids_ipGeoBase::getCityByIP()) { // если удалось определить город по ip
			$this->sCityName = ids_ipGeoBase::getCityByIP() . ", " . ids_ipGeoBase::getRegionByIP();
		}

		$this->render('user_city_widget');
	}
}
