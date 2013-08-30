<?php
/**
 * Created by JetBrains PhpStorm.
 * User: popov
 * Date: 28.08.13
 * Time: 12:02
 * To change this template use File | Settings | File Templates.
 */

class AdminKreddyApi extends CModel
{

	const ERROR_NONE = 0;
	const ERROR_AUTH = 2;
	const ERROR_TOKEN_EXPIRE = 5;
	const ERROR_NEED_SMS_AUTH = 7;

	private $token;

	public function attributeNames()
	{
		return array('token' => 'Token');
	}

	/**
	 * Constructor.
	 */

	public function __construct()
	{
		$this->init();
	}


	public function init()
	{
		$this->token = $this->getSessionToken();
		if (!empty($this->token)) {
			$this->renewClientToken();
		}
	}

	public function getAuth($sPhone, $sPassword)
	{
		$aRequest = array('action' => 'login', 'phone' => $sPhone, 'password' => $sPassword);

		$aTokenData = $this->requestAdminKreddyApi($aRequest);

		if ($aTokenData['code'] === self::ERROR_NONE) {
			$this->setSessionToken($aTokenData['token']);
			$this->token = $aTokenData['token'];

			return true;
		} else {
			return false;
		}
	}

	public function getSmsAuth($sSmsPassword)
	{
		$aRequest = array('action' => 'sms-auth', 'sms-password' => $sSmsPassword);

		$aTokenData = $this->requestAdminKreddyApi($aRequest);

		if ($aTokenData['code'] === self::ERROR_NONE) {
			$this->setSessionToken($aTokenData['token']);
			$this->token = $aTokenData['token'];

			return true;
		} else {
			return false;
		}
	}

	public function sendSMS()
	{
		$aRequest = array('action' => 'sms-send');

		$aResult = $this->requestAdminKreddyApi($aRequest);

		return $aResult;
	}

	public function renewClientToken()
	{
		//отсылаем текущий токен и получаем новый токен в ответ, обновляем его в сессии

		$aRequest = array('action' => 'getNewToken', 'token' => '159753');

		$aTokenData = $this->requestAdminKreddyApi($aRequest);

		if ($aTokenData['code'] == self::ERROR_NONE) {
			$this->setSessionToken($aTokenData['token']);
			$this->token = $aTokenData['token'];

			return true;
		} else {
			$this->setSessionToken(null);
			$this->token = null;

			return false;
		}
	}

	public function getClientData()
	{
		$aData = array('code' => self::ERROR_AUTH, 'first_name' => '', 'last_name' => '', 'third_name' => '', 'balance' => '');
		if (!empty($this->token)) {
			//тут типа запрос данных по токену
			$aGetData = $this->getData('base');

			$aData = array_merge($aData, $aGetData);
		} else {
			$aData = false;
		}

		return $aData;
	}

	public function getClientSecureData()
	{
		$aData = array('code' => self::ERROR_AUTH, 'secure1' => '', 'secure2' => '');

		if (!empty($this->token)) {
			//тут типа запрос данных по токену
			$aGetData = $this->getData('secure');

			$aData = array_merge($aData, $aGetData);
		} else {
			$aData = false;
		}

		return $aData;
	}

	private function getData($sType)
	{
		$aData = array('code' => self::ERROR_AUTH);
		if (!empty($this->token)) {
			switch ($sType) {
				case 'base':
					$aRequest = array('action' => 'base-data');
					break;
				case 'secure':
					$aRequest = array('action' => 'secure-data');
					break;
				default:
					$aRequest = array('action' => 'base-data');
					break;
			}

			$aData = $this->requestAdminKreddyApi($aRequest);
		}

		return $aData;
	}

	private function requestAdminKreddyApi($aRequest)
	{
		//тут у нас непосредственно curl запрашивает данные
		/*$ch = curl_init('http://127.0.0.1/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('host:ccv'));
		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $aRequest);

		$response = curl_exec($ch);

		$aData = CJSON::decode($response);*/

		//заглушка
		$aData = array('code' => self::ERROR_AUTH);

		if ($this->token == '159753' || $aRequest['action'] === 'login') {
			switch ($aRequest['action']) {
				case 'login':
					if ($aRequest['phone'] == '9154701913' && $aRequest['password'] == '159753') {
						$aData = array('code' => self::ERROR_NONE, 'message' => 'OK', 'token' => '159753');
					}
					break;
				case 'sms-auth':
					if ($aRequest['sms-password'] == '15426378') {
						$aData = array('code' => self::ERROR_NONE, 'message' => 'OK', 'token' => '159753159753');
					}
					break;
				case 'sms-send':
					$aData = array('code' => self::ERROR_NONE, 'message' => 'OK');
					break;
				case 'getNewToken':
					if ($aRequest['token'] == '159753') {
						$aData = array('code' => self::ERROR_NONE, 'message' => 'OK', 'token' => '159753');
					}
					break;
				case 'base-data':
					$aData = array('code' => self::ERROR_NONE, 'balance' => '1000', 'first_name' => 'Василий', 'last_name' => 'Пупкин', 'third_name' => 'Иванович');
					break;
				case 'secure-data':
					$aData = array('code' => self::ERROR_NEED_SMS_AUTH);
					break;
				default:
					$aData = array('code' => self::ERROR_AUTH);
					break;
			}
		}
		if ($this->token == '159753159753') {
			switch ($aRequest['action']) {
				case 'getNewToken':
					if ($aRequest['token'] == '159753') {
						$aData = array('code' => self::ERROR_NONE, 'message' => 'OK', 'token' => '159753159753');
					}
					break;
				case 'base-data':
					$aData = array('code' => self::ERROR_NONE, 'balance' => '1000', 'first_name' => 'Василий', 'last_name' => 'Пупкин', 'third_name' => 'Иванович');
					break;
				case 'secure-data':
					$aData = array('code' => self::ERROR_NONE, 'secure1' => 'some-data1', 'secure2' => 'some-data2');
					break;
				default:
					$aData = array('code' => self::ERROR_AUTH);
					break;
			}
		}

		return $aData;
	}

	/**
	 * @return mixed
	 */

	private function getSessionToken()
	{
		return Yii::app()->session['akApi_token'];
	}

	/**
	 * @param $token
	 */

	private function setSessionToken($token)
	{
		Yii::app()->session['akApi_token'] = $token;
	}

}