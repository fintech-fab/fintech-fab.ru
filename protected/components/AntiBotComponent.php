<?php
/**
 * Компонент AntiBotComponent занимается сохранением информации о запросах пользователя
 * и принятием решений о блокировке операций
 */

class AntiBotComponent
{
	/**
	 * @return bool
	 *
	 * Проверка, можно ли сделать еще 1 запрос кода по SMS
	 */


	public static function checkSmsRequest()
	{
		//echo '<pre>' . ""; CVarDumper::dump(strtotime($t)); echo '</pre>';
		//echo '<pre>' . ""; CVarDumper::dump(date('Y-m-d H:i:s', strtotime($t))); echo '</pre>';


		$sIp = self::getUserIp();
		$iTypeSms = SiteParams::U_ACTION_TYPE_SMS;
		$iTypeBlock = SiteParams::U_ACTION_TYPE_BLOCK;

		$iTimeShort = SiteParams::ANTIBOT_TIME_SHORT;
		$iTimeLong = SiteParams::ANTIBOT_TIME_LONG;
		$iTimeBlock = SiteParams::ANTIBOT_TIME_BLOCK;

		//запрашиваем наличие блокировки за сутки
		$iActionCount = UserActionsLog::countRecordsByIpTypeTime($sIp, $iTypeBlock, $iTimeBlock);
		if($iActionCount>0){ //TODO: числовые константы тоже вынести в SiteParams?
			return false;
		}

		$iActionCount = UserActionsLog::countRecordsByIpTypeTime($sIp, $iTypeSms, $iTimeLong);
		if($iActionCount>=3){
			UserActionsLog::addNewAction($sIp, $iTypeBlock);
			return false;
		}

		$iActionCount = UserActionsLog::countRecordsByIpTypeTime($sIp, $iTypeSms, $iTimeShort);
		if($iActionCount>=2)
		{
			return false;
		}

		return true;
	}

	/**
	 * Добавление в лог еще 1 запроса кода по SMS
	 */

	public static function addSmsRequest()
	{
		$sIp = self::getUserIp();
		if (self::checkSmsRequest()) {
			UserActionsLog::addNewAction($sIp, SiteParams::U_ACTION_TYPE_SMS);
		}
	}


	/**
	 * Проверка, может ли пользователь заполнить анкету
	 */

	public static function checkFormRequest()
	{

	}

	/**
	 * Добавление в лог еще 1 запроса на заполнение анкеты
	 */

	public static function addFormRequest()
	{

	}


	private static function getUserIp()
	{
		return Yii::app()->request->getUserHostAddress();
	}
}
