<?php

/**
 * Class CitiesRegions
 */
class CitiesRegions
{
	/**
	 * Возвращает id региона по названию
	 *
	 * @param $sRegion
	 *
	 * @return integer|null
	 */
	public static function getRegionIdByName($sRegion)
	{
		if (empty($sRegion)) {
			return null;
		}
		$oQuery = Yii::app()->db->createCommand()->select('id')
			->from('tbl_regions')
			->where("`name` LIKE :region", array(':region' => $sRegion . '%'));

		$aElem = $oQuery->queryRow();
		if (isset($aElem['id'])) {
			return $aElem['id'];
		}

		return null;
	}

	/**
	 * Выводит города с регионами, похожие на введённый запрос (если он есть)
	 *
	 * @param string $sInput
	 *
	 * @return array
	 */
	public static function getAllCitiesAndRegions($sInput = "")
	{
		$oQuery = Yii::app()->db->createCommand()->select('city_id, city, region')
			->from('tbl_geo__cities');

		if (!empty($sInput)) {
			$oQuery = $oQuery
				->where("`city` LIKE :input", array(':input' => $sInput . '%'));
		}

		$aCities = $oQuery->limit(10)->queryAll();

		foreach ($aCities as &$oElem) {
			$oElem['id'] = $oElem['city_id'];
			unset($oElem['city_id']);
			$oElem['cityName'] = $oElem['city'];
			$oElem['cityAndRegion'] = $oElem['city'];
			$oElem['cityAndRegion'] .= ($oElem['city'] != $oElem['region']) ? (', ' . $oElem['region']) : '';
			unset($oElem['city']);
			//unset($oElem['region']);
		}


		return $aCities;
	}

	/**
	 * @param $iId
	 *
	 * @return bool|string
	 */
	public static function getCityAndRegionById($iId)
	{
		$oQuery = Yii::app()->db->createCommand()->select('city, region')
			->from('tbl_geo__cities')
			->where("`city_id`=:id", array(":id" => $iId));

		$oElem = $oQuery->queryRow();

		if ($oElem) {
			return $oElem['city'] . (($oElem['city'] != $oElem['region']) ? (', ' . $oElem['region']) : '');
		}

		return false;
	}

	/**
	 * @param $iId
	 *
	 * @return bool|string
	 */
	public static function getCityNameById($iId)
	{
		$oQuery = Yii::app()->db->createCommand()->select('city, region')
			->from('tbl_geo__cities')
			->where("`city_id`=:id", array(":id" => $iId));

		$oElem = $oQuery->queryRow();

		if ($oElem) {
			return $oElem['city'];
		}

		return false;
	}

}


