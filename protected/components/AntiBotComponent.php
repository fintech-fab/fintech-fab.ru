<?php
/**
 * Компонент AntiBot занимается сохранением информации о запросах пользователя
 * и принятием решений о блокировке операций
 */

class AntiBotComponent
{

	public function init()
	{

	}

	/**
	 * Проверка, можно ли сделать еще 1 запрос кода по SMS
	 *
	 * @return bool
	 */
	public static function checkSmsRequest()
	{
		if (self::ipInExceptions()) {
			return true;
		}

		$sIP = self::getUserIP();
		$iTypeSms = SiteParams::U_ACTION_TYPE_SMS;
		$iTypeBlock = SiteParams::U_ACTION_TYPE_BLOCK_SMS;

		//время задается в минутах
		$iTimeShort = SiteParams::ANTIBOT_SMS_TIME_SHORT; //короткий период
		$iTimeLong = SiteParams::ANTIBOT_SMS_TIME_LONG; //длительный период
		$iTimeBlock = SiteParams::ANTIBOT_SMS_TIME_BLOCK; //время блокировки

		$iSmsInShort = SiteParams::ANTIBOT_SMS_IN_SHORT; //допустимое число СМС за короткий период
		$iSmsInLong = SiteParams::ANTIBOT_SMS_IN_LONG; //допустимое число СМС за длительный период

		//запрашиваем наличие блокировки за сутки
		$iActionCount = UserActionsLog::countRecordsByIpTypeTime($sIP, $iTypeBlock, $iTimeBlock);
		if ($iActionCount > 0) {
			return false;
		}

		$iActionCount = UserActionsLog::countRecordsByIpTypeTime($sIP, $iTypeSms, $iTimeLong);
		if ($iActionCount >= $iSmsInLong) {
			UserActionsLog::addNewAction($sIP, $iTypeBlock);

			return false;
		}

		$iActionCount = UserActionsLog::countRecordsByIpTypeTime($sIP, $iTypeSms, $iTimeShort);
		if ($iActionCount >= $iSmsInShort) {
			return false;
		}

		return true;
	}

	/**
	 * Добавление в лог еще 1 запроса кода по SMS
	 */

	public static function addSmsRequest()
	{
		$sIP = self::getUserIP();
		if (self::checkSmsRequest()) {
			UserActionsLog::addNewAction($sIP, SiteParams::U_ACTION_TYPE_SMS);
		}
	}

	/**
	 * Добавление в лог записи об ошибке при попытке привязки карты
	 *
	 * @param $sUserName
	 *
	 */
	public static function addCardError($sUserName)
	{
		UserActionsLog::addNewAction($sUserName, SiteParams::U_ACTION_TYPE_CARD_VERIFY);
	}

	/**
	 * Проверка, можно ли еще раз пробовать привязать карту
	 * Проверяет число попыток с текущего момента и на указанное число минут назад,
	 * если число попыток менее заданного - разрешает попытку.
	 *
	 * @param $sUserName
	 *
	 * @return bool
	 */

	public static function getIsAddCardCanRequest($sUserName)
	{
		if (self::ipInExceptions()) {
			return true;
		}

		$iType = SiteParams::U_ACTION_TYPE_CARD_VERIFY;
		$iTime = SiteParams::ANTIBOT_CARD_ADD_TIME;

		$iActionCount = UserActionsLog::countRecordsByIpTypeTime($sUserName, $iType, $iTime);

		//если количество использованных попыток меньше заданного значения - разрешаем попытку
		if ($iActionCount <= SiteParams::ANTIBOT_CARD_ADD_COUNT) {
			return true;
		}

		return false;
	}

	/**
	 * Проверка, может ли пользователь заполнить анкету
	 */

	public static function checkFormRequest()
	{
		if (self::ipInExceptions()) {
			return true;
		}

		$sIP = self::getUserIP();
		$iTypeForm = SiteParams::U_ACTION_TYPE_FORM;
		$iTypeBlock = SiteParams::U_ACTION_TYPE_BLOCK_FORM;

		$iTimeShort = SiteParams::ANTIBOT_FORM_TIME_SHORT;
		$iTimeLong = SiteParams::ANTIBOT_FORM_TIME_LONG;
		$iTimeBlock = SiteParams::ANTIBOT_FORM_TIME_BLOCK;

		$iFormsInShort = SiteParams::ANTIBOT_FORM_IN_SHORT;
		$iFormsInLong = SiteParams::ANTIBOT_FORM_IN_LONG;

		//запрашиваем наличие блокировки за сутки
		$iActionCount = UserActionsLog::countRecordsByIpTypeTime($sIP, $iTypeBlock, $iTimeBlock);
		if ($iActionCount > 0) {
			return false;
		}

		$iActionCount = UserActionsLog::countRecordsByIpTypeTime($sIP, $iTypeForm, $iTimeLong);
		if ($iActionCount >= $iFormsInLong) {
			UserActionsLog::addNewAction($sIP, $iTypeBlock);

			return false;
		}

		$iActionCount = UserActionsLog::countRecordsByIpTypeTime($sIP, $iTypeForm, $iTimeShort);
		if ($iActionCount >= $iFormsInShort) {
			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public static function checkIsBanned()
	{

		if (self::ipInExceptions()) {
			return false;
		}

		$sIP = self::getUserIP();

		$iTypeBlock = SiteParams::U_ACTION_TYPE_BLOCK_FORM;
		$iTimeBlock = SiteParams::ANTIBOT_FORM_TIME_BLOCK;


		$iTypeForm = SiteParams::U_ACTION_TYPE_FORM;
		$iTimeShort = SiteParams::ANTIBOT_FORM_TIME_SHORT;
		$iFormsInShort = SiteParams::ANTIBOT_FORM_IN_SHORT;

		$iActionCount = UserActionsLog::countRecordsByIpTypeTime($sIP, $iTypeBlock, $iTimeBlock);
		if ($iActionCount > 0) {
			return true;
		}


		$iActionCount = UserActionsLog::countRecordsByIpTypeTime($sIP, $iTypeForm, $iTimeShort);
		if ($iActionCount >= $iFormsInShort) {
			return true;
		}

		return false;
	}


	/**
	 * Добавление в лог еще 1 запроса на заполнение анкеты
	 */

	public static function addFormRequest()
	{
		$sIP = self::getUserIP();
		if (self::checkFormRequest()) {
			UserActionsLog::addNewAction($sIP, SiteParams::U_ACTION_TYPE_FORM);
		}
	}

	/**
	 * @return string
	 */
	private static function getUserIP()
	{
		return Yii::app()->request->getUserHostAddress();
	}

	/**
	 * @return bool
	 */
	private static function ipInExceptions()
	{
		$aIpExceptions = array('46.38.98.106', '46.38.98.107', '46.38.98.108', '192.168.10.136', '192.168.10.160');
		if (in_array(self::getUserIP(), $aIpExceptions)) {
			return true;
		}

		return false;
	}
}
