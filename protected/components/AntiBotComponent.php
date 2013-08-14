<?php
/**
 * Компонент AntiBot занимается сохранением информации о запросах пользователя
 * и принятием решений о блокировке операций
 */

class AntiBotComponent
{

	public $aIpExceptions = array('46.38.98.106','46.38.98.107','46.38.98.108');

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
		if(self::ipInExceptions()){
			return true;
		}

		$sIP = self::getUserIP();
		$iTypeSms = SiteParams::U_ACTION_TYPE_SMS;
		$iTypeBlock = SiteParams::U_ACTION_TYPE_BLOCK_SMS;

		$iTimeShort = SiteParams::ANTIBOT_SMS_TIME_SHORT;
		$iTimeLong = SiteParams::ANTIBOT_SMS_TIME_LONG;
		$iTimeBlock = SiteParams::ANTIBOT_SMS_TIME_BLOCK;

		$iSmsInShort = SiteParams::ANTIBOT_SMS_IN_SHORT;
		$iSmsInLong = SiteParams::ANTIBOT_SMS_IN_LONG;

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
	 * Проверка, может ли пользователь заполнить анкету
	 */

	public static function checkFormRequest()
	{
		if(self::ipInExceptions())
		{
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

	public static function checkIsBanned()
	{

		if(self::ipInExceptions())
		{
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
		if (self::checkFormRequest()&&!self::ipInExceptions()) {
			UserActionsLog::addNewAction($sIP, SiteParams::U_ACTION_TYPE_FORM);
		}
	}

	/**
	 * @return string
	 */
	private function getUserIP()
	{
		return Yii::app()->request->getUserHostAddress();
	}

	private function ipInExceptions()
	{
		if(in_array($this->getUserIP(),$this->aIpExceptions )){
			return true;
		}
		return false;
	}
}
