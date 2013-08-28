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
		$aData = array('errorCode' => 1, 'first_name' => '', 'last_name' => '', 'third_name' => '', 'balance' => '');
		if (!empty($sToken)) {
			//тут типа запрос данных по токену
			$aGetData = self::getData('base', $sToken);
			$aData = array_merge($aData, $aGetData);
		} else {
			$aData = false;
		}

		return $aData;
	}

	public static function getClientName()
	{
		$sToken = Yii::app()->session['token'];
		$aData = array();
		if (!empty($sToken)) {
			//тут типа запрос данных по токену
			$aData = self::getData('name', $sToken);
		} else {
			$aData = false;
		}

		return $aData;
	}

	private static function getData($sType, $sToken)
	{
		//TODO сделать константы ерроркодов
		$aData = array('errorCode' => 1);
		//тут curl запрашивает данные

		//заглушка
		switch ($sType) {
			case 'base':
				$aData = array('errorCode' => 0, 'balance' => '-10000', 'first_name' => 'Василий', 'last_name' => 'Пупкин', 'third_name' => 'Иванович');
				break;
			case 'secure':
				$aData = array('');
				break;
		}


		return $aData;
	}

	private static function getSessionToken()
	{
		return Yii::app()->session['token'];
	}

}