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

	const API_ACTION_TOKEN_UPDATE = 'siteToken/update';
	const API_ACTION_TOKEN_CREATE = 'siteToken/create';
	const API_ACTION_GET_INFO = 'siteClient/getInfo';

	const API_ACTION_REQ_SMS_PASS = 'siteToken/reqSms';
	const API_ACTION_CHECK_SMS_PASS = 'siteToken/checkSms';

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
			$this->updateClientToken();
		}
	}

	public function getAuth($sPhone, $sPassword)
	{
		$aRequest = array('login' => $sPhone, 'password' => $sPassword);

		$aTokenData = $this->requestAdminKreddyApi(self::API_ACTION_TOKEN_CREATE, $aRequest);
		echo '<pre>' . "";
		CVarDumper::dump($aTokenData);
		echo '</pre>';
		if ($aTokenData['code'] === self::ERROR_NONE) {
			$this->setSessionToken($aTokenData['token']);
			$this->token = $aTokenData['token'];

			return true;
		} else {
			return false;
		}
	}

	public function updateClientToken()
	{
		//отсылаем текущий токен и получаем новый токен в ответ, обновляем его в сессии

		$aRequest = array('token' => $this->getSessionToken());

		$aTokenData = $this->requestAdminKreddyApi(self::API_ACTION_TOKEN_UPDATE, $aRequest);
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

	public function getSmsAuth($sSmsPassword)
	{
		$aRequest = array('password' => $sSmsPassword);

		$aTokenData = $this->requestAdminKreddyApi(self::API_ACTION_CHECK_SMS_PASS, $aRequest);

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
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_REQ_SMS_PASS);

		return $aResult;
	}

	public function getClientData()
	{
		$aData = array('code' => self::ERROR_AUTH, 'balance' => '');
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
					$sAction = 'siteClient/getBalance';
					break;
				//case 'secure':
				//	$sAction
				//	break;
				default:
					$sAction = 'siteClient/getBalance';
					break;
			}

			$aData = $this->requestAdminKreddyApi($sAction);
		}

		return $aData;
	}

	private function requestAdminKreddyApi($sAction, $aRequest = array())
	{
		//тут у нас непосредственно curl запрашивает данные
		/*$ch = curl_init('http://admin.kreddy.topas/siteApi/' . $sAction);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('host:ccv'));
		curl_setopt($ch, CURLOPT_POST, true);

		$aRequest = array_merge($aRequest, array('token' => $this->getSessionToken()));

		curl_setopt($ch, CURLOPT_POSTFIELDS, $aRequest);

		$response = curl_exec($ch);

		$aData = CJSON::decode($response);*/

		//заглушка
		$aData = array('code' => self::ERROR_AUTH);

		if ($this->token == '159753' || $sAction === self::API_ACTION_TOKEN_CREATE) {
			switch ($aRequest['action']) {
				case self::API_ACTION_TOKEN_CREATE:
					if ($aRequest['login'] == '9154701913' && $aRequest['password'] == '159753') {
						$aData = array('code' => self::ERROR_NONE, 'message' => 'OK', 'token' => '159753');
					}
					break;
				case self::API_ACTION_CHECK_SMS_PASS:
					if ($aRequest['password'] == '15426378') {
						$aData = array('code' => self::ERROR_NONE, 'message' => 'OK', 'token' => '159753159753');
					}
					break;
				case self::API_ACTION_REQ_SMS_PASS:
					$aData = array('code' => self::ERROR_NONE, 'message' => 'OK');
					break;
				case self::API_ACTION_TOKEN_UPDATE:
					if ($aRequest['token'] == '159753') {
						$aData = array('code' => self::ERROR_NONE, 'message' => 'OK', 'token' => '159753');
					}
					break;
				case self::API_ACTION_GET_INFO:
					$aData = array('code' => self::ERROR_NONE, 'balance' => '1000', 'expire_to' => 'Василий', 'last_name' => 'Пупкин', 'third_name' => 'Иванович');
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