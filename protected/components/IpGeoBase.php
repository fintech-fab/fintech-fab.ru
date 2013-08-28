<?

/**
 * класс-обертка для веб-сервиса
 * определения местоположения по IP
 *
 * http://194.85.91.253:8090/geo/geo.html
 *
 */
class IpGeoBase
{

	/** @var boolean */
	public static $bEncode = true;

	/**
	 * поля которые нужно получить в ответе на запрос по IP
	 *
	 * @var array
	 */
	public static $FIELDS = array('city', 'region', 'district');

	/**
	 * массив для кеширования запросов
	 *
	 * @var array
	 */
	public static $aResultCache = array();

	/**
	 * название округа по IP
	 *
	 * @param string $ip
	 *
	 * @return string
	 * @example $sDistrictName = ids_ipGeoBase::getDistrictByIP('62.109.164.209');
	 */
	public static function getDistrictByIP($ip = null)
	{

		if (null === $ip) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$aResult = self::request($ip);

		if (!$aResult || isset($aResult['error'])) {
			return false;
		}

		if (isset($aResult['district'])) {
			if (self::$bEncode) {
				return iconv('utf8', 'cp1251', $aResult['district']);
			} else {
				return $aResult['district'];
			}
		}

		return false;

	}

	/**
	 * название региона по IP
	 *
	 * @param string $ip
	 *
	 * @return string
	 * @example $sRegionName = ids_ipGeoBase::getRegionByIP('62.109.164.209');
	 */
	public static function getRegionByIP($ip = null)
	{

		if (null === $ip) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$aResult = self::request($ip);

		if (!$aResult || isset($aResult['error'])) {
			return false;
		}

		if (isset($aResult['region'])) {
			if (self::$bEncode) {
				return iconv('utf8', 'cp1251', $aResult['region']);
			} else {
				return $aResult['region'];
			}
		}

		return false;

	}

	/**
	 * название города по IP
	 *
	 * @param string $ip
	 *
	 * @return string
	 * @example $sCityName = ids_ipGeoBase::getCityByIP('62.109.164.209');
	 */
	public static function getCityByIP($ip = null)
	{

		if (null === $ip) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$aResult = self::request($ip);

		if (!$aResult || isset($aResult['error'])) {
			return false;
		}

		if (isset($aResult['city'])) {
			if (self::$bEncode) {
				return iconv('utf8', 'cp1251', $aResult['city']);
			} else {
				return $aResult['city'];
			}
		}

		return false;

	}

	/**
	 * полные данные по списку IP
	 *
	 * @param array  $aIpList список IP
	 * @param string $sProxy  прокси
	 *
	 * @return array|bool
	 */
	public static function getDataByIPs($aIpList, $sProxy = null)
	{

		$aResult = self::request($aIpList, $sProxy);

		if (!$aResult || isset($aResult['error'])) {
			return false;
		}

		$aReturnResult = array();

		// одно значение - переводим во много
		if (!empty($aResult['city'])) {
			$aResult = array($aResult);
		}

		// если один IP и он не найден, то массив надо привести к нужному формату
		if (isset($aResult['message'])) {
			$aResult[0] = $aResult;
			unset($aResult['message']);
			unset($aResult['value']);
		}

		foreach ($aResult as $aItem) {

			// ошибка
			if (!empty($aItem['message'])) {

				$sIP = $aItem['value'];
				$aItem['city'] = 'unknown';
				$aItem['region'] = 'unknown';
				$aItem['district'] = 'unknown';
				unset($aItem['value']);
				$aReturnResult[$sIP] = $aItem;

				// не ошибка
			} elseif (!empty($aItem['value'])) {

				$sIP = $aItem['value'];
				unset($aItem['value']);
				$aReturnResult[$sIP] = $aItem;
			}

		}

		return $aReturnResult;

	}

	/**
	 * запрос к сервису
	 *
	 * @param array  $aIpList
	 * @param string $sProxy прокси сервер
	 * @param array  $aFieldList
	 *
	 * @return array
	 */
	private static function request($aIpList, $sProxy = null, $aFieldList = null)
	{

		$key = md5(serialize(array($aIpList, $aFieldList)));

		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}

		if (isset(self::$aResultCache[$key])) {
			return self::$aResultCache[$key];
		}


		$aResult = array();

		$sPostXml = self::getPostXml($aIpList, $aFieldList);

		if (!$sPostXml) {
			return array('ip' => array('error' => 1));
		}

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_URL, "http://194.85.91.253:8090/geo/geo.html");
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($curl, CURLOPT_TIMEOUT, 15);
		curl_setopt($curl, CURLOPT_POST, 1);

		if (self::validateNetSocket($sProxy)) {
			curl_setopt($curl, CURLOPT_PROXY, $sProxy);
		}

		curl_setopt($curl, CURLOPT_POSTFIELDS, 'address=' . $sPostXml);

		$result = @curl_exec($curl);
		/*
		echo "\n"; echo '$sPostXml = '; print_r($sPostXml); echo "\n";
		echo "\n"; echo '$result = '; print_r($result); echo "\n";
		echo "\n"; echo 'curl_error() = '; print_r(curl_error( $curl )); echo "\n";
		echo "\n"; echo 'curl_errno() = '; print_r(curl_errno( $curl )); echo "\n";
		*/
		if ($result) {
			$aResult = ids_convert_simpleXMLToArray(simplexml_load_string($result));
		} else {
			$aResult = array('ip' => array('error' => 1));
		}

		$_SESSION[$key] = $aResult['ip'];
		self::$aResultCache[$key] = $aResult['ip'];

		return $aResult['ip'];

	}

	/**
	 * собрать xml для запроса
	 *
	 * @param array $aIpList    список IP
	 * @param array $aFieldList список нужных полей
	 *
	 * @return bool|string
	 */
	private static function getPostXml($aIpList, $aFieldList = null)
	{


		if (!$aIpList) {
			return false;
		}

		if ($aIpList && !is_array($aIpList)) {
			$aIpList = array($aIpList);
		}

		if ($aFieldList === null) {
			$aFieldList = self::$FIELDS;
		}

		$xml = "<ipquery>";

		$xml .= "<fields>";
		foreach ($aFieldList as $sFieldName) {
			$xml .= '<' . $sFieldName . '/>';
		}
		$xml .= '</fields>';

		$xml .= '<ip-list>';
		foreach ($aIpList as $sIpName) {
			$xml .= '<ip>' . $sIpName . '</ip>';
		}
		$xml .= '</ip-list>';

		$xml .= '</ipquery>';

		return $xml;


	}

	/**
	 * Валидация ip:port
	 *
	 * @param string $sNetSocket типа '127.0.0.1:8080'
	 *
	 * @return boolean
	 */
	private function validateNetSocket($sNetSocket)
	{

		$aNetSocket = explode(':', $sNetSocket);

		if (count($aNetSocket) < 2) {
			return false;
		}

		$sIntegerPattern = '/^\s*[+-]?\d+\s*$/';

		$bIpIsValid = (ip2long($aNetSocket[0]) !== false);
		$bPortIsInteger = (bool)preg_match($sIntegerPattern, $aNetSocket[1]);

		$bValid = ($bIpIsValid && $bPortIsInteger);

		return $bValid;
	}

}

?>