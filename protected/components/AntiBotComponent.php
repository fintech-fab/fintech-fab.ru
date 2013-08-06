<?php
/**
 * Компонент AntiBot занимается сохранением информации о запросах пользователя
 * и принятием решений о блокировке операций
 */

class AntiBot
{
	/**
	 * Проверка, можно ли сделать еще 1 запрос кода по SMS
	 */

	public static function checkSmsRequest()
	{
		//echo '<pre>' . ""; CVarDumper::dump(strtotime($t)); echo '</pre>';
		//echo '<pre>' . ""; CVarDumper::dump(date('Y-m-d H:i:s', strtotime($t))); echo '</pre>';

		$sIp = $sIp = Yii::app()->request->getUserHostAddress();
		$oUserAction = UserActionsLog::getActionByIpAndType($sIp, 2); //type 2 - SMS code, TODO: вынести в константы
		if ($oUserAction) {
			$iCount = $oUserAction->count;
			$sTimestamp = $oUserAction->dt_add;
			$iTimestamp = strtotime($sTimestamp);
			$iDiffTime = time() - $iTimestamp;


			//TODO: вынести время и число в константы
			//если уже есть 2 запроса за 10 минут - ругаемся
			if ($iDiffTime < 600 && $iCount >= 2) {
				return false;
			//если уже есть 3 или более запросов за час - умножаем счетчик и ругаемся
			} elseif ($iDiffTime < SiteParams::CTIME_HOUR && $iCount >= 3) {
				$oUserAction->count++;
				$oUserAction->save();
				return false;
			}
			//если в течение часа запросов не было, и счетчик менее 4, то обнуляем счетчик и радуемся
			elseif ($iDiffTime > SiteParams::CTIME_HOUR && $iCount < 4)
			{
				$oUserAction->count=0;
				$oUserAction->save();
				return true;
			}
			//если в течение суток не было запросов, и количество запросов более 0, то обнуляем счетчик
			elseif ($iDiffTime > SiteParams::CTIME_DAY && $iCount > 0)
			{
				$oUserAction->count=0;
				$oUserAction->save();
			}
		}
		return true;
	}

	/**
	 * Добавление в лог еще 1 запроса кода по SMS
	 */

	public static function addSmsRequest()
	{
		$sIp = $sIp = Yii::app()->request->getUserHostAddress(); //TODO: вынести в getUserIp
		if (self::checkSmsRequest()) {
			UserActionsLog::addNewAction($sIp, 2); //type 2 - SMS code
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

}