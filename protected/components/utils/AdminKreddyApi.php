<?php
/**
 * Created by JetBrains PhpStorm.
 * User: popov
 * Date: 28.08.13
 * Time: 12:02
 * To change this template use File | Settings | File Templates.
 */

class AdminKreddyApi
{


	public static function getClientToken($sPhone, $sPassword)
	{
		//заглушка

		if ($sPhone == "9154701913" && $sPassword == "159753") {
			$sToken = "159753";
			Yii::app()->session['token'] = $sToken;

			return $sToken;
		} else {
			return false;
		}


	}

	public static function getClientData()
	{
		$sToken = Yii::app()->session['token'];
		$aData = array();
		if (!empty($sToken)) {
			//тут типа запрос данных по токену
			$aData = self::getData($sToken);
		} else {
			//тут вызываем процесс переидентификации юзера
		}

		return $aData;
	}

	private static function getData($sToken)
	{
		//тут curl запрашивает данные

		//заглушка
		$aData = array("balance" => "-10000");

		return $aData;
	}

	private static function getSessionToken()
	{
		return Yii::app()->session['token'];
	}

}