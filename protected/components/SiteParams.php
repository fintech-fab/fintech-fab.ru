<?php

/**
 * @property SiteParamValue $param
 * @property array          $params
 */
class SiteParams
{
	const DEFAULT_URL = 'https://kreddy.ru'; // url по умолчанию; изменить можно в параметрах
	const DEFAULT_EMAIL = "dev@kreddy.ru"; // email, на который приходят письма по умолчанию; изменить можно в параметрах
	const CTIME_HOUR = 3600;
	const CTIME_DAY = 86400;
	const CTIME_WEEK = 604800;
	const CTIME_MONTH = 2592000;
	const CTIME_TWO_MONTH = 5184000;
	const CTIME_YEAR = 31104000;
	const EMPTY_DATE = '0000-00-00';
	const EMPTY_DATETIME = '0000-00-00 00:00:00';
	const EMPTY_TIME = '00:00:00';

	/**
	 *  Количество минут до возможности повторной отправки и текст ошибка
	 */
	const API_MINUTES_UNTIL_RESEND = 3;
	const API_MINUTES_RESEND_ERROR = "Должна пройти минута до следующей отправки SMS";


	const U_ACTION_TYPE_FORM = 1;
	const U_ACTION_TYPE_SMS = 2;
	const U_ACTION_TYPE_BLOCK_FORM = 8;
	const U_ACTION_TYPE_BLOCK_SMS = 9;
	const U_ACTION_TYPE_CARD_VERIFY = 101;
	const U_ACTION_TYPE_LOGIN = 10;

	/**
	 * Время перед повторной отправкой sms и email кодов
	 */
	const CODE_RESEND_TIME = 180;

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
	 * Настройка логина для антибота
	 */
	const ANTIBOT_LOGIN_TIME = 60; //время проверки длинного периода
	const ANTIBOT_LOGINT_COUNT = 10;

	/**
	 * Время и число попыток для привязки карты
	 */
	const ANTIBOT_CARD_ADD_TIME = 60; //время, в течение которого доступно соответствующее число попыток
	const ANTIBOT_CARD_ADD_COUNT = 15; //число попыток
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

	public static $iTimeNow = null;

	public static $aMainBreadCrumbs = array(
		array('Выбор пакета', 1, 1),
		array('Заявка на займ', 3, 2),
		array('Подтверждение номера телефона', 8, 3),
		array('Идентификация', 9, 4)
	);

	public static $aIvanovoBreadCrumbs = array(
		array('Выбор займа', 1, 1),
		array('Заявка на займ', 2, 2),
		array('Подтверждение номера телефона', 7, 3),
		array('Идентификация', 8, 4)
	);

	public static $aContinueRegBreadCrumbs = array(
		array('Быстрая регистрация', 1, 1),
		array('Заполнение анкеты', 2, 2),
		array('Идентификация', 8, 3)
	);

	/**
	 * Опции для HTML Purifier
	 *
	 * @var array
	 */
	public static $aPurifyOptions = array(
		'Filter.YouTube'           => true,
		'HTML.SafeObject'          => true,
		'HTML.SafeIframe'          => true,
		'Output.FlashCompat'       => true,
		'URI.SafeIframeRegexp'     => '%^(http://|//)(www.youtube(?:-nocookie)?.com/embed/|player.vimeo.com/video/)%',
		'Attr.AllowedFrameTargets' => array('_blank', '_self', '_parent', '_top'),
		'HTML.AllowedElements'     => array("div", "p", "ul", "ol", "li", "h3", "h4", "h5", "h6", "img", "a", "b", "i", "s", "span", "u", "em", "strong", "del", "blockquote", "sup", "sub", "pre", "br", "hr", "table", "tbody", "thead", "tr", "td", "th", "iframe"),
		'HTML.AllowedAttributes'   => array("img.src", "img.alt", "img.title", "*.width", "*.height", "a.href", "a.title", "a.target", "*.style", "*.class", "iframe.frameborder", "iframe.src"),
	);

	/**
	 * количество цифр в номере телефона
	 */
	const C_PHONE_LENGTH = 10;

	const C_NUMERIC_CODE_MIN_LENGTH = 4;
	const C_NUMERIC_CODE_MAX_LENGTH = 4;

	/**
	 * максимальное число попыток ввода кода из SMS
	 */
	const MAX_SMSCODE_TRIES = 3;

	/**
	 * длина кода подтверждения, отправляемого по SMS
	 */
	const C_SMS_CODE_LENGTH = 4;

	/**
	 * длина кода подтверждения, отправляемого по email
	 */
	const C_EMAIL_CODE_LENGTH = 4;

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
	 * минимальное и максимальное значение допустимого возраста
	 */
	const C_MIN_AGE = 14;
	const C_MAX_AGE = 120;

	/**
	 * допустимая длина для ИНН
	 */
	const C_INN_LENGTH = 12;

	const C_BUTTON_LABEL_NEXT = 'Далее →';
	const C_BUTTON_LABEL_BACK = '← Назад';

	// время жизни авторизации
	public $iLoginTime = self::CTIME_DAY;

	/**
	 * ссылка на сайт
	 *
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
	 *
	 * @return int|null
	 */
	public static function getTime()
	{

		return (null === self::$iTimeNow)
			? time()
			: self::$iTimeNow;

	}

	/**
	 * массив возрастов, в которые меняют паспорт
	 *
	 * @var array
	 */
	public static $aChangePassportAges = array(
		14,
		20,
		45,
	);

	/**
	 * @param ClientCreateFormAbstract $oClientCreateForm
	 * @param                          $sAttrName
	 *
	 * @return array
	 */
	public static function getHintHtmlOptions(ClientCreateFormAbstract $oClientCreateForm, $sAttrName)
	{
		$aHtmlOptions = array();

		$aHintsHtmlOptions = $oClientCreateForm->getHints();
		$sLabel = $oClientCreateForm->getAttributeLabel($sAttrName);

		if (array_key_exists($sAttrName, $aHintsHtmlOptions)) {
			$sInfo = $aHintsHtmlOptions[$sAttrName];

			$sInfoTag = CHtml::tag('i', array(
				'class'          => 'append-icon-info',
				'data-html'      => 'true',
				'data-trigger'   => 'hover',
				'data-placement' => 'top',
				'data-content'   => '<span class="popover-info">' . $sInfo . '</span>',
				'data-toggle'    => 'popover',
				'data-container' => 'false'
			), '', true);

			$aHtmlOptions = array(
				'append'         => $sInfoTag,
				'data-trigger'   => 'focus',
				'data-placement' => 'left',
				'data-html'      => 'true',
				'data-content'   => '<span class="popover-info"><p class="popover-label">' . $sLabel . '</p>' . $sInfo . '</span>',
				'data-toggle'    => 'popover',
				'data-container' => 'false'
			);
		}

		return $aHtmlOptions;
	}

	/**
	 * @param ClientCreateFormAbstract $oClientForm
	 *
	 * @return array
	 */
	public static function  getSecondDocumentPopoversLabel(ClientCreateFormAbstract $oClientForm)
	{
		$aErrors = Dictionaries::$aDocumentsPopovers;
		foreach ($aErrors as &$sError) {
			$sError = '<span class=\'popover-info\'><p class=\'popover-label\'>' . $oClientForm->getAttributeLabel('document_number') . '</p>' . $sError . '</span>';
		}

		return $aErrors;
	}

	/**
	 *
	 * @return array
	 */
	public static function  getSecondDocumentPopovers()
	{
		$aErrors = Dictionaries::$aDocumentsPopovers;
		foreach ($aErrors as &$sError) {
			$sError = '<span class=\'popover-info\'>' . $sError . '</span>';
		}

		return $aErrors;
	}

	/**
	 * Возвращает значение константы
	 *
	 * @return bool
	 */
	public static function getIsIvanovoSite()
	{
		return (defined('SITE_IVANOVO')) ? SITE_IVANOVO : false;
	}

	/**
	 * Возвращает адрес электронной почты, на который будут отправляться письма
	 *
	 * @return string
	 */
	public static function getContactEmail()
	{
		if (!empty(Yii::app()->params['contactEmail'])) {
			return Yii::app()->params['contactEmail'];
		}

		return self::DEFAULT_EMAIL;
	}

	/**
	 * Возвращает адрес, от которого приходит письмо, отправляемое с сервера
	 *
	 * @return string
	 */
	public static function getEmailFrom()
	{
		if (!empty(Yii::app()->params['emailFrom'])) {
			return Yii::app()->params['emailFrom'];
		}

		return self::DEFAULT_EMAIL;
	}

	/**
	 * Форматируем дату в вид 01.01.2013 00:00
	 *
	 * @param      $sDate
	 * @param bool $bWithTime выводить ли время
	 *
	 * @return bool|string
	 */
	public static function formatRusDate($sDate, $bWithTime = true)
	{
		if (!is_numeric($sDate)) {
			$sDate = strtotime($sDate);
		}

		if ($sDate) {
			if ($bWithTime) {
				$sDate = date('d.m.Y H:i', $sDate);

				$sDate .= " " . CHtml::openTag('i', array("class" => "icon-question-sign", "rel" => "tooltip", "title" => Dictionaries::C_INFO_MOSCOWTIME));
				$sDate .= CHtml::closeTag('i');
			} else {
				$sDate = date('d.m.Y', $sDate);
			}
		}

		return $sDate;
	}

	/**
	 * @param null $sLocalLink
	 *
	 * @return string
	 */
	public function getAbsLink($sLocalLink = null)
	{

		return $this->getHttpHost() . $sLocalLink;

	}

	/**
	 * @param $key
	 *
	 * @return string
	 */
	public function getCKeyLink($key)
	{

		return $this->getHttpHost() . '/ckey/' . $key . '/';

	}

	/**
	 * @return string
	 */
	public function getHttpHost()
	{

		if (!$this->isConsole()) {
			return Yii::app()->request->getHostInfo();
		}

		return $this->param->link;

	}

	/**
	 * @param CController $controller
	 */
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

	/**
	 * @param null $url
	 */
	public function setRedirectUrl($url = null)
	{
		if (null === $url) {
			$url = Yii::app()->request->getUrl();
		}
		Yii::app()->user->setReturnUrl($url);
	}


	/**
	 * @param      $url
	 * @param bool $bStrong
	 *
	 * @return bool
	 */
	public function isCurrentUrl($url, $bStrong = false)
	{

		if (!$bStrong) {
			return (strpos(Yii::app()->request->getUrl(), $url) === 0);
		} else {
			return (Yii::app()->request->getUrl() == $url);
		}

	}

	/**
	 * @return bool
	 */
	public function isIndexPage()
	{

		return $this->isCurrentUrl('/', true) || $this->isCurrentUrl('/index');

	}

	public function init()
	{

	}

	/**
	 * @return bool
	 */
	public function isConsole()
	{

		return php_sapi_name() == 'cli';

	}

	/**
	 * @return bool
	 */
	public static function isLocalServer()
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

	/**
	 * @param $sDirPath
	 *
	 * @return bool
	 */
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
	 *
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
	 *
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
	 *
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
	 *
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
	 *
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
	 * Перевод первой буквы строки в верхний регистр (мультибайтовая кодировка)
	 *
	 * @param string $sText
	 * @param string $sEncoding
	 *
	 * @return string
	 */
	public static function mb_ucfirst($sText, $sEncoding = 'utf-8')
	{
		$iStrLen = mb_strlen($sText, $sEncoding);
		$sLeft = mb_substr($sText, 1, $iStrLen - 1, $sEncoding);
		$sFirstChar = mb_substr($sText, 0, 1, $sEncoding);

		return mb_convert_case($sFirstChar, MB_CASE_UPPER, $sEncoding) . $sLeft;
	}

	/**
	 * Перевод первой буквы строки в нижний регистр (мультибайтовая кодировка)
	 *
	 * @param string $sText
	 * @param string $sEncoding
	 *
	 * @return string
	 */
	public static function mb_lcfirst($sText, $sEncoding = 'utf-8')
	{
		$iStrLen = mb_strlen($sText, $sEncoding);
		$sLeft = mb_substr($sText, 1, $iStrLen - 1, $sEncoding);
		$sFirstChar = mb_substr($sText, 0, 1, $sEncoding);

		return mb_convert_case($sFirstChar, MB_CASE_LOWER, $sEncoding) . $sLeft;
	}

	/**
	 * @param $sCommand
	 *
	 * @return string команда для консоли
	 */
	public function getShellCommand($sCommand)
	{
		return 'php ' . Yii::app()->getBasePath() . '/../cron/cron.php ' . $sCommand;
	}

	/**
	 * @param $sCommand
	 *
	 * @return string результат выполнения команды system
	 */
	public function execShellCommand($sCommand)
	{
		ob_start();
		system($this->getShellCommand($sCommand));

		return ob_get_clean();
	}

	/**
	 * @param $sCommand
	 *
	 * @return string команда для консоли по тестовому крон-конфигу
	 */
	public function getTestShellCommand($sCommand)
	{
		return 'php ' . Yii::app()->getBasePath() . '/../cron/cron.test.php ' . $sCommand;
	}

	/**
	 * @param $sCommand
	 *
	 * @return string результат выполнения команды system по тестовому крон-конфигу
	 */
	public function execTestShellCommand($sCommand)
	{
		ob_start();
		system($this->getTestShellCommand($sCommand));

		return ob_get_clean();
	}


	/**
	 * содержимое страницы по url
	 *
	 * @param $sUrl
	 *
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

	/**
	 * @return string
	 */

	public static function copyrightYear()
	{
		$copyYear = 2011;
		$curYear = date('Y');
		echo $copyYear . (($copyYear != $curYear) ? '-' . $curYear : '');
	}

	/**
	 * Для виджета CityWidget возвращает URL из params
	 *
	 * @param $sUrlName
	 *
	 * @return mixed|string
	 */
	public static function getCurrentRedirectUrlForCityWidget($sUrlName)
	{
		$sUrl = Yii::app()->params[$sUrlName];
		if (Yii::app()->controller->getModule()) {
			$sModuleId = Yii::app()->controller->getModule()->getId();
			if ($sModuleId && $sUrl) {
				$sUrl .= "/" . $sModuleId;
			}
		}

		$sUrl = preg_replace('|([^:])//|', '\1/', $sUrl);

		return ($sUrl) ? $sUrl : self::DEFAULT_URL; //если вдруг параметр не передан, возвращаем значение по-умолчанию
	}

	/**
	 * URL для редиректа при логине в ЛК (если нужен редирект на главный сайт)
	 *
	 * @param $sUrlName
	 *
	 * @return mixed|string
	 */
	public static function getRedirectUrlForAccount($sUrlName)
	{
		$sUrl = Yii::app()->params[$sUrlName] . "/account";
		$sUrl = preg_replace('|([^:])//|', '\1/', $sUrl);

		return ($sUrl) ? $sUrl : self::DEFAULT_URL; //если вдруг параметр не передан, возвращаем значение по-умолчанию
	}

	public function getTrackingId()
	{
		$oCookie = Yii::app()->request->cookies['lead_generator'];

		if ($oCookie) {
			return $oCookie->value['sUid'];
		}

		$oCookie = Yii::app()->request->cookies['TrackingID'];
		if ($oCookie) {
			return (string)$oCookie;
		}

		return null;
	}
}

/**
 */
class SiteParamValue
{

	public $hostname;
	public $link;

	/**
	 * @param $aAttributes
	 */
	public function __construct($aAttributes)
	{
		foreach ($aAttributes as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	 * @param $name
	 *
	 * @return null
	 */
	public function __get($name)
	{
		return $this->p($name);
	}

	/**
	 * @param $var
	 *
	 * @return null
	 */
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
