<?php
/**
 * Class AdminKreddyApiComponent
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
	const ERROR_NOT_ALLOWED = 11;

	const SMS_AUTH_OK = 0;
	const SMS_SEND_OK = 1;
	const SMS_CODE_ERROR = 2;
	const SMS_BLOCKED = 3;
	const SMS_CODE_TRIES_EXCEED = 4;

	const SMS_PASSWORD_SEND_OK = 1;

	const API_ACTION_SUBSCRIBE = 'siteClient/doSubscribe';
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
	private $aProducts;
	private $iLastCode;
	private $sLastMessage = '';
	private $sLastSmsMessage = '';


	public $sApiUrl = '';
	public $sTestApiUrl = '';


	/**
	 * @return array
	 */

	public function attributeNames()
	{
		return array('token' => 'Token');
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
	 * Авторизация в API, получаем токен и сохраняем в сессию
	 *
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
	 * Обновляем токен, выполняется при инициализации компонента
	 *
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
	 * Получаем СМС-авторизацию по СМС-паролю для доступа к закрытым данным
	 *
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

		if ($aResult['code'] == self::ERROR_NEED_SMS_CODE && $aResult['sms_status'] == self::SMS_SEND_OK) {
			//устанавливаем флаг "СМС отправлено" и время отправки
			Yii::app()->adminKreddyApi->setSmsPassSentAndTime();
			$this->setLastSmsMessage($aResult['sms_message']);

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
	 * Отправка СМС-кода для подтверждения восстановления пароля
	 *
	 * @param      $sPhone
	 * @param bool $bResend
	 *
	 * @return mixed
	 */
	public function resetPasswordSendSms($sPhone, $bResend = false)
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_RESET_PASSWORD, array('phone' => $sPhone, 'sms_resend' => (int)$bResend));
		//если результат успешный
		if ($aResult['code'] == self::ERROR_NEED_SMS_CODE && $aResult['sms_status'] == self::SMS_SEND_OK) {
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
				$this->setLastSmsMessage('При отправке SMS произошла неизвестная ошибка. Позвоните на горячую линию.');
			}
		}

		return false;
	}

	/**
	 * Проверка СМС-кола для подтверждения восстановления пароля
	 *
	 * @param $sPhone
	 * @param $sSmsCode
	 *
	 * @return mixed
	 */
	public function resetPasswordCheckSms($sPhone, $sSmsCode)
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_RESET_PASSWORD, array('phone' => $sPhone, 'sms_code' => $sSmsCode));

		if ($aResult['sms_status'] == self::SMS_AUTH_OK) {
			$this->setLastSmsMessage($aResult['sms_message']);

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
	 * Получение основной информации о клиенте в виде массива
	 *
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
		$sActivityTo = (!empty($aClientInfo['subscription']['activity_to'])) ? $aClientInfo['subscription']['activity_to'] : false;
		$sActivityTo = $this->formatRusDate($sActivityTo);

		return $sActivityTo;
	}

	/**
	 * @return int|number
	 */
	public function getSubscriptionAvailableLoans()
	{
		$aClientInfo = $this->getClientInfo();

		return (!empty($aClientInfo['subscription']['available_loans'])) ? $aClientInfo['subscription']['available_loans'] : 0;
	}

	/**
	 * @return bool|string
	 */
	public function getSubscriptionMoratorium()
	{
		$aClientInfo = $this->getClientInfo();
		$sMoratoriumTo = (isset($aClientInfo['subscription']['moratorium_to']))
			? $aClientInfo['subscription']['moratorium_to']
			: null;
		$sMoratoriumTo = $this->formatRusDate($sMoratoriumTo);

		return $sMoratoriumTo;
	}

	/**
	 * @return bool|string
	 */
	public function getActiveLoanExpired()
	{
		$aClientInfo = $this->getClientInfo();
		$sExpiredTo = (!empty($aClientInfo['active_loan']['expired_to']))
			? $aClientInfo['active_loan']['expired_to']
			: false;
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
	 * Получение истории операций в виде массива
	 *
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
	 * Сортировщик для истории операций
	 *
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
	 * DataProvider для истории операций
	 *
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
	 * Получение массива с информацией о продуктах
	 *
	 * @return array
	 */
	public function getProducts()
	{
		if (isset($this->aProducts)) {
			return $this->aProducts;
		}
		//TODO сделать получение и сохранение способов получения продукта
		$aGetData = $this->getData('products');
		if (isset($aGetData['products'])) {
			$aProducts = array();
			foreach ($aGetData['products'] as $product) {
				$aProducts[$product['id']] = $product;
			}
			$this->aProducts = $aProducts;

			return $aProducts;
		}

		return false;
	}


	/**
	 * Получение списка продуктов
	 *
	 * @return array
	 */
	public function getProductsList()
	{
		$aProducts = $this->getProducts();
		if ($aProducts) {
			$aProductsList = array();
			foreach ($aProducts as $aProduct) {
				$aProductsList[$aProduct['id']] = $aProduct['name'];
			}

			return $aProductsList;
		}

		return false;

	}

	/**
	 * Получение названия продукта по ID
	 *
	 * @param $iProductId
	 *
	 * @return bool|string
	 */

	public function getProductNameById($iProductId)
	{
		$aProducts = $this->getProducts();

		return (isset($aProducts[$iProductId]['name']))
			? $aProducts[$iProductId]['name']
			: false;
	}

	/**
	 * Получение стоимости продукта по ID
	 *
	 * @param $iProductId
	 *
	 * @return bool|string
	 */

	public function getProductCostById($iProductId)
	{
		$aProducts = $this->getProducts();

		return (isset($aProducts[$iProductId]['subscription_cost']))
			? $aProducts[$iProductId]['subscription_cost']
			: false;
	}

	/**
	 * Получение срока действия продукта по ID
	 *
	 * @param $iProductId
	 *
	 * @return bool|string
	 */

	public function getProductLifetimeById($iProductId)
	{
		$aProducts = $this->getProducts();

		return (isset($aProducts[$iProductId]['subscription_lifetime']))
			? ($aProducts[$iProductId]['subscription_lifetime'] / 3600 / 24)
			: false;
	}

	/**
	 * Получение суммы займа по ID продукта
	 *
	 * @param $iProductId
	 *
	 * @return bool|string
	 */

	public function getProductLoanAmountById($iProductId)
	{
		$aProducts = $this->getProducts();

		return (isset($aProducts[$iProductId]['loan_amount']))
			? $aProducts[$iProductId]['loan_amount']
			: false;
	}

	/**
	 * Получения количества займов для продукта по ID
	 *
	 * @param $iProductId
	 *
	 * @return bool|string
	 */

	public function getProductLoanCountById($iProductId)
	{
		$aProducts = $this->getProducts();

		return (isset($aProducts[$iProductId]['loan_count']))
			? $aProducts[$iProductId]['loan_count']
			: false;
	}

	/**
	 * Получение срока займа по ID продукта
	 *
	 * @param $iProductId
	 *
	 * @return bool|string
	 */

	public function getProductLoanLifetimeById($iProductId)
	{
		$aProducts = $this->getProducts();

		return (isset($aProducts[$iProductId]['loan_lifetime']))
			? ($aProducts[$iProductId]['loan_lifetime'] / 3600 / 24)
			: false;
	}

	/**
	 * Проверка возможности подписки
	 *
	 * @return bool
	 */
	public function checkSubscribe()
	{
		//проверяем возможность подписки
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_SUBSCRIBE, array('test_code' => 1));

		return (($aResult['code'] !== self::ERROR_NOT_ALLOWED) && ($aResult['code'] !== self::ERROR_NEED_SMS_AUTH));
	}

	/**
	 * Отправка СМС с кодом подтверждения подписки
	 *
	 * @return bool
	 */
	public function sendSmsSubscribe()
	{
		//отправляем СМС с кодом
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_SUBSCRIBE);

		if ($aResult['code'] === self::ERROR_NEED_SMS_CODE && $aResult['sms_status'] === self::SMS_SEND_OK) {
			$this->setLastSmsMessage($aResult['sms_message']);

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
	 * Подписка, подписанная СМС-кодом
	 *
	 * @param string $sSmsCode
	 *
	 * @param        $iProduct
	 * @param        $iChannelType
	 *
	 * @return array|bool
	 */
	public function doSubscribe($sSmsCode, $iProduct, $iChannelType)
	{

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_SUBSCRIBE, array('sms_code' => $sSmsCode, 'product_id' => $iProduct, 'channel_type' => $iChannelType));

		if ($aResult['code'] === self::ERROR_NONE && $aResult['sms_status'] === self::SMS_AUTH_OK) {
			$this->setLastSmsMessage($aResult['sms_message']);

			return true;
		} else {
			if (isset($aResult['sms_message'])) {
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setLastSmsMessage('Произошла неизвестная ошибка. Позвоните на горячую линию.');
			}

			return false;
		}
	}

	/**
	 * Сохранение выбранного продукта в сессию
	 *
	 * @param $iProductId
	 */
	public function setSubscribeSelectedProduct($iProductId)
	{
		Yii::app()->session['subscribeSelectedProduct'] = $iProductId;
	}

	/**
	 * Получение выбранного продукта из сессии
	 *
	 * @return bool
	 */
	public function getSubscribeSelectedProduct()
	{
		return (isset(Yii::app()->session['subscribeSelectedProduct']))
			? Yii::app()->session['subscribeSelectedProduct'] :
			false;
	}

	/**
	 * Роутер запросов для получения данных
	 * Получает запросы на данные и перенаправляет на requestAdminKreddyApi() с нужным экшном
	 *
	 * @param $sType
	 *
	 * @return array|mixed
	 */

	private function getData($sType)
	{
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


		return $aData;
	}

	/**
	 * Интерфейс для обращения к API через curl
	 *
	 * @param       $sAction
	 * @param array $aRequest
	 *
	 * @return mixed
	 */

	private function requestAdminKreddyApi($sAction, $aRequest = array())
	{
		$sApiUrl = (!Yii::app()->params['bApiTestModeIsOn']) ? $this->sApiUrl : $this->sTestApiUrl;
		$aData = array('code' => self::ERROR_AUTH, 'message' => 'Произошла неизвестная ошибка. Позвоните на горячую линию.');


		$ch = curl_init($sApiUrl . $sAction);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('host:ccv'));
		curl_setopt($ch, CURLOPT_POST, true);

		$aRequest = array_merge($aRequest, array('token' => $this->getSessionToken()));

		//TODO убрать
		Yii::trace("Action: " . $sAction . " - Request: " . CJSON::encode($aRequest));

		curl_setopt($ch, CURLOPT_POSTFIELDS, $aRequest);

		$response = curl_exec($ch);

		if ($response) {
			//TODO убрать
			Yii::trace("Action: " . $sAction . " - Response: " . $response);
			$aGetData = CJSON::decode($response);

			if (is_array($aData)) {
				$aData = CMap::mergeArray($aData, $aGetData);
			}
		}
		$this->setLastMessage($aData['message']);
		$this->setLastCode($aData['code']);

		return $aData;
	}

	/**
	 * Получение токена, сохраненного в сессию
	 *
	 * @return mixed
	 */
	private function getSessionToken()
	{
		return Yii::app()->session['akApi_token'];
	}

	/**
	 * Сохранение токена в сессию
	 *
	 * @param $token
	 */
	private function setSessionToken($token)
	{
		Yii::app()->session['akApi_token'] = $token;
	}

	/**
	 * Логаут, чистит данные в сессии и удаляет токен
	 */
	public function logout()
	{
		// очищаем сессии, связанные с отправкой SMS
		$this->clearSmsState();

		$this->setSessionToken(null);
	}

	/**
	 * Передаем полученный от API результат и извлекаем из него код
	 *
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
	 * @return bool
	 */
	public function getIsAuth()
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

		return ($iStatus === self::ERROR_NEED_SMS_AUTH);
	}

	/**
	 * Проверяем, требует ли action API подтверждения одноразовым СМС-кодом
	 *
	 * @param $aResult
	 *
	 * @return bool
	 */
	public function getIsNeedSmsCode($aResult)
	{
		$iStatus = $this->getResultStatus($aResult);

		return ($iStatus === self::ERROR_NEED_SMS_CODE);
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
	 * Проверяем, отправлено ли СМС с паролем аутентификации
	 * @return bool
	 */
	public function checkSmsPassSent()
	{
		return (!empty(Yii::app()->session['smsPassSent']));
	}

	/**
	 * Возвращаем время отправки СМС-пароля
	 * @return int|''
	 */
	public function getSmsPassSentTime()
	{
		return (!empty(Yii::app()->session['smsPassSentTime'])) ? Yii::app()->session['smsPassSentTime'] : '';
	}

	/**
	 * Проверяем, отправлено ли СМС с кодом подтверждения восстановления пароля
	 * @return bool
	 */
	public function checkResetPassSmsCodeSent()
	{
		return (!empty(Yii::app()->session['resetPassSmsCodeSent']));
	}

	/**
	 * Возвращаем время отправки СМС с кодом подтверждения восстановления пароля
	 * @return int|''
	 */
	public function getResetPassSmsCodeSentTime()
	{
		return (!empty(Yii::app()->session['resetPassSmsCodeSentTime'])) ? Yii::app()->session['resetPassSmsCodeSentTime'] : '';
	}

	/**
	 * Сохраняем в сессию телефон, на который запрошено восстановление пароля
	 *
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
	public function getIsSmsAuth()
	{
		return (!empty(Yii::app()->session['smsAuthDone'])) ? Yii::app()->session['smsAuthDone'] : false;
	}


	/**
	 * Возвращаем время (в секундах), оставшееся до момента, когда можно запросить СМС-пароль повторно
	 *
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

	/**
	 * Сохраняем время отправки СМС-пароля и ставим флаг "СМС отправлено"
	 */
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

	/**
	 * Сохраняем время отправки СМС-кода для восстановления пароля и ставим флаг "СМС отправлено"
	 */
	public function setResetPassSmsCodeSentAndTime()
	{
		Yii::app()->session['resetPassSmsCodeSent'] = true;
		Yii::app()->session['resetPassSmsCodeSentTime'] = time();
		Yii::app()->session['resetPassSmsCodeLeftTime'] = SiteParams::API_MINUTES_UNTIL_RESEND * 60;
	}

	/**
	 * Форматируем дату в вид 01.01.2013 00:00
	 *
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
	 * Сохраняем последнее полученное сообщение о СМС-статусе запроса
	 *
	 * @param $sMessage
	 */

	public function setLastSmsMessage($sMessage)
	{
		$this->sLastSmsMessage = $sMessage;
	}

	/**
	 * Возвращаем последнее полученное сообщение о СМС-статусе запроса
	 *
	 * @return string
	 */
	public function getLastSmsMessage()
	{
		return $this->sLastSmsMessage;
	}

	/**
	 * Сохраняем последнее полученное сообщение о статусе запроса
	 *
	 * @param $sMessage
	 */

	public function setLastMessage($sMessage)
	{
		$this->sLastMessage = $sMessage;
	}

	/**
	 * Возвращаем последнее полученное сообщение о статусе запроса
	 *
	 * @return string
	 */
	public function getLastMessage()
	{
		return $this->sLastMessage;
	}

	/**
	 * Сохраняем последний полученный код статуса запроса
	 *
	 * @param $iCode
	 *
	 */

	public function setLastCode($iCode)
	{
		$this->iLastCode = $iCode;
	}

	/**
	 * Возвращаем последний полученный код статуса запроса
	 *
	 * @return integer
	 */
	public function getLastCode()
	{
		return $this->iLastCode;
	}
}
