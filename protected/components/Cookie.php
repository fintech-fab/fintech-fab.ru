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
	 * @param $cookieName string
	 * @param $attributeName string
	 * @param $checkValue string
	 *
	 * @return bool
	 */
	public static function compareDataInCookie($cookieName,$attributeName,$checkValue)
	{
		$dataInCookie = false;
		if(isset(Yii::app()->request->cookies[$cookieName]))
		{
			$cookie = Yii::app()->request->cookies[$cookieName];

			$sDecrypt=CryptArray::decryptVal($cookie);//декриптим куку
			$aDecrypt= @unserialize($sDecrypt);
			if(($aDecrypt && $aDecrypt[$attributeName]) && ($checkValue == $aDecrypt[$attributeName]))
			{
				$dataInCookie=true;
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
		if(isset(Yii::app()->request->cookies[$cookieName]))
		{
			$cookie = Yii::app()->request->cookies[$cookieName];

			$sDecrypt=CryptArray::decryptVal($cookie);//декриптим куку
			$aDecrypt= @unserialize($sDecrypt);
			return $aDecrypt;
		}
		return false;
	}

	/**
	 * Шифрует и сериализует массив $data и записывает в куку $cookieName
	 *
	 * @param $cookieName string
	 * @param $data array
	 */
	public static function saveDataToCookie($cookieName,$data)
	{
		$sEncrypt = serialize($data);
		$cookieData = CryptArray::encryptVal($sEncrypt);

		$cookie = new CHttpCookie($cookieName, $cookieData);
		$cookie->expire = time()+60*60*2;
		Yii::app()->request->cookies[$cookieName] = $cookie;
	}
}
