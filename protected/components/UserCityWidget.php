<?php
class UserCityWidget extends CWidget
{
	public $sCityName = "не определён";

	public function run()
	{
		$oCityIdCookie = Yii::app()->request->cookies['city_id'];

		if (!empty($oCityIdCookie)) { // если в куках есть id города, выводим его
			$this->sCityName = CitiesRegions::getCityNameById($oCityIdCookie->value);
		} elseif (ids_ipGeoBase::getCityByIP()) { // если удалось определить город по ip
			$this->sCityName = ids_ipGeoBase::getCityByIP() . ", " . ids_ipGeoBase::getRegionByIP();
		}

		$this->render('user_city_widget');
	}
}
