<?php

/**
 * @property SiteParamValue $param
 * @property array $params
 */
class SiteParams
{


	const CTIME_HOUR = 3600;
	const CTIME_DAY = 86400;
	const CTIME_WEEK = 604800;
	const CTIME_MONTH = 2592000;
	const CTIME_TWO_MONTH = 5184000;
	const CTIME_YEAR = 31104000;
	const EMPTY_DATE = '0000-00-00';
	const EMPTY_DATETIME = '0000-00-00 00:00:00';
	const EMPTY_TIME = '00:00:00';

	const U_ACTION_TYPE_FORM = 1;
	const U_ACTION_TYPE_SMS = 2;
	const U_ACTION_TYPE_BLOCK_FORM = 8;
	const U_ACTION_TYPE_BLOCK_SMS = 9;

	/**
	 * Время проверки для антибота, в минутах
	 */
	const ANTIBOT_SMS_TIME_SHORT = 10; //время проверки для короткого периода
	const ANTIBOT_SMS_TIME_LONG = 60; //время проверки длинного периода
	const ANTIBOT_SMS_TIME_BLOCK = 1440; //время блока

	const ANTIBOT_FORM_TIME_SHORT = 10; //время проверки для короткого периода
	const ANTIBOT_FORM_TIME_LONG = 60; //время проверки длинного периода
	const ANTIBOT_FORM_TIME_BLOCK = 1440; //время блока
	/**
	 * Количество СМС за период
	 */
	const ANTIBOT_SMS_IN_SHORT = 2; //количество за короткий период
	const ANTIBOT_SMS_IN_LONG = 3; //количество за длинный период

	/**
	 * Количество анкет за период
	 */

	const ANTIBOT_FORM_IN_SHORT = 3; //количество за короткий период
	const ANTIBOT_FORM_IN_LONG = 5; //количество за длинный период
	/**
	 * максимальное число попыток ввода кода из SMS
	 */
	const MAX_SMSCODE_TRIES = 3;

	/**
	 * длина кода подтверждения, отправляемого по SMS
	 */
	const C_SMSCODE_LENGTH = 6;

	public static $iTimeNow = null;

	/**
	 * игнорирование выгрузки данных в буфер 1С
	 * @var bool
	 */
	private $_bIgnore1cBuffer = false;


	/**
	 * количество цифр в номере телефона
	 */
	const C_PHONE_LENGTH = 10;

	const C_NUMERIC_CODE_MIN_LENGTH = 4;

	/**
	 * длины серии и номера паспорта
	 */
	const C_PASSPORT_S_LENGTH = 4;
	const C_PASSPORT_N_LENGTH = 6;

	/**
	 * почтовый индекс - длина
	 */
	const C_POST_INDEX_LENGTH = 6;

	/**
	 * допустимая длина для ИНН
	 */
	const C_INN_LENGTH = 12;

	// время жизни авторизации
	public $iLoginTime = self::CTIME_DAY;

	/**
	 * ссылка на сайт
	 * @var string
	 */
	public $link;

	/**
	 * @var SiteParamValue
	 */
	public $param;

	public $ckey = null;

	/**
	 * получить текущее время
	 * @return int|null
	 */
	public static function getTime()
	{

		return (null === self::$iTimeNow)
			? time()
			: self::$iTimeNow;

	}


	public function getAbsLink($sLocalLink = null)
	{

		return $this->getHttpHost() . $sLocalLink;

	}

	public function getCKeyLink($key)
	{

		return $this->getHttpHost() . '/ckey/' . $key . '/';

	}

	public function getHttpHost()
	{

		if (!$this->isConsole()) {
			return Yii::app()->request->getHostInfo();
		}

		return $this->param->link;

	}

	public function redirectReturnUrl(CController $controller)
	{

		$sReturnUrl = null;

		$sBackUrl = urldecode(Yii::app()->request->getParam('back'));

		if ($sBackUrl) {
			if (strpos($sBackUrl, '/') === 0) {
				$sReturnUrl = $sBackUrl;
			}
		}
		if (null === $sReturnUrl) {
			$sReturnUrl = Yii::app()->user->getReturnUrl();
		}
		if (!$sReturnUrl) {
			$sReturnUrl = Yii::app()->getHomeUrl();
		}
		$sReturnUrl = preg_replace("/\/[^\/]+.php/", '/$1', $sReturnUrl);
		$controller->redirect($sReturnUrl);

	}

	public function setRedirectUrl($url = null)
	{
		if (null === $url) {
			$url = Yii::app()->request->getUrl();
		}
		Yii::app()->user->setReturnUrl($url);
	}


	/*public function getGravatarHtml($size, SiteAdminUser $oUser = null)
	{

		$src = '/i/gravatar.jpg';

		if (Yii::app()->user->getIsGuest()) {
			$name = 'пакман';
		} else {
			$oUser = ($oUser)
				? $oUser
				: Yii::app()->user->getModel();
			$name = $oUser->getName();
		}

		return CHtml::image($src, $name, array(
			'width' => $size,
			'height' => $size,
			'class' => 'img-polaroid gravatar-icon',
		));

	}*/


	public function isCurrentUrl($url, $bStrong = false)
	{

		if (!$bStrong) {
			return (strpos(Yii::app()->request->getUrl(), $url) === 0);
		} else {
			return (Yii::app()->request->getUrl() == $url);
		}

	}

	public function isIndexPage()
	{

		return $this->isCurrentUrl('/', true) || $this->isCurrentUrl('/index');

	}

	public function init()
	{

		$this->param = new SiteParamValue($this->params);

		if (Yii::app()->request->getParam('ckey')) {
			$this->ckey = trim(Yii::app()->request->getParam('ckey'));
		}

	}

	public function isConsole()
	{

		return php_sapi_name() == 'cli';

	}

	public function isLocalServer()
	{

		return (
			!empty($_SERVER['REMOTE_ADDR']) &&
				(
					strpos($_SERVER['REMOTE_ADDR'], '192.') === 0 ||
					strpos($_SERVER['REMOTE_ADDR'], '10.') === 0 ||
					strpos($_SERVER['REMOTE_ADDR'], '127.') === 0
				)
		);

	}


	public function doCreateDir($sDirPath)
	{

		if (file_exists($sDirPath) && is_dir($sDirPath)) {
			return true;
		}

		if (file_exists($sDirPath) && is_dir($sDirPath)) {
			return true;
		}

		$aDirPath = explode('/', trim($sDirPath, '/'));
		if (count($aDirPath) > 0) {
			array_pop($aDirPath);
		}

		$sCreateDirPath = '/' . implode('/', $aDirPath);

		if (!$this->doCreateDir($sCreateDirPath)) {
			return false;
		}

		$umask = umask(0);

		$bReturn = true;
		if (!mkdir($sDirPath, 0775, true)) {
			$bReturn = false;
		}

		umask($umask);
		return $bReturn;

	}

		/**
	 * strtotime
	 *
	 * @param string|null $sTime
	 * @return int
	 */
	public static function strtotime($sTime = null)
	{
		if ($sTime === null) {
			return self::getTime();
		}

		$mDateTime = ($sTime);

		if ($mDateTime > 0) {
			return strtotime($mDateTime);
		}

		return 0;
	}

	/**
	 * @param int $iTime
	 * @return string
	 */
	public static function timetostr($iTime = null)
	{
		if ($iTime === null) {
			$iTime = self::getTime();
		}

		return date("Y-m-d H:i:s", $iTime);
	}

	/**
	 * Возвращает дату в формате Y-m-d из строки datetime
	 *
	 * @param string $sDateTime
	 * @return mixed
	 */
	public static function getDateFromDateTime($sDateTime)
	{
		return current(explode(' ', $sDateTime));
	}

	/**
	 * Возвращает время в формате H:i:s из строки date time
	 *
	 * @param string $sDateTime
	 *
	 * @return mixed
	 */
	public static function getTimeFromDateTime($sDateTime)
	{
		$aDateTime = explode(' ', $sDateTime);
		return end($aDateTime);
	}

	/**
	 * Возвращает переданные дату и время, дополненные до 23:59:59 этой даты
	 * (01.01.1970 12:21:36 -> 01.01.1970 23:59:59).
	 * Принимает и возвращает дату-время в виде строки или в виде числа.
	 * Может принимать такие аргументы strtotime() как "-1 day"
	 *
	 * @param mixed $mDatetime
	 * @return bool|int|null|string
	 */
	public static function getDayEndFromDatetime($mDatetime)
	{
		$mReturn = null;
		if (is_string($mDatetime)) {
			$mReturn = date('Y-m-d 23:59:59', strtotime($mDatetime, self::getTime()));
		} elseif (is_int($mDatetime)) {
			$mReturn = strtotime(date('Y-m-d 23:59:59', $mDatetime));
		}
		return $mReturn;
	}

	/**
	 * Возвращает переданные дату и время, урезанные до 00:00:00 этой даты
	 * (01.01.1970 12:21:36 -> 01.01.1970 00:00:00).
	 * Принимает и возвращает дату-время в виде строки или в виде числа.
	 * Может принимать такие аргументы strtotime() как "-1 day"
	 *
	 * @param mixed $mDatetime
	 * @return bool|int|null|string
	 */
	public static function getDayBeginningFromDatetime($mDatetime)
	{
		$mReturn = null;
		if (is_string($mDatetime)) {
			$mReturn = date('Y-m-d 00:00:00', strtotime($mDatetime, self::getTime()));
		} elseif (is_int($mDatetime)) {
			$mReturn = strtotime(date('Y-m-d 00:00:00', $mDatetime));
		}
		return $mReturn;
	}


	/**
	 * @param $sCommand
	 * @return string команда для консоли
	 */
	public function getShellCommand( $sCommand )
	{
		return 'php ' . Yii::app()->getBasePath() . '/../cron/cron.php ' . $sCommand;
	}

	/**
	 * @param $sCommand
	 * @return string результат выполнения команды system
	 */
	public function execShellCommand( $sCommand )
	{
		ob_start();
		system( $this->getShellCommand( $sCommand ) );
		return ob_get_clean();
	}

	/**
	 * @param $sCommand
	 * @return string команда для консоли по тестовому крон-конфигу
	 */
	public function getTestShellCommand( $sCommand )
	{
		return 'php ' . Yii::app()->getBasePath() . '/../cron/cron.test.php ' . $sCommand;
	}

	/**
	 * @param $sCommand
	 * @return string результат выполнения команды system по тестовому крон-конфигу
	 */
	public function execTestShellCommand( $sCommand )
	{
		ob_start();
		system( $this->getTestShellCommand( $sCommand ) );
		return ob_get_clean();
	}



	/**
	 * содержимое страницы по url
	 * @param $sUrl
	 * @return string
	 */
	public function getUrlContents($sUrl)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $sUrl);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		return curl_exec($ch);
	}

}

/**
 */
class SiteParamValue
{

	public $hostname;
	public $link;

	public function __construct($aAttributes)
	{
		foreach ($aAttributes as $key => $value) {
			$this->$key = $value;
		}
	}

	public function __get($name)
	{
		return $this->p($name);
	}

	private function p($var)
	{

		static $aParams = null;
		if (null == $aParams) {
			$aTmpParams = Yii::app()->db->createCommand()->select()->from('site_params')->queryAll();
			$aParams = array();
			foreach ($aTmpParams as $a) {
				$aParams[$a['var']] = $a['value'];
			}
		}
		return !empty($aParams[$var])
			? $aParams[$var]
			: null;

	}

}
