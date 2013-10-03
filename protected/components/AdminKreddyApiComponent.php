<?php
/**
 * Class AdminKreddyApiComponent
 */


class AdminKreddyApiComponent
{

	/**
	 * Статусы API
	 */

	const C_CLIENT_NEW = 'client_new';
	const C_CLIENT_ACTIVE = 'client_active';

	const C_CLIENT_MORATORIUM_LOAN = 'client_moratorium_loan';
	const C_CLIENT_MORATORIUM_SUBSCRIPTION = 'client_moratorium_subscription';
	const C_CLIENT_MORATORIUM_SCORING = 'client_moratorium_scoring';

	const C_SCORING_PROGRESS = 'scoring_progress';
	const C_SCORING_CANCEL = 'scoring_cancel';
	const C_SCORING_ACCEPT = 'scoring_accept';

	const C_SUBSCRIPTION_AVAILABLE = 'subscription_available';
	const C_SUBSCRIPTION_PAYMENT = 'subscription_payment';
	const C_SUBSCRIPTION_PAID = 'subscription_paid';
	const C_SUBSCRIPTION_CANCEL = 'subscription_cancel';
	const C_SUBSCRIPTION_ACTIVE = 'subscription_active';

	const C_LOAN_AVAILABLE = 'loan_available';
	const C_LOAN_CREATED = 'loan_created';
	const C_LOAN_TRANSFER = 'loan_transfer';
	const C_LOAN_ACTIVE = 'loan_active';
	const C_LOAN_DEBT = 'loan_debt';
	const C_LOAN_PAID = 'loan_paid';

	const C_STATUS_ERROR = 'Ошибка!';

	const C_SUBSCRIPTION_NOT_AVAILABLE = "Извините, подключение Пакета недоступно. {account_url_start}Посмотреть информацию о Пакете{account_url_end}";
	const C_LOAN_NOT_AVAILABLE = "Извините, оформление займа недоступно. {account_url_start}Посмотреть информацию о Пакете{account_url_end}";

	const C_DO_SUBSCRIBE_MSG_SCORING_ACCEPTED = 'Ваша заявка одобрена. Для получения займа необходимо оплатить подключение в размере {sub_pay_sum} рублей любым удобным способом. {account_url_start}Посмотреть информацию о Пакете{account_url_end}';
	const C_DO_SUBSCRIBE_MSG = 'Ваша заявка принята. Ожидайте решения.';
	const C_DO_LOAN_MSG = 'Ваша заявка оформлена. Займ поступит {channel_name} в течение нескольких минут. ';

	private $aAvailableStatuses = array(

		self::C_CLIENT_MORATORIUM_LOAN         => 'Временно недоступно получение новых займов',
		self::C_CLIENT_MORATORIUM_SCORING      => 'Заявка отклонена',
		self::C_CLIENT_MORATORIUM_SUBSCRIPTION => 'Временно недоступно подключение новых Пакетов',

		self::C_SUBSCRIPTION_ACTIVE            => 'Подключен к Пакету',
		self::C_SUBSCRIPTION_AVAILABLE         => 'Доступно подключение к Пакету',
		self::C_SUBSCRIPTION_CANCEL            => 'Срок оплаты подключения истек',
		self::C_SUBSCRIPTION_PAID              => 'Займ доступен',
		self::C_SUBSCRIPTION_PAYMENT           => 'Оплатите подключение в размере {sub_pay_sum} рублей любым удобным способом. {payments_url_start}Подробнее{payments_url_end}',

		self::C_SCORING_PROGRESS               => 'Заявка в обработке. {account_url_start}Обновить статус{account_url_end}', //+
		// TODO: убрать обращение в отделение Кредди после внедрения новой схемы #1153
		self::C_SCORING_ACCEPT                 => 'Для получения займа необходимо обратиться в отделение Кредди по адресу: {contacts_url_start}Москва, шоссе Энтузиастов 12, корп. 2, ТЦ Город{contacts_url_end}.',
		self::C_SCORING_CANCEL                 => 'Заявка отклонена',

		self::C_LOAN_DEBT                      => 'Задолженность по займу',
		self::C_LOAN_ACTIVE                    => 'Займ перечислен', //+
		self::C_LOAN_TRANSFER                  => 'Займ перечислен', //+
		self::C_LOAN_AVAILABLE                 => 'Займ доступен',
		self::C_LOAN_CREATED                   => 'Займ перечислен', //+
		self::C_LOAN_PAID                      => 'Займ оплачен',

		self::C_CLIENT_ACTIVE                  => 'Доступно подключение Пакета', //+
		self::C_CLIENT_NEW                     => 'Выберите Пакет займов',
	);

	const ERROR_NONE = 0; //нет ошибок
	const ERROR_UNKNOWN = 1; //неизвестная ошибка
	const ERROR_AUTH = 2; //ошибка авторизации
	const ERROR_TOKEN_DATA = 3; //ошибочные данные в токене
	const ERROR_TOKEN_VERIFY = 4; //ошибка проверки токена
	const ERROR_TOKEN_EXPIRE = 5; //токен просрочен
	const ERROR_TOKEN_NOT_EXIST = 6; //токен не существует
	const CLIENT_NOT_EXIST = 7; //клиент не существует
	const CLIENT_DATA_NOT_EXIST = 8; //данные клиента не существуют
	const ERROR_NEED_SMS_AUTH = 9; //требуется СМС-авторизация
	const ERROR_NEED_SMS_CODE = 10; //требуется подтверждение СМС-кодом
	const ERROR_NOT_ALLOWED = 11; //действие недоступно
	const ERROR_NEED_IDENTIFY = 16; //действие недоступно

	const SMS_AUTH_OK = 0; //СМС-авторизация успешна (СМС-код верный)
	const SMS_SEND_OK = 1; //СМС с кодом/паролем отправлена
	const SMS_CODE_ERROR = 2; //неверный СМС-код
	const SMS_BLOCKED = 3; //отправка СМС заблокирована
	const SMS_CODE_TRIES_EXCEED = 4; //попытки ввода СМС-кода исчерпаны

	const API_ACTION_CHECK_IDENTIFY = 'video/heldIdentification';
	const API_ACTION_GET_IDENTIFY = 'video/getIdentify';
	const API_ACTION_CREATE_CLIENT = 'siteClient/signup';
	const API_ACTION_CHECK_SUBSCRIBE = 'siteClient/checkSubscribe';
	const API_ACTION_SUBSCRIBE = 'siteClient/doSubscribe';
	const API_ACTION_CHECK_LOAN = 'siteClient/checkLoan';
	const API_ACTION_LOAN = 'siteClient/doLoan';
	const API_ACTION_TOKEN_UPDATE = 'siteToken/update';
	const API_ACTION_TOKEN_CREATE = 'siteToken/create';
	const API_ACTION_GET_INFO = 'siteClient/getInfo';
	const API_ACTION_GET_HISTORY = 'siteClient/getPaymentHistory';
	const API_ACTION_RESET_PASSWORD = 'siteClient/resetPassword';
	const API_ACTION_GET_PRODUCTS = 'siteClient/getProducts';

	const API_ACTION_REQ_SMS_CODE = 'siteClient/authBySms';
	const API_ACTION_CHECK_SMS_CODE = 'siteClient/authBySms';

	const API_ACTION_ADD_CARD = 'siteClient/addClientCard';
	const API_ACTION_VERIFY_CARD = 'siteClient/verifyClientCard';
	const API_ACTION_CHECK_CAN_ADD_CARD = 'siteClient/checkClientCanVerifyCard';

const ERROR_MESSAGE_UNKNOWN = 'Произошла неизвестная ошибка. Позвоните на горячую линию.';
	const C_NO_AVAILABLE_PRODUCTS = "Доступные способы перечисления займа отсутствуют.";

	const C_CARD_SUCCESSFULLY_VERIFIED = "Карта успешно привязана!";

	private $token;
	private $aClientInfo; //массив с данными клиента
	private $iLastCode; //code из последнего выполненного запроса
	private $sLastMessage = ''; //message из последнего выполненного запроса
	private $sLastSmsMessage = ''; //sms_message из последнего выполненного запроса
	private $bIsCanSubscribe = null; //клиент может оформить подписку
	private $bIsCanGetLoan = null; //клиент может взять заём
	private $bScoringAccepted = null;

	public $sApiUrl = '';
	public $sTestApiUrl = '';


	/**
	 * Заменяет в сообщениях Клиенту шаблоны на вычисляемые значения
	 *
	 * @return array
	 */
	public function formatStatusMessage()
	{
		// берём ID продукта из сессии, если есть
		$iProductId = $this->getSubscribeSelectedProductId();
		if (!$iProductId) {
			// если нет в сессии - из ответа API
			$iProductId = $this->getSubscriptionProductId();
		}

		return array(
			'{sub_pay_sum}'        => $this->getProductCostById($iProductId), // стоимость подключения

			'{channel_name}'       => SiteParams::mb_lcfirst($this->getChannelNameById($this->getLoanSelectedChannel())), // название канала

			'{account_url_start}'  => CHtml::openTag("a", array(
				"href" => Yii::app()->createUrl("/account")
			)), // ссылка на инфо о пакете
			'{account_url_end}'    => CHtml::closeTag("a"), // /ссылка на инфо о пакете

			'{payments_url_start}' => CHtml::openTag("a", array(
				"href" => Yii::app()->createUrl("pages/view/payments"), "target" => "_blank"
			)), // ссылка на инфо о возможных вариантах оплаты
			'{payments_url_end}'   => CHtml::closeTag("a"), // /ссылка на инфо о возможных вариантах оплаты

			'{contacts_url_start}' => CHtml::openTag("a",
				array("href" => "#fl-contacts", "data-target" => "#fl-contacts", "data-toggle" => "modal")), // ссылка на инфо об отделении
			'{contacts_url_end}'   => CHtml::closeTag("a"), // /ссылка на инфо об отделении
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array('token' => 'Token');
	}

	/**
	 * При инициализации обязательно требуется запросить обновление токена
	 */
	public function init()
	{
		$this->token = $this->getSessionToken();
		if (!empty($this->token)) {
			//если токен существует, то запрашиваем его обновление
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
		}

		return false;
	}

	/**
	 *
	 * @param $aClientData
	 *
	 * @return bool
	 */
	public function createClient($aClientData)
	{
		$aRequest = array('clientData' => CJSON::encode($aClientData));
		$aTokenData = $this->requestAdminKreddyApi(self::API_ACTION_CREATE_CLIENT, $aRequest);

		if ($aTokenData['code'] === self::ERROR_NONE) {
			$this->setSessionToken($aTokenData['token']);
			$this->token = $aTokenData['token'];
			$this->setSmsAuthDone(true);

			return true;
		}

		return false;
	}

	/**
	 * Обновляем токен, обязательно выполняется при инициализации компонента
	 *
	 * @return bool
	 */
	protected function updateClientToken()
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
			$this->setSmsAuthDone(true);

			return true;
		} else {
			//проверяем, получили ли мы sms_message
			if (isset($aResult['sms_message'])) {
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setLastSmsMessage(self::ERROR_MESSAGE_UNKNOWN);
			}
		}

		return false;
	}

	/**
	 * Запрос от API СМС-пароля для СМС-авторизации
	 *
	 * @param bool $bResend
	 *
	 * @return bool
	 */
	public function sendSmsPassword($bResend = false)
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_REQ_SMS_CODE, array('sms_resend' => (int)$bResend));

		if ($aResult['code'] === self::ERROR_NEED_SMS_CODE && $aResult['sms_status'] === self::SMS_SEND_OK) {
			//устанавливаем флаг "СМС отправлено" и время отправки
			Yii::app()->adminKreddyApi->setSmsPassSentAndTime();
			$this->setLastSmsMessage($aResult['sms_message']);

			return true;
		} else {
			//проверяем, получили ли мы sms_message
			if (isset($aResult['sms_message'])) {
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setLastSmsMessage(self::ERROR_MESSAGE_UNKNOWN);
			}
		}

		return false;
	}

	/**
	 * Запрос от API СМС-кода для подтверждения восстановления пароля
	 *
	 * @param      $sPhone
	 * @param bool $bResend
	 *
	 * @return bool
	 */
	public function resetPasswordSendSms($sPhone, $bResend = false)
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_RESET_PASSWORD, array('phone' => $sPhone, 'sms_resend' => (int)$bResend));
		//если результат успешный
		if ($aResult['code'] === self::ERROR_NEED_SMS_CODE && $aResult['sms_status'] === self::SMS_SEND_OK) {
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
				$this->setLastSmsMessage(self::ERROR_MESSAGE_UNKNOWN);
			}
		}

		return false;
	}

	/**
	 * Проверка СМС-кода для подтверждения восстановления пароля
	 *
	 * @param $sPhone
	 * @param $sSmsCode
	 *
	 * @return string|bool
	 */
	public function resetPasswordCheckSms($sPhone, $sSmsCode)
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_RESET_PASSWORD, array('phone' => $sPhone, 'sms_code' => $sSmsCode));

		if ($aResult['sms_status'] === self::SMS_AUTH_OK) {
			$this->setLastSmsMessage($aResult['sms_message']);

			return true;
		} else {
			if (isset($aResult['sms_message'])) {
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setLastSmsMessage(self::ERROR_MESSAGE_UNKNOWN);
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
				'balance'         => false
			),
			'moratoriums'  => array(
				'loan'         => false,
				'subscription' => false,
				'scoring'      => false,
			)
		);

		if (!empty($this->token)) {
			//запрос данных по токену
			$aGetData = $this->getData('info');

			if (is_array($aGetData)) {
				$aData = CMap::mergeArray($aData, $aGetData);
			}
		}

		$this->aClientInfo = $aData;

		return $aData;
	}

	/**
	 * Получаем массив с каналами, доступными клиенту
	 * array('kreddy','mobile')
	 *
	 * @return array
	 */
	public function getClientChannels()
	{
		$aClientInfo = $this->getClientInfo();
		if (isset($aClientInfo['channels']) && is_array($aClientInfo['channels'])) {
			return $aClientInfo['channels'];
		} else {
			return array();
		}
	}

	/**
	 * Получаем имя канала по его id
	 *
	 * @param $iChannel
	 *
	 * @return string|bool
	 */
	public function getChannelNameById($iChannel)
	{
		$aChannels = $this->getProductsChannels();

		return (isset($aChannels[$iChannel])) ? $aChannels[$iChannel] : false;
	}

	/**
	 * Получение сообщения статуса (активен, в скоринге, ожидает оплаты)
	 *
	 * @return string|bool
	 */
	public function getStatusMessage()
	{
		$aClientInfo = $this->getClientInfo();

		$sStatusName = (!empty($aClientInfo['status']['name'])) ? $aClientInfo['status']['name'] : false;

		$sStatus = (!empty($this->aAvailableStatuses[$sStatusName])) ? $this->aAvailableStatuses[$sStatusName] : self::C_STATUS_ERROR;

		$sStatus = strtr($sStatus, $this->formatStatusMessage());

		return $sStatus;
	}

	/**
	 * Получение баланса
	 *
	 * @return int
	 */
	public function getBalance()
	{
		$aClientInfo = $this->getClientInfo();

		return ($aClientInfo['active_loan']['balance']) ? $aClientInfo['active_loan']['balance'] : 0;
	}

	/**
	 * Получение абсолютного значения баланса
	 *
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
	public function getSubscriptionRequest()
	{
		$aClientInfo = $this->getClientInfo();

		return (!empty($aClientInfo['subscription_request'])) ? $aClientInfo['subscription_request'] : false;
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
	 * @return bool|string
	 */
	public function getSubscriptionProductId()
	{
		$aClientInfo = $this->getClientInfo();

		return (!empty($aClientInfo['subscription']['product_id'])) ? $aClientInfo['subscription']['product_id'] : false;
	}

	/**
	 * @return string|bool
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
	 * Возвращает дату окончания моратория на займ, если такой мораторий есть
	 *
	 * @return bool|string
	 */
	public function getMoratoriumLoan()
	{
		$aClientInfo = $this->getClientInfo();
		$sMoratoriumTo = (isset($aClientInfo['moratoriums']['loan']))
			? $aClientInfo['moratoriums']['loan']
			: null;
		$sMoratoriumTo = $this->formatRusDate($sMoratoriumTo, false);

		return $sMoratoriumTo;
	}

	/**
	 * Сравнивает 2 даты и возвращает бОльшую
	 *
	 * @param string $sDate1
	 * @param string $sDate2
	 *
	 * @return string
	 */
	private function getMaxDateInFormat($sDate1, $sDate2)
	{
		$iDate1 = strtotime($sDate1);
		$iDate2 = strtotime($sDate2);

		$iMaxDate = ($iDate1 > $iDate2) ? $iDate1 : $iDate2;

		return $this->formatRusDate($iMaxDate, false);
	}

	/**
	 * Возвращает дату окончания моратория на займ (выбирая максимум между мораториями на подписку и скоринг),
	 * если такой мораторий есть
	 *
	 * @return bool|string
	 */
	public function getMoratoriumSubscription()
	{
		$aClientInfo = $this->getClientInfo();

		$sMoratoriumSub = (isset($aClientInfo['moratoriums']['subscription']))
			? $aClientInfo['moratoriums']['subscription']
			: null;
		$sMoratoriumScoring = (isset($aClientInfo['moratoriums']['scoring']))
			? $aClientInfo['moratoriums']['scoring']
			: null;

		$sMoratoriumTo = $this->getMaxDateInFormat($sMoratoriumSub, $sMoratoriumScoring);

		return $sMoratoriumTo;
	}

	/**
	 * Возвращает дату окончания моратория на займ (выбирая максимум между мораториями на подписку, скоринг и займ),
	 * если такой мораторий есть
	 *
	 * @return bool|string
	 */
	public function getMoratoriumSubscriptionLoan()
	{
		$aClientInfo = $this->getClientInfo();

		$sMoratoriumLoan = (isset($aClientInfo['moratoriums']['loan']))
			? $aClientInfo['moratoriums']['loan']
			: null;

		$sMoratoriumTo = $this->getMaxDateInFormat($this->getMoratoriumSubscription(), $sMoratoriumLoan);

		return $sMoratoriumTo;
	}

	/**
	 * @return bool
	 */
	public function getActiveLoanExpired()
	{
		$aClientInfo = $this->getClientInfo();
		$bExpired = (!empty($aClientInfo['active_loan']['expired']))
			? $aClientInfo['active_loan']['expired']
			: false;

		return $bExpired;
	}


	/**
	 * @return bool|string
	 */
	public function getActiveLoanExpiredTo()
	{
		$aClientInfo = $this->getClientInfo();
		$sExpiredTo = (!empty($aClientInfo['active_loan']['expired_to']))
			? $aClientInfo['active_loan']['expired_to']
			: false;
		$sExpiredTo = $this->formatRusDate($sExpiredTo, false);

		return $sExpiredTo;
	}

	/**
	 * Получение полного имени клиента
	 *
	 * @return string
	 */
	public function getClientFullName()
	{
		$aClientInfo = $this->getClientInfo();

		return (!empty($aClientInfo['client_data']['fullname'])) ? $aClientInfo['client_data']['fullname'] : '';
	}

	/**
	 * Есть ли у клиента задолженность по кредиту
	 *
	 * @return bool
	 */
	public function getIsDebt()
	{
		$aClientInfo = $this->getClientInfo();

		return (!empty($aClientInfo['client_data']['is_debt']));
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
	 * Получение массива с информацией о продуктах и каналах
	 *
	 * @return array
	 */
	public function getProductsAndChannels()
	{
		$aProducts = Yii::app()->cache->get('products');
		if (!empty($aProducts)) {
			return $aProducts;
		}
		$aProductsAndChannels = $this->getData('products');
		if ($aProductsAndChannels['code'] === self::ERROR_NONE) {
			//сохраняем в кэш с временем хранения 10 минут
			Yii::app()->cache->set('products', $aProductsAndChannels, 600);
			//кэш длительного хранения, на случай отключения API
			Yii::app()->cache->set('productsLongTime', $aProductsAndChannels);
		} else {
			//если вдруг при обращении к API вылезла ошибка, достаем данные из длительного кэша
			$aProducts = Yii::app()->cache->get('productsLongTime');
			if (isset($aProducts)) {
				return $aProducts;
			}
		}

		return $aProductsAndChannels;
	}

	/**
	 * Получение массива с информацией о продуктах
	 *
	 * @return array|bool
	 */
	public function getProducts()
	{
		$aProducts = $this->getProductsAndChannels();

		if (isset($aProducts['products'])) {

			return $aProducts['products'];
		}

		return false;
	}

	/**
	 * Получение массива с информацией о каналах
	 *
	 * @return array|bool
	 */
	public function getProductsChannels()
	{
		$aProducts = $this->getProductsAndChannels();

		if (isset($aProducts['channels'])) {

			return $aProducts['channels'];
		}

		return false;
	}

	/**
	 * Получение списка каналов, доступных клиенту
	 *
	 * @return array
	 */
	public function getClientProductsChannelsList()
	{
		$aProducts = $this->getProductsAndChannels();
		$aClientChannels = $this->getClientChannels();

		$aClientChannelsList = array();
		if (isset($aProducts['channels']) && isset($aClientChannels)) {
			foreach ($aClientChannels as $iChannel) {
				if (isset($aProducts['channels'][$iChannel])) {
					$aClientChannelsList[$iChannel] = $aProducts['channels'][$iChannel];
				}
			}
		}

		return $aClientChannelsList;
	}

	/**
	 * Получение списка продуктов и каналов для данного пользователя.
	 * Проверяет, какие каналы получения денег доступны клиенту, и возвращает только допустимые продукты и каналы
	 * Если нет ничего доступного, выводит соответствующую информацию
	 *
	 * @return array
	 */
	public function getClientProductsAndChannelsList()
	{
		//получаем список продуктов
		$aProducts = $this->getProducts();
		//получаем список каналов
		$aChannels = $this->getProductsChannels();
		//получаем список каналов, доступных клиенту
		$aClientChannels = $this->getClientChannels();
		$aProductsAndChannels = array();
		//проверяем, что получили массивы
		if (is_array($aProducts) && is_array($aChannels) && is_array($aClientChannels)) {

			//перебираем все продукты
			foreach ($aProducts as $aProduct) {
				//получаем из продукта каналы, по которым его можно получить
				$aProductChannels = (isset($aProduct['channels']) && is_array($aProduct['channels']))
					? $aProduct['channels']
					: array();
				//перебираем каналы, по которым можно получить продукт
				foreach ($aProductChannels as $iChannel) {
					//проверяем, что у канала есть описание
					//проверяем, что данный канал доступен пользователю
					if (isset($aChannels[$iChannel])
						&& in_array($iChannel, $aClientChannels)
					) {
						$aProductsAndChannels[($aProduct['id'] . '_' . $iChannel)] = $aProduct['name'] . ' ' . SiteParams::mb_lcfirst($aChannels[$iChannel]);
					}
				}
			}
		}

		return $aProductsAndChannels;
	}

	/**
	 * Получение списка продуктов и каналов для данного пользователя.
	 * Проверяет, какие каналы получения денег доступны клиенту, и возвращает только допустимые продукты и каналы
	 *
	 * @return array|bool
	 */

	public function getProductsList()
	{
		//получаем список продуктов
		$aProducts = $this->getProducts();
		//получаем список каналов
		$aChannels = $this->getProductsChannels();
		//получаем список каналов, доступных клиенту
		//проверяем, что получили массивы
		if (is_array($aProducts) && is_array($aChannels)) {
			$aProductsList = array();
			//перебираем все продукты
			foreach ($aProducts as $aProduct) {
				$aProductsList[$aProduct['id']] = $aProduct;

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
	 * Проверка возможности получения займа
	 *
	 * @return bool
	 */
	public function checkLoan()
	{
		if (!isset($this->bIsCanGetLoan)) {
			$this->requestAdminKreddyApi(self::API_ACTION_CHECK_LOAN);

			$this->bIsCanGetLoan = (!$this->getIsNotAllowed()
				&& !$this->getIsNeedSmsAuth()
				&& !$this->getIsError()
			);
		}

		return $this->bIsCanGetLoan;
	}

	/**
	 * Отправка СМС с кодом подтверждения займа
	 *
	 * @return bool
	 */
	public function sendSmsLoan()
	{
		//отправляем СМС с кодом
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_LOAN);

		if ($aResult['code'] === self::ERROR_NEED_SMS_CODE && $aResult['sms_status'] === self::SMS_SEND_OK) {
			$this->setLastSmsMessage($aResult['sms_message']);

			return true;
		} else {
			if (isset($aResult['sms_message'])) {
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setLastSmsMessage(self::ERROR_MESSAGE_UNKNOWN);
			}

			return false;
		}
	}

	/**
	 * Взять займ, подписанный СМС-кодом
	 *
	 * @param string $sSmsCode
	 *
	 * @param        $iChannelId
	 *
	 *
	 * @return bool
	 */
	public function doLoan($sSmsCode, $iChannelId)
	{

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_LOAN, array('sms_code' => $sSmsCode, 'channel_id' => $iChannelId));

		if ($aResult['code'] === self::ERROR_NONE && $aResult['sms_status'] === self::SMS_AUTH_OK) {
			$this->setLastSmsMessage($aResult['sms_message']);

			return true;
		} else {
			if (isset($aResult['sms_message'])) {
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setLastSmsMessage(self::ERROR_MESSAGE_UNKNOWN);
			}

			return false;
		}
	}


	/**
	 * Проверка возможности подписки
	 *
	 * @return bool
	 */
	public function checkSubscribe()
	{
		if (!isset($this->bIsCanSubscribe)) {
			$this->requestAdminKreddyApi(self::API_ACTION_CHECK_SUBSCRIBE);
			$this->bIsCanSubscribe = (!$this->getIsNotAllowed()
				&& !$this->getIsNeedSmsAuth());
		}

		return $this->bIsCanSubscribe;
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

		if ($aResult['code'] === self::ERROR_NEED_SMS_CODE && isset($aResult['sms_status']) && $aResult['sms_status'] === self::SMS_SEND_OK) {
			$this->setLastSmsMessage($aResult['sms_message']);

			return true;
		} else {
			if (isset($aResult['sms_message'])) {
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setLastSmsMessage(self::ERROR_MESSAGE_UNKNOWN);
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
	 * @param        $iChannelId
	 *
	 * @return bool
	 */
	public function doSubscribe($sSmsCode, $iProduct, $iChannelId)
	{

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_SUBSCRIBE,
			array('sms_code' => $sSmsCode, 'product_id' => $iProduct, 'channel_id' => $iChannelId));

		if ($aResult['code'] === self::ERROR_NONE && $aResult['sms_status'] === self::SMS_AUTH_OK) {
			if (isset($aResult['scoring_accepted'])) {
				$this->setScoringAccepted($aResult['scoring_accepted']);
			}
			$this->setLastSmsMessage($aResult['sms_message']);

			return true;
		} else {
			if (isset($aResult['sms_message'])) {
				$this->setScoringAccepted(null);
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setScoringAccepted(null);
				$this->setLastSmsMessage(self::ERROR_MESSAGE_UNKNOWN);
			}

			return false;
		}
	}

	/**
	 * Привязка пластиковой карты к аккаунту
	 *
	 * @param $sCardPan
	 * @param $sCardMonth
	 * @param $sCardYear
	 * @param $sCardCvc
	 *
	 * @return bool
	 */
	public function addClientCard($sCardPan, $sCardMonth, $sCardYear, $sCardCvc)
	{
		$aRequest = array(
			'card_pan'   => $sCardPan,
			'card_month' => $sCardMonth,
			'card_year'  => $sCardYear,
			'card_cvc'   => $sCardCvc
		);

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_ADD_CARD, $aRequest);

		$this->setLastMessage($aResult['message']);

		if ($aResult['code'] === self::ERROR_NONE) {
			return $aResult['card_order'];
		} else {
			return false;
		}
	}

	/**
	 * @param $sCardOrder
	 * @param $sCardVerifyAmount
	 *
	 * @return bool
	 */
	public function verifyClientCard($sCardOrder, $sCardVerifyAmount)
	{
		$aRequest = array(
			'card_order'         => $sCardOrder,
			'card_verify_amount' => $sCardVerifyAmount,
		);

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_VERIFY_CARD, $aRequest);

		$this->setLastMessage($aResult['message']);

		return ($aResult['code'] === self::ERROR_NONE);
	}

	/**
	 * Проверяет, может ли клиент добавить карту.
	 *
	 * @return bool
	 */
	public function checkCanAddCard()
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CHECK_CAN_ADD_CARD);
		if(!$this->getIsError()){
			return (!empty($aResult['card_can_verify']));
		}
	}

	/**
	 * Проверка, нужна ли видеоидентификация
	 *
	 * @return bool
	 */
	public function checkIsNeedIdentify()
	{
		$this->getData('check_identify');

		return (!$this->getIsError() && $this->getIsNeedIdentify());
	}

	/**
	 * @return array|bool
	 */

	public function getIdentify()
	{
		$aResult = $this->getData('identify');

		if (!$this->getIsError()) {
			unset($aResult['code']);
			unset($aResult['message']);

			return $aResult;
		}

		return false;
	}

	/**
	 * Сохранение выбранного продукта в сессию
	 *
	 * @param $sProduct
	 */
	public function setSubscribeSelectedProduct($sProduct)
	{
		Yii::app()->session['subscribeSelectedProduct'] = $sProduct;
	}

	/**
	 * Получение выбранного продукта из сессии
	 *
	 * @return string|bool
	 */
	public function getSubscribeSelectedProduct()
	{
		return (isset(Yii::app()->session['subscribeSelectedProduct']))
			? Yii::app()->session['subscribeSelectedProduct'] :
			false;
	}

	/**
	 * Сохранение в сессию выбранного канала получения продукта
	 *
	 * @param $iChannel
	 */
	public function setLoanSelectedChannel($iChannel)
	{
		Yii::app()->session['loanSelectedChannel'] = $iChannel;
	}

	/**
	 * Получение выбранного канала из сессии
	 *
	 * @return string|bool
	 */
	public function getLoanSelectedChannel()
	{
		return (isset(Yii::app()->session['loanSelectedChannel']))
			? Yii::app()->session['loanSelectedChannel'] :
			false;
	}

	/**
	 * Получение выбранного продукта из сессии
	 *
	 * @return string|bool
	 */
	public function getSubscribeSelectedProductId()
	{
		$aProduct = explode('_', Yii::app()->session['subscribeSelectedProduct']);

		if (count($aProduct) === 2) {
			$iProductId = $aProduct[0];

			return $iProductId;
		}

		return false;
	}

	/**
	 * Роутер запросов для получения данных
	 * Получает запросы на данные и перенаправляет на requestAdminKreddyApi() с нужным экшном
	 *
	 * @param $sType
	 *
	 * @return array
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
			case 'identify':
				$sAction = self::API_ACTION_GET_IDENTIFY;
				break;
			case 'check_identify':
				$sAction = self::API_ACTION_CHECK_IDENTIFY;
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
	 * @return array
	 */
	private function requestAdminKreddyApi($sAction, $aRequest = array())
	{
		$sApiUrl = (!Yii::app()->params['bApiTestModeIsOn']) ? $this->sApiUrl : $this->sTestApiUrl;
		$aData = array('code' => self::ERROR_AUTH, 'message' => self::ERROR_MESSAGE_UNKNOWN);

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

			if (is_array($aGetData)) {
				$aData['message'] = 'Запрос выполнен успешно.';
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

		//$this->setSessionToken(null);
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
	 * @return bool
	 */
	public function getIsNeedSmsAuth()
	{
		return ($this->getLastCode() === self::ERROR_NEED_SMS_AUTH);
	}

	/**
	 * Проверяем, требует ли последний запрошенный action API подтверждения одноразовым СМС-кодом
	 *
	 * @return bool
	 */
	public function getIsNeedSmsCode()
	{
		return ($this->getLastCode() === self::ERROR_NEED_SMS_CODE);
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
	 *
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
		if ($aResult['sms_status'] === self::SMS_AUTH_OK) {
			$this->setSmsAuthDone(true);

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
		return (!empty(Yii::app()->session['smsAuthDone']));
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
	 * @param      $sDate
	 * @param bool $bWithTime выводить ли время
	 *
	 * @return bool|string
	 */
	public function formatRusDate($sDate, $bWithTime = true)
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
	 * @param $bAccepted
	 */
	public function setScoringAccepted($bAccepted)
	{
		$this->bScoringAccepted = $bAccepted;
	}

	/**
	 * @return mixed
	 */
	public function getScoringAccepted()
	{
		return $this->bScoringAccepted;
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

	/**
	 * Проверка, вернул ли последний выполненный запрос сообщение "операция недоступна" - код 11
	 *
	 * @return bool
	 */
	public function getIsNotAllowed()
	{
		return ($this->getLastCode() === self::ERROR_NOT_ALLOWED);
	}

	/**
	 * Проверка, вернул ли последний выполненный запрос ошибку (коды 0, 9 и 10 - не являются кодами ошибок)
	 *
	 * @return bool
	 */
	public function getIsError()
	{
		return ($this->getLastCode() !== self::ERROR_NONE
			&& $this->getLastCode() !== self::ERROR_NEED_SMS_AUTH
			&& $this->getLastCode() !== self::ERROR_NEED_SMS_CODE
			&& $this->getLastCode() !== self::ERROR_NOT_ALLOWED
			&& $this->getLastCode() !== self::ERROR_NEED_IDENTIFY
		);
	}

	/**
	 * Проверка, требуется ли видеоидентификация
	 * @return bool
	 */
	public function getIsNeedIdentify()
	{
		return ($this->getLastCode() === self::ERROR_NEED_IDENTIFY);
	}


	/**
	 * @return string
	 */
	public function getDoSubscribeMessage()
	{
		$bScoringAccepted = $this->getScoringAccepted();
		if (!empty($bScoringAccepted)) {
			$sMessage = strtr(self::C_DO_SUBSCRIBE_MSG_SCORING_ACCEPTED, $this->formatStatusMessage());

			return $sMessage;
		} else {
			return self::C_DO_SUBSCRIBE_MSG;
		}
	}

	/**
	 * @return string
	 */
	public function getDoLoanMessage()
	{
		$sMessage = strtr(self::C_DO_LOAN_MSG, $this->formatStatusMessage());

		return $sMessage;
	}

	/**
	 * @return string
	 */
	public function getNoAvailableProductsMessage()
	{
		return self::C_NO_AVAILABLE_PRODUCTS;
	}

	/**
	 * @param $bSmsAuthDone
	 */
	public function setSmsAuthDone($bSmsAuthDone)
	{
		Yii::app()->session['smsAuthDone'] = $bSmsAuthDone;
	}

	/**
	 * @return string
	 */
	public function getSubscriptionNotAvailableMessage()
	{
		$sMessage = strtr(self::C_SUBSCRIPTION_NOT_AVAILABLE, $this->formatStatusMessage());

		return $sMessage;
	}

	/**
	 * @return string
	 */
	public function getLoanNotAvailableMessage()
	{
		$sMessage = strtr(self::C_LOAN_NOT_AVAILABLE, $this->formatStatusMessage());

		return $sMessage;
	}

	/**
	 * @param $sCardOrder
	 */
	public function setCardOrder($sCardOrder)
	{
		//TODO сделать длительное хранение (не менее 30 минут), можно в кукисе
		Yii::app()->session['sCardOrder'] = $sCardOrder;
	}

	/**
	 * @return string
	 */
	public function getCardOrder()
	{
		return Yii::app()->session['sCardOrder'];
	}
}
