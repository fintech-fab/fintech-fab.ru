<?php
/**
 * Class ids_ipGeoBase
 */
class ids_ipGeoBase
{

	/** @var boolean */
	public static $bEncode = false;

	/**
	 * массив для кеширования запросов
	 *
	 * @var array
	 */
	public static $aResultCache = array();

	/**
	 * название округа по IP
	 *
	 * @param string $ip
	 *
	 * @return string
	 * @example $sDistrictName = ids_ipGeoBase::getDistrictByIP('62.109.164.209');
	 */
	public static function getDistrictByIP($ip = null)
	{

		if (null === $ip) {
			$ip = Yii::app()->request->getUserHostAddress();
		}

		$aResult = self::request($ip);

		if (!$aResult || isset($aResult['error'])) {
			return false;
		}

		if (isset($aResult['district'])) {
			if (self::$bEncode) {
				return iconv('utf8', 'cp1251', $aResult['district']);
			} else {
				return $aResult['district'];
			}
		}

		return false;

	}

	/**
	 * название региона по IP
	 *
	 * @param string $ip
	 *
	 * @return string
	 * @example $sRegionName = ids_ipGeoBase::getRegionByIP('62.109.164.209');
	 */
	public static function getRegionByIP($ip = null)
	{

		if (null === $ip) {
			$ip = Yii::app()->request->getUserHostAddress();
		}

		$aResult = self::request($ip);

		if (!$aResult || isset($aResult['error'])) {
			return false;
		}

		if (isset($aResult['region'])) {
			if (self::$bEncode) {
				return iconv('utf8', 'cp1251', $aResult['region']);
			} else {
				return $aResult['region'];
			}
		}

		return false;

	}

	/**
	 * название города по IP
	 *
	 * @param string $ip
	 *
	 * @return string
	 * @example $sCityName = ids_ipGeoBase::getCityByIP('62.109.164.209');
	 */
	public static function getCityByIP($ip = null)
	{

		if (null === $ip) {
			$ip = Yii::app()->request->getUserHostAddress();
		}

		$aResult = self::request($ip);

		if (!$aResult || isset($aResult['error'])) {
			return '1';
		}

		if (isset($aResult['city'])) {
			if (self::$bEncode) {
				return iconv('utf8', 'cp1251', $aResult['city']);
			} else {
				return $aResult['city'];
			}
		}

		return false;

	}

	/**
	 * @param $sIp
	 *
	 * @return array
	 */
	private static function request($sIp)
	{
		$long_ip = ip2long($sIp);

		$sSqlRequest = "SELECT * FROM `tbl_geo__base` WHERE `long_ip1`<='$long_ip' AND `long_ip2`>='$long_ip' LIMIT 1";

		$aResult = Yii::app()->db->createCommand($sSqlRequest)->queryRow();

		$aReturn = array('error'=>true);

		if (!empty($aResult)) {
			if (!empty($aResult['city_id'])) {
				$sSqlRequest = "SELECT * FROM `tbl_geo__cities` WHERE `city_id`='$aResult[city_id]' LIMIT 1";
				$aResult2 = Yii::app()->db->createCommand($sSqlRequest)->queryRow();
				if (!empty($aResult2)) {
					$aReturn = array('country' => $aResult['country']);
					$aReturn = array_merge($aReturn, $aResult2);
				}

			} else {
				$aReturn = array(
					'country'  => $aResult['country'],
				);
			}
		}

		return $aReturn;

	}
}


