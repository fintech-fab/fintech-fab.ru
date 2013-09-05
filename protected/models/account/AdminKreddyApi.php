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
	const ERROR_NEED_SMS_AUTH = 9;
	const ERROR_NEED_SMS_CODE = 10;

	const SMS_AUTH_OK = 0;
	const SMS_SEND_OK = 1;
	const SMS_CODE_ERROR = 2;
	const SMS_CANT_SEND = 3;

	const SMS_PASSWORD_SEND_OK = 1;

	const API_ACTION_TEST = 'siteClient/doTest';
	const API_ACTION_TOKEN_UPDATE = 'siteToken/update';
	const API_ACTION_TOKEN_CREATE = 'siteToken/create';
	const API_ACTION_GET_INFO = 'siteClient/getInfo';
	const API_ACTION_GET_HISTORY = 'siteClient/getPaymentHistory';
	const API_ACTION_RECOVER_PASSWORD = 'siteClient/recoverPassword';

	const API_ACTION_REQ_SMS_CODE = 'siteClient/authBySms';
	const API_ACTION_CHECK_SMS_CODE = 'siteClient/authBySms';

	private $token;

	/**
	 * @return array
	 */

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

	/**
	 *
	 */

	public function init()
	{
		$this->token = $this->getSessionToken();
		if (!empty($this->token)) {
			$this->updateClientToken();
		}
	}

	/**
	 * @param $sPhone
	 * @param $sPassword
	 *
	 * @return bool
	 */

	public function getAuth($sPhone, $sPassword)
	{
		$aRequest = array('login' => $sPhone, 'password' => $sPassword);

		$aTokenData = $this->requestAdminKreddyApi(self::API_ACTION_TOKEN_CREATE, $aRequest);
		if ($aTokenData['code'] === self::ERROR_NONE) {
			$this->setSessionToken($aTokenData['token']);
			$this->token = $aTokenData['token'];

			return true;
		} else {
			return false;
		}
	}

	/**
	 * @return bool
	 */

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

	/**
	 * @param $sSmsPassword
	 *
	 * @return bool
	 */

	public function getSmsAuth($sSmsPassword)
	{
		$aRequest = array('sms_code' => $sSmsPassword);

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CHECK_SMS_CODE, $aRequest);

		if ($aResult['code'] === self::ERROR_NONE && $aResult['sms_status'] === self::SMS_AUTH_OK) {
			$this->setSessionToken($aResult['token']);
			$this->token = $aResult['token'];

			return $aResult;
		} else {
			return $aResult;
		}
	}

	/**
	 * @param bool|int $resend
	 *
	 * @return mixed
	 */

	public function sendSMS($resend = false)
	{
		if (!$resend) {
			$aResult = $this->requestAdminKreddyApi(self::API_ACTION_REQ_SMS_CODE, array('sms_resend' => 0));
		} else {
			$aResult = $this->requestAdminKreddyApi(self::API_ACTION_REQ_SMS_CODE, array('sms_resend' => 1));
		}

		return $aResult;
	}

	/**
	 * @param      $phone
	 * @param bool $resend
	 *
	 * @return mixed
	 */
	public function recoveryPasswordSendSms($phone, $resend = false)
	{
		if (!$resend) {
			$aResult = $this->requestAdminKreddyApi(self::API_ACTION_RECOVER_PASSWORD, array('phone' => $phone, 'sms_resend' => 0));
		} else {
			$aResult = $this->requestAdminKreddyApi(self::API_ACTION_RECOVER_PASSWORD, array('phone' => $phone, 'sms_resend' => 1));
		}

		return $aResult;
	}

	/**
	 * @param $phone
	 * @param $sms_code
	 *
	 * @return mixed
	 */
	public function recoveryPasswordCheckSms($phone, $sms_code)
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_RECOVER_PASSWORD, array('phone' => $phone, 'sms_code' => $sms_code));

		return $aResult;
	}


	/**
	 * @return array|bool
	 */

	public function getClientInfo()
	{
		$aData = array('code' => self::ERROR_AUTH);
		if (!empty($this->token)) {
			//тут типа запрос данных по токену
			$aGetData = $this->getData('info');

			//if(gettype($aGetData)==='array'){
			$aData = array_merge($aData, $aGetData);
			//}
		} else {
			$aData = false;
		}

		return $aData;
	}

	/**
	 * @return array|bool
	 */

	public function getHistory()
	{
		//TODO убрать либо тут $aData, либо в else, во всех экшнах
		$aData = array('code' => self::ERROR_AUTH);
		if (!empty($this->token)) {
			//тут типа запрос данных по токену
			$aGetData = $this->getData('history');

			$aData = array_merge($aData, $aGetData);
		} else {
			$aData = false;
		}

		return $aData;
	}

	/**
	 * @param bool   $get_code
	 * @param string $sms_code
	 *
	 * @return array|bool
	 */
	public function doTest($get_code = false, $sms_code = '')
	{
		$aResult = array('code' => self::ERROR_AUTH);

		if (!empty($this->token)) {
			if (!empty($sms_code)) {
				$aResult = $this->requestAdminKreddyApi(self::API_ACTION_TEST, array('sms_code' => $sms_code));
			} elseif ($get_code) {
				$aResult = $this->requestAdminKreddyApi(self::API_ACTION_TEST);
			} else {
				$aResult = $this->requestAdminKreddyApi(self::API_ACTION_TEST, array('test_code' => 1));
			}
		}

		return $aResult;
	}

	/**
	 * @param $sType
	 *
	 * @return array|mixed
	 */

	private function getData($sType)
	{
		$aData = array('code' => self::ERROR_AUTH);
		if (!empty($this->token)) {
			switch ($sType) {
				case 'info':
					$sAction = self::API_ACTION_GET_INFO;
					break;
				case 'history':
					$sAction = self::API_ACTION_GET_HISTORY;
					break;
				default:
					$sAction = self::API_ACTION_GET_INFO;
					break;
			}

			$aData = $this->requestAdminKreddyApi($sAction);
		}

		return $aData;
	}

	/**
	 * @param       $sAction
	 * @param array $aRequest
	 *
	 * @return mixed
	 */

	private function requestAdminKreddyApi($sAction, $aRequest = array())
	{
		if ($sAction !== self::API_ACTION_RECOVER_PASSWORD) {
			//тут у нас непосредственно curl запрашивает данные
			$ch = curl_init('http://admin.kreddy.topas/siteApi/' . $sAction);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//curl_setopt($ch, CURLOPT_HTTPHEADER, array('host:ccv'));
			curl_setopt($ch, CURLOPT_POST, true);

			$aRequest = array_merge($aRequest, array('token' => $this->getSessionToken()));

			curl_setopt($ch, CURLOPT_POSTFIELDS, $aRequest);

			$response = curl_exec($ch);

			$aData = CJSON::decode($response);
		} else {

			//заглушка
			$aData = array('code' => self::ERROR_AUTH);
			if (empty($this->token)) {
				switch ($sAction) {
					case self::API_ACTION_RECOVER_PASSWORD:
						if ($aRequest['phone'] === '9808055488' && empty($aRequest['sms_code'])) {
							$aData = array('code' => self::ERROR_AUTH, 'sms_status' => self::SMS_SEND_OK, 'message' => 'СМС с кодом успешно отправлено');
						} elseif ($aRequest['phone'] === '9808055488' && $aRequest['sms_code'] === '1111') {
							$aData = array('code' => self::ERROR_AUTH, 'sms_status' => self::SMS_SEND_OK, 'message' => 'СМС с паролем успешно отправлено');
						}
						break;
				}
			}
			if ($this->token == '159753' || $sAction === self::API_ACTION_TOKEN_CREATE) {
				switch ($sAction) {
					case self::API_ACTION_TOKEN_CREATE:
						if ($aRequest['login'] == '9154701913' && $aRequest['password'] == '159753') {
							$aData = array('code' => self::ERROR_NONE, 'message' => 'OK', 'token' => '159753');
						}
						break;
					case self::API_ACTION_CHECK_SMS_CODE:
						if ($aRequest['password'] == '159753') {
							$aData = array('code' => self::ERROR_NONE, 'message' => 'OK', 'token' => '159753159753');
						}
						break;
					case self::API_ACTION_REQ_SMS_CODE:
						$aData = array('code' => self::ERROR_NONE, 'message' => 'OK');
						break;
					case self::API_ACTION_TOKEN_UPDATE:
						if ($aRequest['token'] == '159753') {
							$aData = array('code' => self::ERROR_NONE, 'message' => 'OK', 'token' => '159753');
						}
						break;
					case self::API_ACTION_GET_INFO:
						$aData = array('code' => 9, 'active_loan' => array('balance' => '-1000', 'expired_to' => '10-10-1000', 'expired' => ''));
						break;
					case self::API_ACTION_GET_HISTORY:
						$aData = array('code' => 9);
						break;
					case self::API_ACTION_RECOVER_PASSWORD:
						if ($aRequest['phone'] === '9808055488' && empty($aRequest['sms_code'])) {
							$aData = array('code' => self::SMS_SEND_OK, 'message' => 'СМС с кодом успешно отправлено');
						} elseif ($aRequest['phone'] === '9808055488') {

						}
						break;
					default:
						$aData = array('code' => self::ERROR_AUTH);
						break;
				}
			}
			if ($this->token == '159753159753') {
				switch ($sAction) {
					case self::API_ACTION_TOKEN_UPDATE:
						if ($aRequest['token'] == '159753159753') {
							$aData = array('code' => self::ERROR_NONE, 'message' => 'OK', 'token' => '159753159753');
						}
						break;
					case self::API_ACTION_GET_INFO:
						$aData = array('code' => self::ERROR_NONE, 'active_loan' => array('balance' => '-1000', 'expired_to' => '10-10-1000', 'expired' => ''), 'subscription' => array('activity_to' => '20-20-2000', 'available_loans' => 1, 'balance' => '-3000'));
						break;
					case self::API_ACTION_GET_HISTORY:
						$aData = array('code' => self::ERROR_NONE, 'active_loan' => array('balance' => '-1000', 'expired_to' => '10-10-1000', 'expired' => ''), 'subscription' => array('activity_to' => '20-20-2000', 'available_loans' => 1, 'balance' => '-3000'));
						break;
					default:
						$aData = array('code' => self::ERROR_AUTH);
						break;
				}
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

	public function logout()
	{
		$this->setSessionToken(null);
	}

	/**
	 * @param $aResult
	 *
	 * @return int
	 */
	public function getResultStatus($aResult)
	{
		if (isset($aResult) && isset($aResult['code'])) {
			$iRet = $aResult['code'];
		} else {
			$iRet = self::ERROR_AUTH;
		}

		return $iRet;
	}

	/**
	 * Проверяем полученный ответ API на его уровень авторизации
	 * коды 0, 9, 10 - авторизация в порядке
	 *
	 * @param $aResult
	 *
	 * @return bool
	 */
	public function isResultAuth($aResult)
	{
		$iStatus = $this->getResultStatus($aResult);
		if ($iStatus === self::ERROR_NONE || $iStatus == self::ERROR_NEED_SMS_AUTH || self::ERROR_NEED_SMS_CODE) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Проверяем, требует ли ответ API авторизации по СМС
	 *
	 * @param $aResult
	 *
	 * @return bool
	 */
	public function isNeedSmsAuth($aResult)
	{
		$iStatus = $this->getResultStatus($aResult);
		if ($iStatus === 9) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param      $aResult
	 * @param bool $needSmsActionCode
	 *
	 * @internal param bool $needSmsPass
	 * @return array
	 */

	public
	function getSmsState($aResult, $needSmsActionCode = false)
	{
		$needSmsPass = $this->isNeedSmsAuth($aResult);

		return array('passSent' => Yii::app()->session['smsPassSent'], 'codeSent' => Yii::app()->session['smsCodeSent'], 'smsAuthDone' => Yii::app()->session['smsAuthDone'], 'needSmsPass' => $needSmsPass, 'needSmsActionCode' => $needSmsActionCode);
	}

	/**
	 * @return CSort
	 */

	private
	function getHistorySort()
	{
		$sort = new CSort;
		$sort->defaultOrder = 'time DESC';
		$sort->attributes = array('time', 'type', 'type_id', 'amount');

		return $sort;
	}

	/**
	 * @param $aHistory
	 *
	 * @return \CArrayDataProvider
	 */
	public
	function getHistoryDataProvider($aHistory)
	{
		if (isset($aHistory) && $aHistory['code'] === 0 && isset($aHistory['history'])) {
			$oHistoryDataProvider = new CArrayDataProvider($aHistory['history'], array('keyField' => 'time', 'sort' => $this->getHistorySort()));
		} else {
			$oHistoryDataProvider = new CArrayDataProvider(array());
		}

		return $oHistoryDataProvider;
	}


}