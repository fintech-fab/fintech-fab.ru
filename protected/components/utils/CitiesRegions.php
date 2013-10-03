<?php
/**
 * Class CitiesRegions
 */

class CitiesRegions
{
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
			$oElem['city'] .= ($oElem['city'] != $oElem['region']) ? (', ' . $oElem['region']) : '';
			unset($oElem['region']);
		}

		return $aCities;
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
			return $oElem['city'] . (($oElem['city'] != $oElem['region']) ? (', ' . $oElem['region']) : '');
		}

		return false;
	}
}


