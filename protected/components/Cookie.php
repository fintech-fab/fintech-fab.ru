<?php

/**
 * Компонент Cookie
 * занимается обработкой данных cookies
 * и их шифрованием/дешифрованием
 *
 */
class Cookie
{
	/**
	 * Сравнивает атрибут $attributeName из зашифрованной и сериализованной куки $cookieName со значением $checkValue
	 *
	 * @param $cookieName    string
	 * @param $attributeName string
	 * @param $checkValue    mixed
	 *
	 * @return bool
	 */
	public static function compareDataInCookie($cookieName, $attributeName, $checkValue)
	{
		$dataInCookie = false;
		if (isset(Yii::app()->request->cookies[$cookieName])) {
			$cookie = Yii::app()->request->cookies[$cookieName];

			$sDecrypt = CryptArray::decryptVal($cookie); //декриптим куку
			$aDecrypt = @unserialize($sDecrypt);
			if (($aDecrypt && $aDecrypt[$attributeName]) && ($checkValue == $aDecrypt[$attributeName])) {
				$dataInCookie = true;
			}
		}

		return $dataInCookie;
	}

	/**
	 * Возвращает дешифрованный и десериализованный массив данных, записанный в куку $cookieName,
	 * либо false, если десериализовать не удалось.
	 *
	 * @param $cookieName string
	 *
	 * @return array|bool
	 */
	public static function getDataFromCookie($cookieName)
	{
		if (isset(Yii::app()->request->cookies[$cookieName])) {
			$cookie = Yii::app()->request->cookies[$cookieName];

			$sDecrypt = CryptArray::decryptVal($cookie); //декриптим куку
			$aDecrypt = @unserialize($sDecrypt);

			return $aDecrypt;
		}

		return false;
	}

	/**
	 * Шифрует и сериализует массив $data и записывает в куку $cookieName
	 *
	 * @param      $sCookieName string
	 * @param      $aData       array
	 * @param null $iExpireTime
	 */
	public static function saveDataToCookie($sCookieName, $aData, $iExpireTime = null)
	{
		$sEncrypt = serialize($aData);
		$aCookieData = CryptArray::encryptVal($sEncrypt);

		$aCookieOptions = Yii::app()->session->getCookieParams();
		if (!empty($aCookieOptions['domain'])) {
			$aCookieOptions = array(
				'domain' => $aCookieOptions['domain']
			);
			if (isset($iExpireTime)) {
				$aCookieOptions['expire'] = $iExpireTime;
			}
		} else {
			if (is_null($iExpireTime)) {
				$aCookieOptions = array(
					'expire' => time() + 60 * 60 * 24
				);
			} else {
				$aCookieOptions = array(
					'expire' => $iExpireTime
				);
			}
		}

		$oCookie = new CHttpCookie($sCookieName, $aCookieData, $aCookieOptions);

		Yii::app()->request->cookies[$sCookieName] = $oCookie;
	}

	/**
	 * @param $sCookieName
	 */
	public static function removeCookie($sCookieName)
	{
		Yii::app()->request->cookies->remove($sCookieName);
	}

}
