<?php
/**
 * Created by JetBrains PhpStorm.
 * User: popov
 * Date: 28.08.13
 * Time: 12:02
 * To change this template use File | Settings | File Templates.
 */

class AdminKreddyApiComponent
{
	const ERROR_NONE = 0;
	const ERROR_UNKNOWN = 1;
	const ERROR_AUTH = 2;
	const ERROR_TOKEN_DATA = 3;
	const ERROR_TOKEN_VERIFY = 4;
	const ERROR_TOKEN_EXPIRE = 5;
	const ERROR_TOKEN_NOT_EXIST = 6;
	const CLIENT_NOT_EXIST = 7;
	const CLIENT_DATA_NOT_EXIST = 8;
	const ERROR_NEED_SMS_AUTH = 9;
	const ERROR_NEED_SMS_CODE = 10;

	const SMS_AUTH_OK = 0;
	const SMS_SEND_OK = 1;
	const SMS_CODE_ERROR = 2;
	const SMS_BLOCKED = 3;
	const SMS_CODE_TRIES_EXCEED = 4;

	const SMS_PASSWORD_SEND_OK = 1;

	const API_URL = 'http://admin.kreddy.topas/siteApi/';
	const API_ACTION_TEST = 'siteClient/doTest';
	const API_ACTION_TOKEN_UPDATE = 'siteToken/update';
	const API_ACTION_TOKEN_CREATE = 'siteToken/create';
	const API_ACTION_GET_INFO = 'siteClient/getInfo';
	const API_ACTION_GET_HISTORY = 'siteClient/getPaymentHistory';
	const API_ACTION_RESET_PASSWORD = 'siteClient/resetPassword';
	const API_ACTION_GET_PRODUCTS = 'siteClient/getProducts';

	const API_ACTION_REQ_SMS_CODE = 'siteClient/authBySms';
	const API_ACTION_CHECK_SMS_CODE = 'siteClient/authBySms';

	private $token;
	private $aClientInfo;
	private $iLastCode;
	private $sLastMessage = '';
	private $sLastSmsMessage = '';

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
			//если существует токен, то запрашиваем его обновление
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
			Yii::app()->session['smsAuthDone'] = true;

			return true;
		} else {
			//проверяем, получили ли мы sms_message
			if (isset($aResult['sms_message'])) {
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setLastSmsMessage('Произошла неизвестная ошибка. Позвоните на горячую линию.');
			}
		}

		return false;
	}

	/**
	 * Отправка СМС-пароля для СМС-авторизации
	 *
	 * @param bool $bResend
	 *
	 * @return mixed
	 */

	public function sendSmsPassword($bResend = false)
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_REQ_SMS_CODE, array('sms_resend' => (int)$bResend));

		if ($aResult && $aResult['code'] == self::ERROR_NEED_SMS_CODE && $aResult['sms_status'] == self::SMS_SEND_OK) {
			//устанавливаем флаг "СМС отправлено" и время отправки
			Yii::app()->adminKreddyApi->setSmsPassSentAndTime();
			$this->lastSmsMessage = $aResult['sms_message'];

			return true;
		} else {
			//проверяем, получили ли мы sms_message
			if (isset($aResult['sms_message'])) {
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setLastSmsMessage('При отправке SMS произошла неизвестная ошибка. Позвоните на горячую линию.');
			}
		}

		return false;
	}

	/**
	 * @param      $sPhone
	 * @param bool $bResend
	 *
	 * @return mixed
	 */
	public function resetPasswordSendSms($sPhone, $bResend = false)
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_RESET_PASSWORD, array('phone' => $sPhone, 'sms_resend' => (int)$bResend));
		//если результат успешный
		if ($aResult && $aResult['code'] == self::ERROR_NEED_SMS_CODE && $aResult['sms_status'] == self::SMS_SEND_OK) {
			//ставим флаг "смс отправлено" и сохраняем время отправки в сесссию
			Yii::app()->adminKreddyApi->setResetPassSmsCodeSentAndTime();
			//сохраняем телефон в сессию
			Yii::app()->adminKreddyApi->setResetPassPhone($sPhone);
			$this->setLastSmsMessage($aResult['sms_message']);

			return true;
		} else {
			//проверяем, получили ли мы sms_message
			if (isset($aResult['sms_message'])) {
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setLastSmsMessage = 'При отправке SMS произошла неизвестная ошибка. Позвоните на горячую линию.';
			}
		}

		return false;
	}

	/**
	 * @param $sPhone
	 * @param $sSmsCode
	 *
	 * @return mixed
	 */
	public function resetPasswordCheckSms($sPhone, $sSmsCode)
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_RESET_PASSWORD, array('phone' => $sPhone, 'sms_code' => $sSmsCode));

		if ($aResult['sms_status'] == AdminKreddyApiComponent::SMS_AUTH_OK) {
			$this->lastSmsMessage = $aResult['sms_message'];

			return true;
		} else {
			if (isset($aResult['sms_message'])) {
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setLastSmsMessage('При отправке SMS произошла неизвестная ошибка. Позвоните на горячую линию.');
			}

			return false;
		}
	}

	/**
	 * @return array|bool
	 */

	public function getClientInfo()
	{

		if (isset($this->aClientInfo)) {
			return $this->aClientInfo;
		}

		$aData = array(
			'code'         => self::ERROR_AUTH,
			'client_data'  => array(
				'is_debt'  => null,
				'fullname' => ''
			),
			'active_loan'  => array(
				'balance'    => false,
				'expired'    => false,
				'expired_to' => false
			),
			'subscription' => array(
				'product'         => false,
				'activity_to'     => false,
				'available_loans' => false,
				'moratorium_to'   => false,
				'balance'         => false
			)
		);
		if (!empty($this->token)) {
			//запрос данных по токену
			$aGetData = $this->getData('info');

			if (is_array($aGetData)) {
				//если subscription пустой - делаем unset()
				//это необходимо чтобы массив subscription не был заменен на путой при слиянии массивов
				$aData = CMap::mergeArray($aData, $aGetData);
			}
		}

		$this->aClientInfo = $aData;

		return $aData;
	}

	/**
	 * @return int
	 */
	public function getBalance()
	{
		$aClientInfo = $this->getClientInfo();

		return ($aClientInfo['active_loan']['balance']) ? $aClientInfo['active_loan']['balance'] : 0;
	}

	/**
	 * @return int|number
	 */
	public function getAbsBalance()
	{
		$aClientInfo = $this->getClientInfo();

		return ($aClientInfo['active_loan']['balance']) ? abs($aClientInfo['active_loan']['balance']) : 0;
	}

	/**
	 * @return bool|string
	 */

	public function getSubscriptionProduct()
	{
		$aClientInfo = $this->getClientInfo();

		return (!empty($aClientInfo['subscription']['product'])) ? $aClientInfo['subscription']['product'] : false;
	}

	/**
	 * @return int|number
	 */
	public function getSubscriptionActivity()
	{
		$aClientInfo = $this->getClientInfo();
		$sActivityTo = (!empty($aClientInfo['subscription']['activity_to'])) ? abs($aClientInfo['subscription']['activity_to']) : false;
		$sActivityTo = $this->formatRusDate($sActivityTo);

		return $sActivityTo;
	}

	/**
	 * @return int|number
	 */
	public function getSubscriptionAvailableLoans()
	{
		$aClientInfo = $this->getClientInfo();

		return (!empty($aClientInfo['subscription']['available_loans'])) ? abs($aClientInfo['subscription']['available_loans']) : 0;
	}

	/**
	 * @return bool|string
	 */
	public function getSubscriptionMoratorium()
	{
		$aClientInfo = $this->getClientInfo();
		$sMoratoriumTo = (!empty($aClientInfo['subscription']['moratorium_to'])) ? abs($aClientInfo['subscription']['moratorium_to']) : false;
		$sMoratoriumTo = $this->formatRusDate($sMoratoriumTo);

		return $sMoratoriumTo;
	}

	/**
	 * @return bool|string
	 */
	public function getActiveLoanExpired()
	{
		$aClientInfo = $this->getClientInfo();
		$sExpiredTo = (!empty($aClientInfo['active_loan']['expired_to'])) ? abs($aClientInfo['active_loan']['expired_to']) : false;
		$sExpiredTo = $this->formatRusDate($sExpiredTo);

		return $sExpiredTo;
	}

	/**
	 * @return string
	 */
	public function getClientFullName()
	{
		$aClientInfo = $this->getClientInfo();

		return (!empty($aClientInfo['client_data']['fullname'])) ? $aClientInfo['client_data']['fullname'] : '';
	}

	/**
	 * @return bool
	 */
	public function getIsDebt()
	{
		$aClientInfo = $this->getClientInfo();

		return (!empty($aClientInfo['client_data']['is_debt'])) ? (boolean)$aClientInfo['client_data']['is_debt'] : false;
	}

	/**
	 * @return array
	 */
	public function getHistory()
	{
		$aData = array('code' => self::ERROR_AUTH);
		if (!empty($this->token)) {
			//тут типа запрос данных по токену
			$aGetData = $this->getData('history');

			$aData = array_merge($aData, $aGetData);
		}

		return $aData;
	}

	/**
	 * @return array
	 */
	public function getProducts()
	{
		$aData = array('code' => self::ERROR_AUTH);
		$aGetData = $this->getData('products');
		$aData = array_merge($aData, $aGetData);

		return $aData;
	}

	/**
	 * @param bool   $bGetCode
	 * @param string $sSmsCode
	 *
	 * @return array|bool
	 */
	public function doTest($bGetCode = false, $sSmsCode = '')
	{
		$aResult = array('code' => self::ERROR_AUTH);

		if (!empty($this->token)) {

			if (!empty($sSmsCode)) { //если передан СМС-код, то отправляем его в API
				$aResult = $this->requestAdminKreddyApi(self::API_ACTION_TEST, array('sms_code' => $sSmsCode));
			} elseif ($bGetCode) { //если сделан запрос кода, то запрашиваем код
				$aResult = $this->requestAdminKreddyApi(self::API_ACTION_TEST);
			} else { //если параметры не переданы то запрашиваем статус, нужен ли код подтверждения
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
			//проверяем, какие данные запрошены, и выбираем необходимый экшн и отправляем запрос в API
			switch ($sType) {
				case 'info':
					$sAction = self::API_ACTION_GET_INFO;
					break;
				case 'history':
					$sAction = self::API_ACTION_GET_HISTORY;
					break;
				case 'products':
					$sAction = self::API_ACTION_GET_PRODUCTS;
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
		$ch = curl_init(self::API_URL . $sAction);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('host:ccv'));
		curl_setopt($ch, CURLOPT_POST, true);

		$aRequest = array_merge($aRequest, array('token' => $this->getSessionToken()));

		//TODO убрать
		Yii::trace("Action: " . $sAction . " - Request: " . CJSON::encode($aRequest));

		curl_setopt($ch, CURLOPT_POSTFIELDS, $aRequest);

		$response = curl_exec($ch);

		//TODO убрать
		Yii::trace("Action: " . $sAction . " - Response: " . $response);

		$aData = CJSON::decode($response);

		/*if{

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
		}*/

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
		// очищаем сессии, связанные с отправкой SMS
		$this->clearSmsState();

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
	 * коды 0, 9 - авторизация в порядке
	 *
	 * @return bool
	 */
	public function isAuth()
	{
		$aInfo = Yii::app()->adminKreddyApi->getClientInfo();
		$iStatus = $this->getResultStatus($aInfo);

		return ($iStatus === self::ERROR_NONE || $iStatus === self::ERROR_NEED_SMS_AUTH || $iStatus === self::ERROR_NEED_SMS_CODE);
	}

	/**
	 * Проверяем, требует ли ответ API авторизации по СМС
	 *
	 * @param $aResult
	 *
	 * @return bool
	 */
	public function getIsNeedSmsAuth($aResult)
	{
		$iStatus = $this->getResultStatus($aResult);

		return ($iStatus === 9);
	}

	/**
	 * Проверяем, требует ли action API авторизации по СМС
	 *
	 * @param $aResult
	 *
	 * @return bool
	 */
	public function getIsNeedSmsCode($aResult)
	{
		$iStatus = $this->getResultStatus($aResult);

		return ($iStatus === 10);
	}

	/**
	 * очищаем сессии, связанные с отправкой SMS
	 */
	private function clearSmsState()
	{
		$this->clearSmsPassState();
		$this->clearResetPassSmsCodeState();
	}

	/**
	 * очищаем сессии, связанные с отправкой SMS (форма Восстановления пароля)
	 */
	public function clearResetPassSmsCodeState()
	{
		Yii::app()->session['resetPassSmsCodeSent'] = null;
		Yii::app()->session['resetPassSmsCodeSentTime'] = null;
		Yii::app()->session['resetPassSmsCodeLeftTime'] = null;
		Yii::app()->session['resetPasswordPhone'] = null;
	}

	/**
	 * очищаем сессии, связанные с отправкой SMS (форма SMS пароль)
	 */
	public function clearSmsPassState()
	{
		Yii::app()->session['smsPassSent'] = null;
		Yii::app()->session['smsPassSentTime'] = null;
		Yii::app()->session['smsPassLeftTime'] = null;
		Yii::app()->session['smsAuthDone'] = null;
	}

	/**
	 * @return bool
	 */
	public function checkSmsPassSent()
	{
		return (!empty(Yii::app()->session['smsPassSent']));
	}

	/**
	 * @return int|''
	 */
	public function getSmsPassSentTime()
	{
		return (!empty(Yii::app()->session['smsPassSentTime'])) ? Yii::app()->session['smsPassSentTime'] : '';
	}

	/**
	 * @return bool
	 */
	public function checkResetPassSmsCodeSent()
	{
		return (!empty(Yii::app()->session['resetPassSmsCodeSent']));
	}

	/**
	 * @return int|''
	 */
	public function getResetPassSmsCodeSentTime()
	{
		return (!empty(Yii::app()->session['resetPassSmsCodeSentTime'])) ? Yii::app()->session['resetPassSmsCodeSentTime'] : '';
	}

	/**
	 * @param $sPhone string
	 */
	public function setResetPassPhone($sPhone)
	{
		Yii::app()->session['resetPasswordPhone'] = $sPhone;
	}

	/**
	 * Загрузка из сессии сохраненного номера телефона, указанного в форме восстановления пароля
	 *
	 * @return string
	 */
	public function getResetPassPhone()
	{
		return (!empty(Yii::app()->session['resetPasswordPhone'])) ? Yii::app()->session['resetPasswordPhone'] : '';
	}

	/**
	 * Проверяем, есть ли в сессии номер телефна, указанный в форме восстановления пароля
	 * @return bool
	 */
	public function checkResetPassPhone()
	{
		return (!empty(Yii::app()->session['resetPasswordPhone']));
	}

	/**
	 *
	 * Проверяем статус СМС-авторизации для переданного результата
	 *
	 * @param $aResult
	 *
	 * @return bool
	 */

	public function checkSmsAuthStatus($aResult)
	{
		if ($aResult['sms_status'] == self::SMS_AUTH_OK) {
			Yii::app()->session['smsAuthDone'] = true;

			return true;
		}

		return false;
	}

	/**
	 * Проверяем, авторизован ли пользователь через СМС-пароль
	 *
	 * @return bool
	 */
	public function isSmsAuth()
	{
		return (!empty(Yii::app()->session['smsAuthDone'])) ? Yii::app()->session['smsAuthDone'] : false;
	}

	/**
	 * @return CSort
	 */

	private function getHistorySort()
	{
		$sort = new CSort;
		$sort->defaultOrder = 'time DESC';
		$sort->attributes = array('time', 'type', 'type_id', 'amount');

		return $sort;
	}

	/**
	 * @return \CArrayDataProvider
	 */
	public function getHistoryDataProvider()
	{
		$aHistory = $this->getHistory();
		if (isset($aHistory) && $aHistory['code'] === 0 && isset($aHistory['history'])) {
			$oHistoryDataProvider = new CArrayDataProvider($aHistory['history'],
				array(
					'keyField' => 'time',
					'sort'     => $this->getHistorySort()
				)
			);
		} else {
			$oHistoryDataProvider = new CArrayDataProvider(array());
		}

		return $oHistoryDataProvider;
	}

	/**
	 * @return integer
	 */
	public function getSmsPassLeftTime()
	{
		$curTime = time();
		$leftTime = (!empty(Yii::app()->session['smsPassSentTime']))
			? Yii::app()->session['smsPassSentTime']
			: $curTime;
		$leftTime = $curTime - $leftTime;
		$leftTime = SiteParams::API_MINUTES_UNTIL_RESEND * 60 - $leftTime;

		return $leftTime;
	}

	public function setSmsPassSentAndTime()
	{
		Yii::app()->session['smsPassSent'] = true;
		Yii::app()->session['smsPassSentTime'] = time();
		Yii::app()->session['smsPassLeftTime'] = SiteParams::API_MINUTES_UNTIL_RESEND * 60;
	}

	/**
	 * Получаем время, оставшееся до возможности повторной отправки SMS (форма Восстановление пароля)
	 *
	 * @return integer
	 */
	public function getResetPassSmsCodeLeftTime()
	{
		$iCurTime = time();
		$iLeftTime = (!empty(Yii::app()->session['resetPassSmsCodeSentTime']))
			? Yii::app()->session['resetPassSmsCodeSentTime']
			: $iCurTime;
		$iLeftTime = $iCurTime - $iLeftTime;
		$iLeftTime = SiteParams::API_MINUTES_UNTIL_RESEND * 60 - $iLeftTime;

		return $iLeftTime;
	}

	public function setResetPassSmsCodeSentAndTime()
	{
		Yii::app()->session['resetPassSmsCodeSent'] = true;
		Yii::app()->session['resetPassSmsCodeSentTime'] = time();
		Yii::app()->session['resetPassSmsCodeLeftTime'] = SiteParams::API_MINUTES_UNTIL_RESEND * 60;
	}

	/**
	 * @param $sDate
	 *
	 * @return bool|string
	 */
	public function formatRusDate($sDate)
	{
		if (!is_numeric($sDate)) {
			$sDate = strtotime($sDate);
		}

		if ($sDate) {
			$sDate = date('d.m.Y H:i', $sDate);
		}

		return $sDate;
	}

	/**
	 * @param $sMessage
	 */

	public function setLastSmsMessage($sMessage)
	{
		$this->sLastSmsMessage = $sMessage;
	}

	/**
	 * @return string
	 */
	public function getLastSmsMessage()
	{
		return $this->sLastSmsMessage;
	}
}
