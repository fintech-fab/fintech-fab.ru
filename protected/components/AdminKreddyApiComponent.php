<?php

/**
 * Class AdminKreddyApiComponent
 *
 */
class AdminKreddyApiComponent
{

	/**
	 * Статусы API
	 */

	const C_CLIENT_NEW = 'client_new';
	const C_CLIENT_ACTIVE = 'client_active';
	const C_CLIENT_FAST_REG = 'client_fast_reg';

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
	const C_SUBSCRIPTION_AWAITING_CONFIRMATION = 'subscription_awaiting_confirmation';

	const C_LOAN_AVAILABLE = 'loan_available';
	const C_LOAN_CREATED = 'loan_created';
	const C_LOAN_TRANSFER = 'loan_transfer';
	const C_LOAN_ACTIVE = 'loan_active';
	const C_LOAN_DEBT = 'loan_debt';
	const C_LOAN_PAID = 'loan_paid';

	const C_STATUS_ERROR = 'Ошибка!';

	const C_SUBSCRIPTION_NOT_AVAILABLE = "Извините, подключение Пакета недоступно. {account_url_start}Посмотреть информацию о КРЕДДИтной линии{account_url_end}";
	const C_SUBSCRIPTION_NOT_AVAILABLE_IVANOVO = "Извините, оформление займа недоступно. {account_url_start}Посмотреть информацию о статусе займа{account_url_end}";
	const C_LOAN_NOT_AVAILABLE = "Извините, оформление займа недоступно. Попробуйте повторить запрос на перевод через 1 минуту. {account_url_start}Посмотреть информацию о КРЕДДИтной линии{account_url_end}";

	const C_DO_SUBSCRIBE_MSG_SCORING_ACCEPTED = 'Ваша заявка одобрена. Для получения займа необходимо оплатить подключение в размере {do_sub_pay_sum} рублей любым удобным способом. {account_url_start}Посмотреть информацию о КРЕДДИтной линии{account_url_end}';
	const C_DO_SUBSCRIBE_MSG_SCORING_ACCEPTED_POSTPAID = 'Заявка одобрена. Для получения денег {do_loan_url_start}отправьте запрос на перевод.{do_loan_url_end} {account_url_start}Посмотреть информацию о КРЕДДИтной линии{account_url_end}';
	const C_DO_SUBSCRIBE_MSG_SCORING_CANCELED = 'Ваша заявка отклонена';
	const C_DO_SUBSCRIBE_MSG = 'Ваша заявка принята. Ожидайте решения.';
	const C_DO_LOAN_MSG = 'Ваша заявка оформлена. Займ поступит {channel_name} {loan_transfer_time}';

	const C_SESSION_EXPIRED = 'Время Вашей сессии истекло. Просим Вас снова зайти в личный кабинет.';
	const C_SESSION_TIME_UNTIL_EXPIRED = 'Время сессии: ';

	const C_CARD_NOT_AVAILABLE = 'Вы выбрали получение денег на не доступный Вам канал получения.
			 Пройдите, пожалуйста, процедеру привязки банковской карты, для получения займа на неё,
			  и затем вернитесь к получению займа.';

	private $aSubscriptionActiveStates = array(
		self::C_SUBSCRIPTION_ACTIVE,
		self::C_LOAN_AVAILABLE,
		self::C_LOAN_CREATED,
		self::C_LOAN_TRANSFER,
		self::C_LOAN_DEBT,
		self::C_LOAN_PAID,
		self::C_CLIENT_MORATORIUM_LOAN,
	);

	private $aAvailableStatuses = array(

		self::C_CLIENT_MORATORIUM_LOAN             => 'Временно недоступно получение новых займов',
		self::C_CLIENT_MORATORIUM_SCORING          => 'Заявка отклонена',
		self::C_CLIENT_MORATORIUM_SUBSCRIPTION     => 'Временно недоступно подключение новых Пакетов',

		self::C_SUBSCRIPTION_ACTIVE                => 'Подключен к Пакету',
		self::C_SUBSCRIPTION_AVAILABLE             => 'Доступно подключение к Пакету',
		self::C_SUBSCRIPTION_CANCEL                => 'Срок оплаты подключения истек',
		self::C_SUBSCRIPTION_PAID                  => 'Займ доступен',
		self::C_SUBSCRIPTION_PAYMENT               => 'Оплатите подключение в размере {sub_pay_sum} рублей любым удобным способом. {payments_url_start}Подробнее{payments_url_end}',

		self::C_SCORING_PROGRESS                   => 'Заявка в обработке. {account_url_start}Обновить статус{account_url_end}', //+

		self::C_SCORING_ACCEPT                     => 'Ваша заявка одобрена, ожидайте выдачи займа',
		self::C_SCORING_CANCEL                     => 'Заявка отклонена',

		self::C_SUBSCRIPTION_AWAITING_CONFIRMATION => 'Ожидание подтверждения запроса на займ',

		self::C_LOAN_DEBT                          => 'Задолженность по займу',
		self::C_LOAN_ACTIVE                        => 'Займ перечислен', //+
		self::C_LOAN_TRANSFER                      => 'Займ перечислен', //+
		self::C_LOAN_AVAILABLE                     => 'Займ доступен',
		self::C_LOAN_CREATED                       => 'Займ перечислен', //+
		self::C_LOAN_PAID                          => 'Займ оплачен',

		self::C_CLIENT_ACTIVE                      => 'Доступно подключение Пакета', //+
		self::C_CLIENT_NEW                         => 'Выберите Пакет займов',
		self::C_CLIENT_FAST_REG                    => 'Требуется заполнить анкету',
	);

	private $aAvailableStatusesIvanovo = array(

		self::C_CLIENT_MORATORIUM_LOAN         => 'Временно недоступно получение новых займов',
		self::C_CLIENT_MORATORIUM_SCORING      => 'Заявка отклонена',
		self::C_CLIENT_MORATORIUM_SUBSCRIPTION => 'Временно недоступно получение новых займов',

		self::C_SUBSCRIPTION_ACTIVE            => 'Займ оформлен',
		self::C_SUBSCRIPTION_AVAILABLE         => 'Доступно оформление займа',
		self::C_SUBSCRIPTION_CANCEL            => '', //для Иваново не должно использоваться
		self::C_SUBSCRIPTION_PAID              => 'Займ доступен',

		self::C_SUBSCRIPTION_PAYMENT           => '', //для Иваново не должно использоваться

		self::C_SCORING_PROGRESS               => 'Заявка в обработке. {account_url_start}Обновить статус{account_url_end}', //+

		self::C_SCORING_ACCEPT                 => 'Ваша заявка одобрена, ожидайте выдачи займа',
		self::C_SCORING_CANCEL                 => 'Заявка отклонена',

		self::C_LOAN_DEBT                      => 'Задолженность по займу',
		self::C_LOAN_ACTIVE                    => 'Займ перечислен',
		self::C_LOAN_TRANSFER                  => 'Займ перечислен',
		self::C_LOAN_AVAILABLE                 => 'Займ доступен',
		self::C_LOAN_CREATED                   => 'Займ перечислен',
		self::C_LOAN_PAID                      => 'Займ оплачен',

		self::C_CLIENT_ACTIVE                  => 'Доступно оформление займа',
		self::C_CLIENT_NEW                     => 'Выберите займ',
	);

	const C_MOBILE = 'mobile';
	const C_CARD = 'card';

	const PRODUCT_TYPE_KREDDY = 1;
	const PRODUCT_TYPE_IVANOVO = 2;
	const PRODUCT_TYPE_KREDDYLINE = 3;
	const PRODUCT_TYPE_KREDDY_LINE_POSTPAID = 4;

	const C_KREDDY_LINE_POSTPAID_PAY_RULES = 'до окончания действия КРЕДДИтной линии';
	const C_KREDDY_LINE_PAY_RULES = 'перед началом использования КРЕДДИтной линии';

	private static $aChannels = array(
		self::C_MOBILE,
		self::C_CARD,
	);

	/**
	 * @var array массив каналов с рег.выражениями - на карту и на мобильный
	 */
	private static $aChannelsRegexps = array(
		self::C_MOBILE => '/мобил/',
		self::C_CARD   => '/карт/',
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
	const ERROR_VALIDATION = 24; //ошибка валидации
	const ERROR_PHONE_ERROR = 15; //ошибка номера телефона (такой номер уже есть)
	const ERROR_NEED_IDENTIFY = 16; //требуется идентификация
	const ERROR_NEED_PASSPORT_DATA = 17; //требуется ввести паспортные данные
	const ERROR_NEED_REDIRECT = 18; //требуется редирект на основной домен сайта
	const ERROR_NEED_CARD = 202; //требуется привязать банковскую карту

	/**
	 * Требуется подождать и повторить запрос
	 */
	const ERROR_NEED_WAIT = 203;

	/**
	 * Требуется пройти процесс 3DS авторизации
	 */
	const ERROR_NEED_3DS_PROCESS = 204;

	/**
	 * Ошибка в процессе верификации карты через 3DS
	 */
	const ERROR_VERIFY_3DS = 205;

	const SMS_AUTH_OK = 0; //СМС-авторизация успешна (СМС-код верный)
	const SMS_SEND_OK = 1; //СМС с кодом/паролем отправлена
	const SMS_CODE_ERROR = 2; //неверный СМС-код
	const SMS_BLOCKED = 3; //отправка СМС заблокирована
	const SMS_CODE_TRIES_EXCEED = 4; //попытки ввода СМС-кода исчерпаны

	const C_MAX_SMS_CODE_TRIES = 3;
	const C_MAX_PASS_SMS_CODE_TRIES = 5;

	const TOKEN_MINUTES_LIVE = 10; // токен живёт 10 минут

	const API_ACTION_CHECK_IDENTIFY = 'video/heldIdentification';
	const API_ACTION_GET_IDENTIFY = 'video/getIdentify';
	const API_ACTION_CREATE_CLIENT = 'siteClient/signup';
	const API_ACTION_CREATE_FAST_REG_CLIENT = 'siteClient/signupFast';
	const API_ACTION_UPDATE_FAST_REG_CLIENT = 'siteClient/updateFastReg';
	const API_ACTION_CHECK_SUBSCRIBE = 'siteClient/checkSubscribe';
	const API_ACTION_SUBSCRIBE = 'siteClient/doSubscribe';
	const API_ACTION_CHECK_LOAN = 'siteClient/checkLoan';
	const API_ACTION_LOAN = 'siteClient/doLoan';
	const API_ACTION_TOKEN_UPDATE = 'siteToken/update';
	const API_ACTION_TOKEN_CREATE = 'siteToken/create';
	const API_ACTION_GET_INFO = 'siteClient/getInfo';
	const API_ACTION_GET_FULL_CLIENT_DATA = 'siteClient/getFullClientData';
	const API_ACTION_GET_HISTORY = 'siteClient/getPaymentHistory';
	const API_ACTION_RESET_PASSWORD = 'siteClient/resetPassword';
	const API_ACTION_GET_PRODUCTS = 'siteClient/getProducts';
	const API_ACTION_CHANGE_PASSPORT = 'siteClient/doChangePassport';
	const API_ACTION_CHANGE_SECRET_QUESTION = 'siteClient/doChangeSecretQuestion';
	const API_ACTION_CHANGE_SMS_AUTH_SETTING = 'siteClient/doChangeSmsAuthSetting';
	const API_ACTION_GET_LOAN = 'siteClient/getLoan';
	const API_ACTION_CHANGE_NUMERIC_CODE = 'siteClient/doChangeNumericCode';
	const API_ACTION_CHANGE_PASSWORD = 'siteClient/doChangePassword';
	const API_ACTION_UPLOAD_DOCUMENT = 'siteClient/uploadDocument';
	const API_ACTION_SET_IDENTIFICATION_FINISHED = 'siteClient/setFinishedVideoId';
	const API_ACTION_CANCEL_REQUEST = 'siteClient/doCancelRequest';

	const API_ACTION_REQ_SMS_CODE = 'siteClient/authBySms';
	const API_ACTION_CHECK_SMS_CODE = 'siteClient/authBySms';

	const API_ACTION_ADD_CARD = 'siteClientCard/addClientCard';
	const API_ACTION_VERIFY_CARD = 'siteClientCard/verifyClientCard';
	const API_ACTION_CHECK_CAN_VERIFY_CARD = 'siteClientCard/checkClientCanVerifyCard';

	const API_ACTION_EMAIL_INFO = 'siteEmail/emailLinkHandler';

	const ERROR_MESSAGE_UNKNOWN = 'Произошла неизвестная ошибка. Проверьте правильность заполнения данных.';
	const C_NO_AVAILABLE_PRODUCTS = "Доступные способы перечисления займа отсутствуют.";

	const C_CARD_MSG_REQUIREMENTS = 'Убедитесь, что банковская карта зарегистрирована на Ваше имя, не является предоплаченной, активна (не заблокирована) и доступна для перечисления денег.';
	const C_CARD_WARNING_NO_CARD = 'ВНИМАНИЕ! У Вас нет привязанной банковской карты. Для получения займов на банковскую карту пройдите процедуру привязки карты.';
	const C_CARD_WARNING_EXPIRED = 'ВНИМАНИЕ! У ранее привязанной банковской карты вышел срок действия привязки и необходимо привязать ту же самую или новую карту.';
	const C_CARD_SUCCESSFULLY_VERIFIED = "Карта успешно привязана!";
	const C_CARD_ADD_TRIES_EXCEED = "Сервис временно недоступен. Попробуйте позже.";
	const C_CARD_VERIFY_EXPIRED = "Время проверки карты истекло. Для повторения процедуры привязки введите данные карты.";
	const C_CARD_VERIFY_ERROR_3DS = "При авторизации карты произошла ошибка. Возможно, неверно введены данные карты или код авторизации. Попробуйте повторить процедуру привязки карты.";
	const C_CARD_AGREEMENT = "Срок зачисления средств зависит от банка-эмитента Вашей карты. В некоторых случаях срок зачисления может составлять несколько дней. Обращаем Ваше внимание, МФО ООО «Финансовые Решения» оставляет за собой право увеличить срок возврата займа, указанный в Приложение №1 к Договору (Оферте), не более, чем на 3 дня.";

	const C_REQUEST_CANCEL_SUCCESS = 'Ваше подключение успешно отменено. Будем ждать новой заявки!';
	const C_REQUEST_CANCEL_ERROR = 'Ошибка! Не удалось отменить подключение.';

	/**
	 * Переменные для тестирования API идентификации, требуются для выполнения тестов.
	 * логин и пароль должны соответствовать заданным в IdentifyModuleTest
	 *
	 */
	private $testLogin = '9631321654';
	private $testPassword = 'Aa123456';
	private $testToken = 'abcdsdg*98ughjg23t8742yusdjf';

	const C_NEED_PASSPORT_DATA = "ВНИМАНИЕ! Вы прошли идентификацию, но не заполнили форму подтверждения документов. Для продолжения {passport_url_start}заполните, пожалуйста, форму{passport_url_end}.";

	private $token;
	private $aClientInfo; //массив с данными клиента
	private $iLastCode; //code из последнего выполненного запроса
	private $sLastMessage = ''; //message из последнего выполненного запроса
	private $sLastSmsMessage = ''; //sms_message из последнего выполненного запроса
	private $bIsCanSubscribe = null; //клиент может оформить подписку
	private $bIsCanGetLoan = null; //клиент может взять заём
	private $iScoringResult = null;
	private $aCheckIdentify;
	private $bIsNeedCard;

	public $sApiUrl = '';
	public $sTestApiUrl = '';
	private $iSmsCode;
	private $oCardVerifyStatus;

	/**
	 * Заменяет в сообщениях Клиенту шаблоны на вычисляемые значения
	 * TODO найти все использования не для статусов, сделать для них отдельный форматтер
	 *
	 * @return array
	 */
	public function formatStatusMessage()
	{
		// берём ID продукта из сессии, если есть
		/*$iProductId = $this->getSubscribeSelectedProductId();
		if (!$iProductId) {
			// если нет в сессии - из ответа API
			$iProductId = $this->getSubscriptionProductId();
		}*/

		Yii::app()->adminKreddyApi->getSubscribeProductCost();

		return array(
			'{sub_pay_sum}'        => $this->getSubscriptionCost(), // стоимость подключения текущего пакета

			'{do_sub_pay_sum}'     => $this->getSubscribeProductCost(), //стоимость оформляемого в данный момент пакета

			'{channel_name}'       => SiteParams::mb_lcfirst($this->getChannelNameForSubscriptionLoan($this->getLoanSelectedChannel())), // название канала

			'{account_url_start}'  => CHtml::openTag("a", array(
					"href" => Yii::app()->createUrl("/account")
				)), // ссылка на инфо о пакете
			'{account_url_end}'    => CHtml::closeTag("a"), // /ссылка на инфо о пакете

			'{do_loan_url_start}'  => CHtml::openTag("a", array(
					"href" => Yii::app()->createUrl("/account/loan")
				)), // ссылка на получение займа
			'{do_loan_url_end}'    => CHtml::closeTag("a"), // /ссылка на получение займа

			'{payments_url_start}' => CHtml::openTag("a", array(
					"href" => Yii::app()->createUrl("pages/view/payments"), "target" => "_blank"
				)), // ссылка на инфо о возможных вариантах оплаты
			'{payments_url_end}'   => CHtml::closeTag("a"), // /ссылка на инфо о возможных вариантах оплаты

			'{contacts_url_start}' => CHtml::openTag("a",
					array("href" => "#fl-contacts", "data-target" => "#fl-contacts", "data-toggle" => "modal")), // ссылка на инфо об отделении
			'{contacts_url_end}'   => CHtml::closeTag("a"), // /ссылка на инфо об отделении
			'{loan_transfer_time}' => $this->getLoanChannelSpeed($this->getLoanSelectedChannel())
		);
	}

	/**
	 * Форматирование сообщения по шаблону
	 *
	 * @param $sMessage
	 *
	 * @return string
	 */
	public function formatMessage($sMessage)
	{
		$aReplace = array(
			'{passport_url_start}' => CHtml::openTag("a", array(
					"href" => Yii::app()->createUrl("/account/changePassport"),
				)), // ссылка на форму изменения паспорта
			'{passport_url_end}'   => CHtml::closeTag("a")
		);

		return strtr($sMessage, $aReplace);
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
	 * @return bool
	 */
	public function isLoggedIn()
	{
		return isset($this->token);
	}

	/**
	 * Авторизация в API по логину и паролю, получаем токен и сохраняем в сессию
	 *
	 * @param $sPhone
	 * @param $sPassword
	 *
	 * @return bool
	 */
	public function getAuth($sPhone, $sPassword)
	{

		$aRequest = array(
			'login'       => $sPhone,
			'password'    => $sPassword,
			'ip'          => Yii::app()->request->getUserHostAddress(),
			'site_region' => Yii::app()->clientForm->getSiteRegionId(),
		);

		$aTokenData = $this->requestAdminKreddyApi(self::API_ACTION_TOKEN_CREATE, $aRequest);

		if ($aTokenData['code'] === self::ERROR_NONE) {
			$this->setSessionToken($aTokenData['token']);
			$this->token = $aTokenData['token'];

			//перезапросим инфо клиента
			$this->getClientInfo(true);

			// Если смс-авторизация не требутеся
			if (!$this->getIsNeedSmsAuth()) {
				$this->setSmsAuthDone(true);
			} else {
				$this->setSmsAuthDone(false);
			}

			if ($this->checkIsNeedPassportData()) {
				Yii::app()->user->setFlash('warning', $this->formatMessage(self::C_NEED_PASSPORT_DATA));
			}

			return true;
		}

		return false;
	}

	/**
	 * * Метод для получения авторизации и токена для API идентификации
	 *
	 * @param      $sPhone
	 * @param      $sPassword
	 *
	 * @param bool $bTest
	 *
	 * @return null|string
	 */
	public function getIdentifyApiAuth($sPhone, $sPassword, $bTest = false)
	{
		$aRequest = array('login' => $sPhone, 'password' => $sPassword);

		if ($bTest && $this->testLogin == $sPhone && $this->testPassword == $sPassword) {
			return $this->testToken;
		}

		//проверяем, не исчерпаны ли попытки авторизации
		if (!AntiBotComponent::isCanLoginRequest()) {
			return null;
		}

		//добавляем в лог запись о еще одном запросе на авторизацию
		AntiBotComponent::addLoginRequest();

		$aTokenData = $this->requestAdminKreddyApi(self::API_ACTION_TOKEN_CREATE, $aRequest);
		if ($aTokenData['code'] === self::ERROR_NONE) {

			return $aTokenData['token'];
		}

		return null;
	}

	/**
	 * @param      $oCurlFile
	 * @param      $sDocumentType
	 * @param      $sToken
	 *
	 * @param bool $bTest
	 *
	 * @return bool
	 */
	public function uploadDocument($oCurlFile, $sDocumentType, $sToken, $bTest = false)
	{
		$aRequest = array(
			'token' => $sToken, 'type' => $sDocumentType,
			'files' => $oCurlFile,
		);

		if ($bTest && $sToken == $this->testToken) {
			return true;
		}

		$aResponse = $this->requestAdminKreddyApi(self::API_ACTION_UPLOAD_DOCUMENT, $aRequest);
		if ($aResponse['code'] === self::ERROR_NONE) {

			return true;
		}

		return false;
	}

	/**
	 * @param      $sToken
	 *
	 * @param bool $bTest
	 *
	 * @return bool
	 */
	public function setFinishedVideoId($sToken, $bTest = false)
	{
		if ($bTest && $sToken == $this->testToken) {
			return true;
		}

		$aRequest = array(
			'token' => $sToken,
		);

		$aResponse = $this->requestAdminKreddyApi(self::API_ACTION_SET_IDENTIFICATION_FINISHED, $aRequest);
		if ($aResponse['code'] === self::ERROR_NONE) {

			return true;
		}

		return false;
	}

	/**
	 * @param $aClientData
	 *
	 * @return bool
	 */
	public function createClient($aClientData)
	{
		$aRequiredFields = array(
			'tracking_id'         => null,
			'ip'                  => null,

			'last_name'           => null,
			'first_name'          => null,
			'third_name'          => null,
			'sex'                 => null,
			'prev_last_name'      => null,
			'birthday'            => null,
			'phone'               => null,
			'email'               => null,

			'passport_series'     => null,
			'passport_number'     => null,
			'passport_date'       => null,
			'passport_code'       => null,
			'passport_issued'     => null,

			'document'            => null,
			'document_number'     => null,

			'relatives_one_fio'   => null,
			'relatives_one_phone' => null,

			'address_reg_region'  => null,
			'address_reg_city'    => null,
			'address_reg_address' => null,

			'address_reg_as_res'  => null,

			'address_res_region'  => null,
			'address_res_city'    => null,
			'address_res_address' => null,

			'numeric_code'        => null,

			'secret_question'     => null,
			'secret_answer'       => null,

			'status'              => null,
			'loan_purpose'        => null,
			'birthplace'          => null,
			'password'            => null,
		);

		$sDateFormatInBase = "Y-m-d";
		$aClientData['birthday'] = date($sDateFormatInBase, strtotime($aClientData['birthday']));
		$aClientData['passport_date'] = date($sDateFormatInBase, strtotime($aClientData['passport_date']));

		$aClientData = array_intersect_key($aClientData, $aRequiredFields);

		$aRequest = array('clientData' => CJSON::encode($aClientData));
		$aTokenData = $this->requestAdminKreddyApi(self::API_ACTION_CREATE_CLIENT, $aRequest);

		if (!self::getIsError() && !self::getIsPhoneError()) {
			$this->setSessionToken($aTokenData['token']);
			$this->token = $aTokenData['token'];
			$this->setSmsAuthDone(true);

			return true;
		}

		return false;
	}

	/**
	 * @param $aClientData
	 *
	 * @return bool
	 */
	public function createFastRegClient($aClientData)
	{
		//список полей, которые требуется передать при быстрой регистрации
		$aRequiredFields = array(
			'first_name'  => null,
			'last_name'   => null,
			'third_name'  => null,
			'email'       => null,
			'phone'       => null,
			'tracking_id' => null,
			'ip'          => null,
			'site_region' => null,
		);


		//получаем массив, соджержащий только заданные поля
		$aClientData = array_intersect_key($aClientData, $aRequiredFields);

		$aRequest = array('clientData' => CJSON::encode($aClientData));
		$aTokenData = $this->requestAdminKreddyApi(self::API_ACTION_CREATE_FAST_REG_CLIENT, $aRequest);

		if (!self::getIsError() && !self::getIsPhoneError()) {
			$this->setSessionToken($aTokenData['token']);
			$this->token = $aTokenData['token'];
			$this->setSmsAuthDone(true);

			return true;
		}

		return false;
	}

	/**
	 * @param $aClientData
	 *
	 * @return bool
	 */
	public function updateFastRegClient($aClientData)
	{
		//список полей, которые требуется передать при обновлении анкеты быстрой регистрации
		$aRequiredFields = array(
			'last_name'           => null,
			'first_name'          => null,
			'third_name'          => null,
			'sex'                 => null,
			'prev_last_name'      => null,
			'birthday'            => null,

			'passport_series'     => null,
			'passport_number'     => null,
			'passport_date'       => null,
			'passport_code'       => null,
			'passport_issued'     => null,

			'document'            => null,
			'document_number'     => null,

			'relatives_one_fio'   => null,
			'relatives_one_phone' => null,

			'address_reg_region'  => null,
			'address_reg_city'    => null,
			'address_reg_address' => null,

			'address_res_region'  => null,
			'address_res_city'    => null,
			'address_res_address' => null,

			'numeric_code'        => null,

			'secret_question'     => null,
			'secret_answer'       => null,

			'status'              => null,

			'loan_purpose'        => null,
			'birthplace'          => null,
		);

		//получаем массив, соджержащий только заданные поля
		$aClientData = array_intersect_key($aClientData, $aRequiredFields);

		$sDateFormatInBase = "Y-m-d";
		$aClientData['birthday'] = date($sDateFormatInBase, strtotime($aClientData['birthday']));
		$aClientData['passport_date'] = date($sDateFormatInBase, strtotime($aClientData['passport_date']));

		$aRequest = array('clientData' => CJSON::encode($aClientData));
		$this->requestAdminKreddyApi(self::API_ACTION_UPDATE_FAST_REG_CLIENT, $aRequest);

		if (!self::getIsError()) {

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
		}

		if (($aTokenData['code'] == self::ERROR_TOKEN_EXPIRE)) {
			$this->setUserSessionExpired();
		}

		$this->setSessionToken(null);
		$this->token = null;

		return false;
	}

	/**
	 * Метод для обновления токена для API идентификации
	 *
	 * @param      $sToken
	 *
	 * @param bool $bTest
	 *
	 * @return null
	 */
	public function updateIdentifyApiToken($sToken, $bTest = false)
	{
		$aRequest = array('token' => $sToken);

		if ($bTest && $sToken == $this->testToken) {
			return $this->testToken;
		}

		$aTokenData = $this->requestAdminKreddyApi(self::API_ACTION_TOKEN_UPDATE, $aRequest);

		if ($aTokenData['code'] == self::ERROR_NONE) {
			return $aTokenData['token'];
		}

		return null;
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
	 * @param array $aData
	 * @param bool  $bResend
	 *
	 * @return bool
	 */
	public function resetPasswordSendSms(array $aData, $bResend = false)
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_RESET_PASSWORD, $aData + array('sms_resend' => (int)$bResend));
		//если результат успешный
		if ($aResult['code'] === self::ERROR_NEED_SMS_CODE && $aResult['sms_status'] === self::SMS_SEND_OK) {
			//ставим флаг "смс отправлено" и сохраняем время отправки в сесссию
			Yii::app()->adminKreddyApi->setResetPassSmsCodeSentAndTime();
			//сохраняем телефон в сессию
			Yii::app()->adminKreddyApi->setResetPassData($aData);
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
	 * @param array $aData
	 *
	 * @return string|bool
	 */
	public function resetPasswordCheckSms(array $aData)
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_RESET_PASSWORD, $aData);

		if (isset($aResult['sms_status']) && $aResult['sms_status'] === self::SMS_AUTH_OK) {
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
	 * @param bool $bForce //сделать force-запрос к API без использования кэша
	 *
	 * @return array|bool
	 */
	public function getClientInfo($bForce = false)
	{
		//если данные уже были сохранены - возвращаем их без повторного запроса
		if (isset($this->aClientInfo) && !$bForce) {
			return $this->aClientInfo;
		}

		//TODO сравнить с текущей выдачей API и дополнить пустые массивы новыми ключами
		$aData = array(
			'code'                 => self::ERROR_AUTH,
			'client_data'          => array(
				'is_debt'               => false,
				'fullname'              => '',
				'client_new'            => false,
				'sms_auth_enabled'      => false,
				'is_possible_take_loan' => false,
			),
			'status'               => array(
				'name' => false,
			),
			'loan_request'         => false,
			'first_identification' => false,
			'active_loan'          => array(
				'channel_id'               => false,
				'balance'                  => 0,
				'loan_balance'             => 0,
				'subscription_balance'     => 0,
				'fine_and_penalty_balance' => 0,
				'expired'                  => false,
				'expired_to'               => false
			),
			'subscription_request' => array(
				'name'       => false,
				'can_cancel' => false,
				'type'       => 0
			),
			'subscription'         => array(
				'product'         => false,
				'product_id'      => false,
				'activity_to'     => false,
				'channel_id'      => false,
				'available_loans' => 0,
				'balance'         => 0,
				'product_info'    => array(
					'channels'      => array(),
					'loan_amount'   => false,
					'loan_lifetime' => false,
					'type'          => false,
				),
			),
			'moratoriums'          => array(
				'loan'         => false,
				'subscription' => false,
				'scoring'      => false,
			),
			'channels'             => array(),
			'slow_channels'        => array(),
			'bank_card_exists'     => false,
			'bank_card_expired'    => false,
			'bank_card_pan'        => false,
		);
		$this->token = $this->getSessionToken();
		if (!empty($this->token)) {
			//запрос данных по токену
			$aGetData = $this->getData('info');

			if (is_array($aGetData)) {
				$aData = CMap::mergeArray($aData, $aGetData);
			}
		}

		$this->processNewClientInfo($aData);

		return $aData;
	}

	/**
	 * После получения новой информации о клиенте следует обработать информацию
	 * и соответствующим образом отреагировать на нее
	 *
	 * @param $aData
	 */
	protected function processNewClientInfo($aData)
	{
		//сохраняем полученные данные для последующих запросов
		$this->aClientInfo = $aData;

		//запрашиваем, ушел ли клиент на идентификацию
		$bClientOnIdentify = $this->getClientOnIdentify();
		//если клиент ушел на идентификацию
		//проверяем, требуется ли заново ввести паспортные данные
		if ($bClientOnIdentify && $this->checkIsNeedPassportData()) {
			//выводим сообщение о необходимости повторно ввести паспортные данные
			Yii::app()->user->setFlash('warning', $this->formatMessage(self::C_NEED_PASSPORT_DATA));
		}

		//если не авторизован, то незачем ставить warning'и, а то они потом вылезут после авторизации
		if (!Yii::app()->adminKreddyApi->getIsAuth()) {
			return;
		}

		//если нет привязанной карты и не установлен другой warning, то уведомляем о необходимости привязки карты
		if (Yii::app()->user->hasFlash('warning')) {
			return;
		}

		if ($aData['bank_card_expired'] === true) {
			Yii::app()->user->setFlash('warning', self::C_CARD_WARNING_EXPIRED);
		} elseif ($aData['bank_card_exists'] === false) {
			Yii::app()->user->setFlash('warning', self::C_CARD_WARNING_NO_CARD);
		}

		return;
	}

	/**
	 * @return bool
	 */
	public function getIsNewClient()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['client_data']['client_new'];
	}

	public function getIsCanCancelRequest()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['subscription_request']['can_cancel'];
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

		return $aClientInfo['channels'];
	}

	/**
	 * @param $iChannelId
	 *
	 * @return bool
	 */
	public function getIsSlowChannel($iChannelId)
	{
		$aClientInfo = $this->getClientInfo();

		return in_array($iChannelId, $aClientInfo['slow_channels']);
	}

	/**
	 * @param $iChannelId
	 *
	 * @return string
	 */
	public function getChannelSpeed($iChannelId)
	{
		return ($this->getIsSlowChannel($iChannelId)) ? "до 3 дней" : "несколько минут";
	}

	/**
	 * То же, что и выше, для сообщения об успешном получении займа
	 *
	 * @param $iChannelId
	 *
	 * @return string
	 */
	public function getLoanChannelSpeed($iChannelId)
	{
		return ($this->getIsSlowChannel($iChannelId)) ? "в течение 3 дней." : "в течение нескольких минут.";
	}

	/**
	 * Получаем массив с каналами, доступными клиенту по данной подписке
	 * array('kreddy','mobile')
	 *
	 * @return array
	 */
	public function getClientSubscriptionChannels()
	{
		$aClientInfo = $this->getClientInfo();

		//находим пересечение массивов, т.е. каналы, которые доступны пользователю, и при этом доступные для текущей подписки
		$aChannels = array_intersect($aClientInfo['subscription']['product_info']['channels'], $aClientInfo['channels']);

		return $aChannels;
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
	 * @param $iChannel
	 *
	 * @return bool|mixed|string
	 */
	public function getChannelNameForSubscriptionLoan($iChannel)
	{
		$sChannelName = $this->getChannelNameById($iChannel);

		$sChannelName = preg_replace('/Перевод /ui', '', $sChannelName);

		return $sChannelName;
	}

	/**
	 * Отдает имя канала для статуса, в случае если статусное сообщение - "Займ перечислен"
	 *
	 * @return bool|string
	 */
	public function getChannelNameForStatus()
	{
		$sStatusName = $this->getClientStatus();
		$aStatuses = array(
			self::C_LOAN_ACTIVE,
			self::C_LOAN_TRANSFER,
			self::C_LOAN_CREATED,
		);
		//проверяем, что текущий статус находится в списке статусов, для которых нужно выдать имя канала
		if (in_array($sStatusName, $aStatuses)) {
			$iActiveLoanChannelId = Yii::app()->adminKreddyApi->getSubscriptionActiveLoanChannelId();
			$sChannelName = Yii::app()->productsChannels->formatChannelNameForStatus(Yii::app()->adminKreddyApi->getChannelNameById($iActiveLoanChannelId));

		} else {
			$sChannelName = '';
		}

		return $sChannelName;
	}

	/**
	 * Получение сообщения статуса (активен, в скоринге, ожидает оплаты)
	 *
	 * @return string|bool
	 */
	public function getStatusMessage()
	{

		$sStatusName = $this->getClientStatus();

		if (!SiteParams::getIsIvanovoSite()) {
			$sStatus = (!empty($this->aAvailableStatuses[$sStatusName])) ? $this->aAvailableStatuses[$sStatusName] : self::C_STATUS_ERROR;
		} else {
			$sStatus = (!empty($this->aAvailableStatusesIvanovo[$sStatusName])) ? $this->aAvailableStatusesIvanovo[$sStatusName] : self::C_STATUS_ERROR;
		}

		$sStatus = strtr($sStatus, $this->formatStatusMessage());

		return $sStatus;
	}

	/**
	 * @return string
	 */
	public function getClientStatus()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['status']['name'];
	}

	/**
	 * @return bool
	 */
	public function isFastReg()
	{
		$cStatus = $this->getClientStatus();
		if ($cStatus == self::C_CLIENT_FAST_REG) {
			return true;
		}

		return false;
	}

	/**
	 * Получение баланса
	 *
	 * @return int
	 */
	public function getBalance()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['active_loan']['balance'];
	}

	/**
	 * Получение абсолютного значения баланса
	 *
	 * @return int|number
	 */
	public function getAbsBalance()
	{
		$aClientInfo = $this->getClientInfo();

		return abs($aClientInfo['active_loan']['balance']);
	}

	/**
	 * @return number
	 */
	public function getAbsLoanBalance()
	{
		$aClientInfo = $this->getClientInfo();

		return abs($aClientInfo['active_loan']['loan_balance']);
	}

	/**
	 * @return number
	 */
	public function getAbsSubscriptionBalance()
	{
		$aClientInfo = $this->getClientInfo();

		return abs($aClientInfo['active_loan']['subscription_balance']);
	}

	/**
	 * @return number
	 */
	public function getAbsFineAndPenalty()
	{
		$aClientInfo = $this->getClientInfo();

		return abs($aClientInfo['active_loan']['fine_and_penalty_balance']);
	}

	/**
	 * @return bool|string
	 */
	public function getSubscriptionRequestName()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['subscription_request']['name'];
	}

	/**
	 * @return int
	 */
	public function getSubscriptionRequestType()
	{
		$aClientInfo = $this->getClientInfo(true);

		return $aClientInfo['subscription_request']['type'];
	}


	/**
	 * Получаем сумму из имени пакета, не работает для пакетов типа "Покупки", использовать только для Иваново
	 *
	 * @return bool|string
	 */
	public function getSubscriptionRequestLoan()
	{

		$sProduct = $this->getSubscriptionRequestName();
		$iProductLoan = preg_replace('/[^\d]+/', '', $sProduct);

		return ($iProductLoan) ? $iProductLoan : false;
	}

	/**
	 * ID канала текущей подписки
	 *
	 * @return bool
	 */
	public function getSubscriptionActiveLoanChannelId()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['active_loan']['channel_id'];
	}

	/**
	 * @return bool|string
	 */
	public function getSubscriptionProduct()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['subscription']['product'];
	}

	/**
	 * Получить тип продукта
	 *
	 * @return string
	 */
	public function getProductType()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['subscription']['product_info']['type'];
	}

	/**
	 * Проверяем, является ли текущая подписка "старой", т.е. на старые продукты до КРЕДДИтной линии
	 *
	 * @return bool
	 */
	public function isSubscriptionOldType()
	{
		$sType = $this->getProductType();

		switch ($sType) {
			case self::PRODUCT_TYPE_KREDDY:
			case self::PRODUCT_TYPE_IVANOVO:
				return true;
				break;
			default:
				return false;
				break;
		}

	}

	/**
	 * @return bool|string
	 */
	public function getSubscriptionChannel()
	{
		$aClientInfo = $this->getClientInfo();

		$iChannelId = $aClientInfo['subscription']['channel_id'];

		return $this->getChannelNameById($iChannelId);
	}

	/**
	 * Стоимость текущей подписки клиента из getInfo
	 *
	 * @return bool
	 *
	 */
	public function getSubscriptionCost()
	{
		$aClientInfo = $this->getClientInfo();

		$iSubscriptionCost = $aClientInfo['subscription']['balance'];

		if ($iSubscriptionCost > 0) {
			$iSubscriptionCost = 0;
		} elseif ($iSubscriptionCost < 0) {
			$iSubscriptionCost *= -1;
		}

		return $iSubscriptionCost;

	}

	/**
	 * @return bool|string
	 */
	public function getSubscriptionLoanAmount()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['subscription']['product_info']['loan_amount'];
	}

	/**
	 * @return bool|string
	 */
	public function getSubscriptionLoanLifetime()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['subscription']['product_info']['loan_lifetime'] / 3600 / 24;
	}

	/**
	 * @return bool|string
	 */
	public function getSubscriptionProductId()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['subscription']['product_id'];
	}

	/**
	 * @return string|bool
	 */
	public function getSubscriptionActivity()
	{
		if ($this->isSubscriptionAwaitingConfirmationStatus()) {
			return false;
		}

		$aClientInfo = $this->getClientInfo();
		$sActivityTo = $aClientInfo['subscription']['activity_to'];
		$sActivityTo = $this->formatRusDate($sActivityTo, false);

		return $sActivityTo;
	}

	/**
	 * @return string|bool
	 */
	public function getSubscriptionActivityToTime()
	{
		$aClientInfo = $this->getClientInfo();
		if ($aClientInfo['subscription']['activity_to']) {
			$sActivityTo = $aClientInfo['subscription']['activity_to'];
			$sActivityTo = $this->formatRusDate($sActivityTo, true);

			return $sActivityTo;
		}

		return false;

	}

	/**
	 * @return int|number
	 */
	public function getSubscriptionAvailableLoans()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['subscription']['available_loans'];
	}

	/**
	 * Возвращает PAN карты, если она привязана
	 *
	 * @return bool
	 */
	public function getBankCardPan()
	{
		$aClientInfo = $this->getClientInfo();

		return ($aClientInfo['bank_card_exists'] && $aClientInfo['bank_card_pan'])
			? $aClientInfo['bank_card_pan']
			: false;
	}

	/**
	 * Возвращает дату окончания моратория на заём, если такой мораторий есть
	 *
	 * @return bool|string
	 */
	public function getMoratoriumLoan()
	{
		$aClientInfo = $this->getClientInfo();
		$sMoratoriumTo = $aClientInfo['moratoriums']['loan'];
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
	 * Возвращает дату окончания моратория на заём (выбирая максимум между мораториями на подписку и скоринг),
	 * если такой мораторий есть
	 *
	 * @return bool|string
	 */
	public function getMoratoriumSubscription()
	{
		$aClientInfo = $this->getClientInfo();

		$sMoratoriumSub = $aClientInfo['moratoriums']['subscription'];
		$sMoratoriumScoring = $aClientInfo['moratoriums']['scoring'];

		$sMoratoriumTo = $this->getMaxDateInFormat($sMoratoriumSub, $sMoratoriumScoring);

		return $sMoratoriumTo;
	}

	/**
	 * Возвращает дату окончания моратория на заём (выбирая максимум между мораториями на подписку, скоринг и заём),
	 * если такой мораторий есть
	 *
	 * @return bool|string
	 */
	public function getMoratoriumSubscriptionLoan()
	{
		$aClientInfo = $this->getClientInfo();

		$sMoratoriumLoan = $aClientInfo['moratoriums']['loan'];

		$sMoratoriumTo = $this->getMaxDateInFormat($this->getMoratoriumSubscription(), $sMoratoriumLoan);

		return $sMoratoriumTo;
	}

	/**
	 * @return bool
	 */
	public function isSubscriptionAwaitingConfirmationStatus()
	{
		return $this->getClientStatus() == AdminKreddyApiComponent::C_SUBSCRIPTION_AWAITING_CONFIRMATION;
	}

	/**
	 * @return bool
	 */
	public function getActiveLoanExpired()
	{
		if ($this->isSubscriptionAwaitingConfirmationStatus()) {
			return false;
		}
		$aClientInfo = $this->getClientInfo();
		$bExpired = $aClientInfo['active_loan']['expired'];

		return $bExpired;
	}

	/**
	 * @return bool|string
	 */
	public function getActiveLoanExpiredTo()
	{
		if ($this->isSubscriptionAwaitingConfirmationStatus()) {
			return false;
		}

		$aClientInfo = $this->getClientInfo();

		$sExpiredTo = $aClientInfo['active_loan']['expired_to'];

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

		return $aClientInfo['client_data']['fullname'];
	}

	/**
	 * Получение данных клиента в статусе "быстрая регистрация", заполненных оператором КЦ
	 *
	 * @return array
	 */
	public function getFullClientData()
	{
		$aClientData = $this->getData('full_data');

		return isset($aClientData['client_data']) ? $aClientData['client_data'] : array();
	}

	/**
	 * Есть ли у клиента задолженность по кредиту
	 *
	 * @return bool
	 */
	public function getIsDebt()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['client_data']['is_debt'];
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
		if (SiteParams::getIsIvanovoSite()) {
			$sCacheName = 'productsAndChannelsIvanovo';
		} else {
			$sCacheName = 'productsAndChannels';
		}


		$aProducts = Yii::app()->cache->get($sCacheName);

		if (!empty($aProducts)) {
			return $aProducts;
		}

		$aProductsAndChannels = $this->getData('products_and_channels');

		if ($aProductsAndChannels['code'] === self::ERROR_NONE) {
			//сохраняем в кэш с временем хранения 10 минут
			Yii::app()->cache->set($sCacheName, $aProductsAndChannels, 600);
			//кэш длительного хранения, на случай отключения API
			Yii::app()->cache->set($sCacheName . 'LongTime', $aProductsAndChannels);

		} else {
			//если вдруг при обращении к API вылезла ошибка, достаем данные из длительного кэша

			$aProducts = Yii::app()->cache->get($sCacheName . 'LongTime');
			if (isset($aProducts)) {
				return $aProducts;
			}
		}

		return $aProductsAndChannels;
	}

	/**
	 * @param bool $bPostPaid
	 * @param bool $bPrePaid
	 *
	 * @return array
	 */
	public function getClientProductsList($bPostPaid = true, $bPrePaid = true)
	{
		//получаем список продуктов
		$aProducts = $this->getProducts();
		//получаем список каналов
		$aChannels = $this->getProductsChannels();
		//получаем список каналов, доступных клиенту
		$aClientChannels = $this->getClientChannels();
		$aAvailableProducts = array();
		//проверяем, что получили массивы
		if (is_array($aProducts) && is_array($aChannels) && is_array($aClientChannels)) {

			//перебираем все продукты
			foreach ($aProducts as $aProduct) {
				//Если тип продукта - постоплата и нам не нужны пост-оплатные продукты - пропускаем
				if ($aProduct['type'] == self::PRODUCT_TYPE_KREDDY_LINE_POSTPAID && !$bPostPaid) {
					continue;
				}
				//Если тип продукта - не постоплата (предоплата) и нам не нужны предоплатные продукты - пропускаем
				if ($aProduct['type'] != self::PRODUCT_TYPE_KREDDY_LINE_POSTPAID && !$bPrePaid) {
					continue;
				}

				//получаем из продукта каналы, по которым его можно получить
				$aProductChannels = (isset($aProduct['channels']) && is_array($aProduct['channels']))
					? $aProduct['channels']
					: array();
				//перебираем каналы, по которым можно получить продукт
				foreach ($aProductChannels as $iKey => $aChannel) {
					//проверяем, что у канала есть описание
					//проверяем, что данный канал доступен пользователю
					if (isset($aChannels[$iKey])
						&& in_array($iKey, $aClientChannels)
					) {
						$aAvailableProducts[$aProduct['id']] = $aProduct['name'];
					}
				}
			}
		}

		return $aAvailableProducts;
	}

	/**
	 * Заполняет массив значений доступных каналов соответствующими id канала
	 *
	 * @return array
	 */
	public function getAvailableChannelValues()
	{
		// по умолчанию ставим false, т.е. что каналы недоступны
		$aAvailableChannelValues = array(
			self::C_CARD   => false,
			self::C_MOBILE => false,
		);

		$bIsSecondLoan = $this->getSubscriptionProductId();
		if ($bIsSecondLoan) {
			// если повторный займ - соответственно, берём каналы, доступные по текущей подписке.
			$aAvailableChannelKeys = $this->getClientSubscriptionChannels();
		} else {
			// иначе берём каналы, доступные по выбранному на предыдущем шаге формы Пакету
			$aAvailableChannelKeys = $this->getSelectedProductChannelsList();
		}

		$aAllChannelNames = $this->getProductsChannels();
		foreach (self::$aChannels as $sChannel) {
			$sRegexp = self::$aChannelsRegexps[$sChannel];

			// перебираем все доступные Клиенту каналы
			foreach ($aAvailableChannelKeys as $sKey) {
				// берём соответствующее имя из массива имён каналов
				$sAvailableChannelName = $aAllChannelNames[$sKey];

				// ищем совпадению по регулярному выражению. в случае успеха - записываем id канала на место false
				// например: $aAvailableChannelValues['card'] = 20;
				if (preg_match($sRegexp, $sAvailableChannelName)) {
					$aAvailableChannelValues[$sChannel] = $sKey;
					break;
				}
			}
		}

		return $aAvailableChannelValues;
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
	 * Получение списка каналов, доступных клиенту, выбравшему данный продукт
	 *
	 * @return array
	 */
	public function getSelectedProductChannelsList()
	{
		$aChannels = array();

		$iSelectedProduct = $this->getSubscribeSelectedProduct();
		$aProducts = $this->getProducts();

		if (isset($aProducts[$iSelectedProduct]['channels'])) {
			$aSelectedProductChannels = array_keys($aProducts[$iSelectedProduct]['channels']);
			$aClientChannels = array_values($this->getClientChannels());

			$aChannels = array_intersect($aSelectedProductChannels, $aClientChannels);
		}

		return $aChannels;
	}

	/**
	 * @return array
	 */
	public function getClientProductsChannelListWithAmounts()
	{
		$aProducts = $this->getProductsAndChannels();
		//получаем каналы, доступные клиенту по данной подписке
		$aClientChannels = $this->getClientSubscriptionChannels();

		$aClientChannelsList = array();

		if (isset($aProducts['channels']) && isset($aClientChannels)) {
			foreach ($aClientChannels as $iChannel) {
				//если канал присутствует в списке каналов
				//и находится в списке доступных для данной подписки каналов
				if (isset($aProducts['channels'][$iChannel])
				) {
					$aClientChannelsList[$iChannel] = $this->getSubscriptionLoanAmount() . " рублей " .
						SiteParams::mb_lcfirst(
							ProductsChannelsComponent::formatChannelNameNoOperators($aProducts['channels'][$iChannel])
						);
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
				foreach ($aProductChannels as $iKey => $aChannel) {
					//проверяем, что у канала есть описание
					//проверяем, что данный канал доступен пользователю
					if (isset($aChannels[$iKey])
						&& in_array($iKey, $aClientChannels)
					) {
						$aProductsAndChannels[($aProduct['id'] . '_' . $iKey)] = $aProduct['name'] . ' ' . SiteParams::mb_lcfirst($aChannels[$iKey]);
					}
				}
			}
		}

		return $aProductsAndChannels;
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
	 * @param $iChannelId
	 *
	 * @return bool|string
	 */
	public function getProductCostById($iProductId, $iChannelId)
	{
		$aProducts = $this->getProducts();

		$iSubscriptionCost = 0;

		if (isset($aProducts[$iProductId]['subscription_cost'])) {
			$iSubscriptionCost += $aProducts[$iProductId]['subscription_cost'];
		}
		if (isset($aProducts[$iProductId]['channels'][$iChannelId]['additional_cost'])) {
			$iSubscriptionCost += $aProducts[$iProductId]['channels'][$iChannelId]['additional_cost'];
		}

		return $iSubscriptionCost;
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
	 * Получение канала, выбранного клиентом по умолчанию
	 *
	 * @return int
	 */
	public function getSelectedChannelId()
	{
		$aClientInfo = $this->getClientInfo();

		return $aClientInfo['subscription']['channel_id'];
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
	 * Взять заём, подписанный СМС-кодом
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
	 * Отмена неоплаченной подписки
	 *
	 * @return bool
	 */
	public function doCancelRequest()
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CANCEL_REQUEST);

		if ($aResult['code'] === self::ERROR_NONE) {

			return true;
		} else {
			if (isset($aResult['message'])) {
				$this->setLastMessage($aResult['message']);
			} else {
				$this->setLastMessage(self::ERROR_MESSAGE_UNKNOWN);
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
		if (!isset($this->bIsCanSubscribe) || !isset($this->bIsNeedCard)) {
			$this->requestAdminKreddyApi(self::API_ACTION_CHECK_SUBSCRIBE);
			$this->bIsCanSubscribe = !$this->getIsNotAllowed();

			$this->bIsNeedCard = $this->getIsNeedCard();
		}

		return $this->bIsCanSubscribe;
	}

	/**
	 * @return mixed
	 */
	public function checkSubscribeNeedCard()
	{
		if (!isset($this->bIsNeedCard)) {
			$this->checkSubscribe();
		}

		return $this->bIsNeedCard;
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
		$aRequestData = array(
			'sms_code'    => $sSmsCode,
			'product_id'  => $iProduct,
			'channel_id'  => $iChannelId,
			'tracking_id' => Yii::app()->request->cookies['TrackingID'],
		);

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_SUBSCRIBE, $aRequestData);

		if ($aResult['code'] === self::ERROR_NONE && $aResult['sms_status'] === self::SMS_AUTH_OK) {
			if (isset($aResult['scoring_result'])) {
				$this->setScoringResult($aResult['scoring_result']);
			}
			$this->setLastSmsMessage($aResult['sms_message']);

			return true;
		} else {
			if (isset($aResult['sms_message'])) {
				$this->setScoringResult(null);
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setScoringResult(null);
				$this->setLastSmsMessage(self::ERROR_MESSAGE_UNKNOWN);
			}

			return false;
		}
	}

	/**
	 * @param $sSmsCode
	 * @param $iProduct
	 * @param $iChannelId
	 * @param $iAmount
	 * @param $iTime
	 *
	 * @return bool
	 */
	public function doSubscribeFlexible($sSmsCode, $iProduct, $iChannelId, $iAmount, $iTime)
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_SUBSCRIBE,
			array('sms_code' => $sSmsCode, 'product_id' => $iProduct, 'channel_id' => $iChannelId, 'custom_options' => array('loan_amount' => $iAmount, 'loan_lifetime' => $iTime)));

		if ($aResult['code'] === self::ERROR_NONE && $aResult['sms_status'] === self::SMS_AUTH_OK) {
			if (isset($aResult['scoring_result'])) {
				$this->setScoringResult($aResult['scoring_result']);
			}
			$this->setLastSmsMessage($aResult['sms_message']);

			return true;
		} else {
			if (isset($aResult['sms_message'])) {
				$this->setScoringResult(null);
				$this->setLastSmsMessage($aResult['sms_message']);
			} else {
				$this->setScoringResult(null);
				$this->setLastSmsMessage(self::ERROR_MESSAGE_UNKNOWN);
			}

			return false;
		}
	}

	/**
	 * Заявка на смену паспортных данных, подписанная СМС-кодом
	 *
	 * @param string $sSmsCode
	 *
	 * @param array  $aPassportData
	 *
	 * @return array
	 */
	public function changePassport($sSmsCode, $aPassportData)
	{

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CHANGE_PASSPORT,
			array('sms_code' => $sSmsCode, 'ChangePassportForm' => $aPassportData));

		if (isset($aResult['sms_message'])) {
			$this->setLastSmsMessage($aResult['sms_message']);
		} else {
			$this->setLastSmsMessage($aResult['message']);
		}

		if ($aResult['code'] === self::ERROR_NONE && $aResult['sms_status'] === self::SMS_AUTH_OK) {
			return true;
		}

		return false;
	}

	/**
	 * Отправка СМС с кодом подтверждения смены паспорта
	 *
	 * @return bool
	 */
	public function sendSmsChangePassport()
	{
		//отправляем СМС с кодом
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CHANGE_PASSPORT);

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
	 * Заявка на смену цифрового кода, подписанная СМС-кодом
	 *
	 * @param string $sSmsCode
	 *
	 * @param        $aNumericCode
	 *
	 *
	 * @return bool
	 */
	public function changeNumericCode($sSmsCode, $aNumericCode)
	{

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CHANGE_NUMERIC_CODE,
			array('sms_code' => $sSmsCode, 'ChangeNumericCodeForm' => $aNumericCode));

		return $this->checkChangeResultMessage($aResult);
	}

	/**
	 * Отправка СМС с кодом подтверждения смены цифрового кода
	 *
	 * @return bool
	 */
	public function sendSmsChangeNumericCode()
	{
		//отправляем СМС с кодом
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CHANGE_NUMERIC_CODE);

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
	 * Заявка на смену секретного вопроса, подписанная СМС-кодом
	 *
	 * @param string $sSmsCode
	 *
	 * @param        $aSecretQuestion
	 *
	 * @return bool
	 */
	public function changeSecretQuestion($sSmsCode, $aSecretQuestion)
	{

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CHANGE_SECRET_QUESTION,
			array('sms_code' => $sSmsCode, 'ChangeSecretQuestionForm' => $aSecretQuestion));

		return $this->checkChangeResultMessage($aResult);
	}

	/**
	 * Отправка СМС с кодом подтверждения смены секретного вопроса
	 *
	 * @return bool
	 */
	public function sendSmsChangeSecretQuestion()
	{
		//отправляем СМС с кодом
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CHANGE_SECRET_QUESTION);

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
	 * Заявка на смену параметра СМС-аутентификации, подписанная СМС-кодом
	 *
	 * @param string $sSmsCode
	 *
	 * @param        $aSmsAuthSetting
	 *
	 * @return bool
	 */
	public function changeSmsAuthSetting($sSmsCode, $aSmsAuthSetting)
	{
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CHANGE_SMS_AUTH_SETTING,
			array('sms_code' => $sSmsCode, 'ChangeSmsAuthSettingForm' => $aSmsAuthSetting));

		return $this->checkChangeResultMessage($aResult);
	}

	/**
	 * Отправка СМС с кодом подтверждения для смены параметра аутентификации по СМС
	 *
	 * @return bool
	 */
	public function sendSmsChangeSmsAuthSetting()
	{
		//отправляем СМС с кодом
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CHANGE_SMS_AUTH_SETTING);

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
	 * Заявка на смену пароля, подписанная СМС-кодом
	 *
	 * @param $sSmsCode
	 * @param $aData
	 *
	 * @return bool
	 */
	public function changePassword($sSmsCode, $aData)
	{

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CHANGE_PASSWORD, $aData + array('sms_code' => $sSmsCode));

		$bResult = $this->checkChangeResultMessage($aResult);

		if ($bResult) {
			//обновляем токен сессии в связи со сменой пароля (иначе разлогинит, т.к. пароль в старом токене другой)
			$this->setSessionToken($aResult['token']);
			$this->token = $aResult['token'];
			//ставим флаг успешной СМС-авторизации
			$this->setSmsAuthDone(true);
		}

		return $bResult;
	}

	/**
	 * Отправка СМС с кодом подтверждения смены пароля
	 *
	 * @param array $aData
	 *
	 * @return bool
	 */
	public function sendSmsChangePassword(array $aData)
	{
		//отправляем СМС с кодом
		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CHANGE_PASSWORD, $aData);

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
	 * Привязка банковской карты к аккаунту
	 *
	 * @param AddCardForm $oCardForm
	 *
	 * @return bool
	 */
	public function addClientCard(AddCardForm $oCardForm)
	{


		$aAddCardForm = array(
			'card_pan'          => $oCardForm->sCardPan,
			'card_month'        => $oCardForm->sCardMonth,
			'card_year'         => $oCardForm->sCardYear,

			'card_cvc'          => $oCardForm->sCardCvc,
			'card_printed_name' => $oCardForm->sCardHolderName,
		);

		if (Yii::app()->adminKreddyApi->isCardVerifyNeedAdditionalFields()) {
			$aAdditionalCardFields = array(
				'email'        => $oCardForm->sEmail,
				'address'      => $oCardForm->sAddress,
				'city'         => $oCardForm->sCity,
				'zip_code'     => $oCardForm->sZipCode,
				'country'      => $oCardForm->sCountry,

				'ip'           => Yii::app()->request->getUserHostAddress(),
				'redirect_url' => Yii::app()->createAbsoluteUrl('/account/returnFrom3DSecurity'),
			);

			$aAddCardForm = CMap::mergeArray($aAddCardForm, $aAdditionalCardFields);
		}
		$aRequest = array(
			'AddCard' => $aAddCardForm,
		);

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_ADD_CARD, $aRequest);

		if ($aResult['code'] === self::ERROR_NONE) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $sCardVerifyAmount
	 *
	 * @return bool
	 */
	public function verifyClientCard($sCardVerifyAmount)
	{
		$aRequest = array(
			'card_verify_amount' => $sCardVerifyAmount,
		);

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_VERIFY_CARD, $aRequest);

		$this->setLastMessage($aResult['message']);

		return ($aResult['code'] === self::ERROR_NONE);
	}

	/**
	 * Проверяет, требуется ли клиенту пройти верификацию карты
	 * 0 - не может, нужно сначала добавить карту
	 *
	 * @return stdClass
	 */
	public function checkVerifyCardStatus()
	{
		$sCardVerify3DHtml = null;
		$bCardVerifyNeedWait = false;
		$bCardVerify3DsError = false;

		if (isset($this->oCardVerifyStatus)) {
			return $this->oCardVerifyStatus;
		}

		$aResult = $this->requestAdminKreddyApi(self::API_ACTION_CHECK_CAN_VERIFY_CARD);

		//если код "требуется пройти 3ds"
		if ($aResult['code'] == self::ERROR_NEED_3DS_PROCESS) {
			$sCardVerify3DHtml = isset($aResult['html']) ?
				$aResult['html'] :
				null;
		}

		//если код "ждите"
		if ($aResult['code'] == self::ERROR_NEED_WAIT) {
			$bCardVerifyNeedWait = true;
		}

		//если код "ошибка 3ds"
		if ($aResult['code'] == self::ERROR_VERIFY_3DS) {
			$bCardVerify3DsError = true;
		}


		$oResult = new stdClass();
		$oResult->bCardCanVerify = !empty($aResult['card_can_verify']);
		$oResult->sCardVerify3DHtml = $sCardVerify3DHtml;
		$oResult->bCardVerify3DsError = $bCardVerify3DsError;
		$oResult->bCardVerifyNeedWait = $bCardVerifyNeedWait;
		$oResult->bCardVerifyExists = !empty($aResult['verify_exists']);
		$oResult->bCardVerifyNeedAdditionalFields = !empty($aResult['verify_additional_fields']);

		$this->oCardVerifyStatus = $oResult;

		return $oResult;
	}

	/**
	 * @return mixed
	 */
	public function isCardVerifyNeedAdditionalFields()
	{
		if (!isset($this->oCardVerifyStatus)) {
			$this->checkVerifyCardStatus();
		}

		return $this->oCardVerifyStatus->bCardVerifyNeedAdditionalFields;
	}

	/**
	 * @return mixed
	 */
	public function checkCardVerifyExists()
	{
		if (!isset($this->oCardVerifyStatus)) {
			$this->checkVerifyCardStatus();
		}

		return $this->oCardVerifyStatus->bCardVerifyExists;
	}

	/**
	 * Первая ли это идентификация будет у клиента?
	 *
	 * @return bool
	 */
	public function isFirstIdentification()
	{
		$aData = $this->getClientInfo();

		return $aData['first_identification'];
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
	 * Проверка, нужно ли ввести паспортные данные
	 *
	 * @return bool
	 */
	public function checkIsNeedPassportData()
	{
		$this->getData('check_identify');

		return (!$this->getIsError() && $this->getIsNeedPassportData());
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
			unset($aResult['sms_message']);
			unset($aResult['sms_code']);
			unset($aResult['sms_status']);

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
	 * Сохранение выбранного канала в сессию
	 *
	 * @param $sChannel
	 */
	public function setSubscribeSelectedChannel($sChannel)
	{
		Yii::app()->session['subscribeSelectedChannel'] = $sChannel;
	}

	/**
	 * Получение выбранного продукта из сессии
	 *
	 * @return string|bool
	 */
	public function getSubscribeSelectedChannel()
	{
		return (isset(Yii::app()->session['subscribeSelectedChannel']))
			? Yii::app()->session['subscribeSelectedChannel'] :
			false;
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
			case 'full_data':
				$sAction = self::API_ACTION_GET_FULL_CLIENT_DATA;
				break;
			case 'history':
				$sAction = self::API_ACTION_GET_HISTORY;
				break;
			case 'products_and_channels':
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

		if ($sAction == 'check_identify' && !empty($this->aCheckIdentify)) {
			$aData = $this->aCheckIdentify;
			$this->setLastMessage($aData['message']);
			$this->setLastCode($aData['code']);
		} else {
			$aData = $this->requestAdminKreddyApi($sAction);
			if ($sAction == 'check_identify') {
				$this->aCheckIdentify = $aData;
			}
		}

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
		curl_setopt($ch, CURLOPT_POST, true);

		if (SiteParams::getIsIvanovoSite()) {
			$iEntryPoint = 8;
		} else {
			$iEntryPoint = 1;
		}

		$aRequest = array_merge(array('token' => $this->getSessionToken(), 'entry_point' => $iEntryPoint), $aRequest);

		//если включен debug то делаем чистку данных и trace
		if (defined('YII_DEBUG') && YII_DEBUG) {
			$aTraceData = $aRequest;
			//если есть card_cvc то удаляем его
			if (isset($aTraceData['card_cvc'])) {
				$aTraceData['card_cvc'] = '***';
			}
			if (isset($aTraceData['card_pan'])) {
				$aTraceData['card_pan'] = substr_replace($aTraceData['card_pan'], '********', 4, 8);
			}
			//трейс с чисткой 16-цифровых значений для маскировки номеров карт
			Yii::trace("Action: " . $sAction . " - Request: " . CJSON::encode($aTraceData));
		}

		if (isset($aRequest['files'])) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $aRequest);
		} else {
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aRequest));
		}

		$response = curl_exec($ch);

		if ($response) {
			Yii::trace("Action: " . $sAction . " - Response: " . $response);
			$aGetData = CJSON::decode($response);

			if (is_array($aGetData)) {

				// в случае если сервер ответил, но не передал message,
				$aData = array(
					'message'     => '',
					'sms_message' => '',
					'sms_code'    => '',
					'sms_status'  => '',
				);

				$aData = CMap::mergeArray($aData, $aGetData);
				$this->setLastSmsStatus($aData['sms_status']);
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

		return ($iStatus === self::ERROR_NONE || $iStatus === self::ERROR_NEED_SMS_AUTH || $iStatus === self::ERROR_NEED_SMS_CODE || $iStatus === self::ERROR_NEED_REDIRECT);
	}

	/**
	 * Проверяем, требует ли ответ API авторизации по СМС
	 *
	 * @return bool
	 */
	public function getIsNeedSmsAuth()
	{
		$aInfo = $this->getClientInfo();

		return ($aInfo['code'] === self::ERROR_NEED_SMS_AUTH);
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
		Yii::app()->session['resetPasswordData'] = null;
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
	 *
	 * @return int|''
	 */
	public function getSmsPassSentTime()
	{
		return (!empty(Yii::app()->session['smsPassSentTime'])) ? Yii::app()->session['smsPassSentTime'] : '';
	}

	/**
	 * Проверяем, отправлено ли СМС с кодом подтверждения восстановления пароля
	 *
	 * @return bool
	 */
	public function checkResetPassSmsCodeSent()
	{
		return (!empty(Yii::app()->session['resetPassSmsCodeSent']));
	}

	/**
	 * Возвращаем время отправки СМС с кодом подтверждения восстановления пароля
	 *
	 * @return int|''
	 */
	public function getResetPassSmsCodeSentTime()
	{
		return (!empty(Yii::app()->session['resetPassSmsCodeSentTime'])) ? Yii::app()->session['resetPassSmsCodeSentTime'] : '';
	}

	/**
	 * Сохраняем в сессию телефон, на который запрошено восстановление пароля
	 *
	 * @param array $aData
	 *
	 */
	public function setResetPassData(array $aData)
	{
		Yii::app()->session['resetPasswordData'] = $aData;
	}

	/**
	 * Загрузка из сессии сохраненного номера телефона, указанного в форме восстановления пароля
	 *
	 * @return string
	 */
	public function getResetPassData()
	{
		return (!empty(Yii::app()->session['resetPasswordData'])) ? Yii::app()->session['resetPasswordData'] : '';
	}

	/**
	 * Проверяем, есть ли в сессии номер телефна, указанный в форме восстановления пароля
	 *
	 * @return bool
	 */
	public function checkResetPassPhone()
	{
		return (!empty(Yii::app()->session['resetPasswordData']['phone']));
	}

	/**
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
	 * @param $iResult
	 */
	public function setScoringResult($iResult)
	{
		$this->iScoringResult = $iResult;
	}

	/**
	 * @return mixed
	 */
	public function getScoringResult()
	{
		return $this->iScoringResult;
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
		return ($this->getLastCode() === self::ERROR_NOT_ALLOWED || $this->getLastCode() === self::ERROR_NEED_REDIRECT);
	}

	/**
	 * Проверка ответа checkSubscribe, требуется ли привязать банковскую карту
	 *
	 * @return bool
	 */
	private function getIsNeedCard()
	{
		return ($this->getLastCode() === self::ERROR_NEED_CARD);
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
			&& $this->getLastCode() !== self::ERROR_NEED_PASSPORT_DATA
			&& $this->getLastCode() !== self::ERROR_NEED_REDIRECT
			&& $this->getLastCode() !== self::ERROR_NEED_WAIT
			&& $this->getLastCode() !== self::ERROR_NEED_3DS_PROCESS
		);
	}

	/**
	 * @return bool
	 */
	public function getIsPhoneError()
	{
		return $this->getLastCode() === self::ERROR_PHONE_ERROR;
	}

	/**
	 * Проверка, требуется ли видеоидентификация
	 *
	 * @return bool
	 */
	public function getIsNeedIdentify()
	{
		return ($this->getLastCode() === self::ERROR_NEED_IDENTIFY);
	}

	/**
	 * Проверка, требуется ли подтверждение/изменение паспортных данных
	 *
	 * @return bool
	 */
	public function getIsNeedPassportData()
	{
		return ($this->getLastCode() === self::ERROR_NEED_PASSPORT_DATA);
	}

	/**
	 * @return string
	 */
	public function getDoSubscribeMessage()
	{
		$iScoringResult = $this->getScoringResult();

		if (empty($iScoringResult) || SiteParams::getIsIvanovoSite()) {
			return self::C_DO_SUBSCRIBE_MSG;
		}

		switch ($iScoringResult) {
			case self::C_SCORING_ACCEPT:
				$sMessage = strtr($this->getAutomaticScoringMessage(), $this->formatStatusMessage());

				return $sMessage;
				break;
			case self::C_SCORING_CANCEL:
				return self::C_DO_SUBSCRIBE_MSG_SCORING_CANCELED;
				break;
			default:
				return self::C_DO_SUBSCRIBE_MSG;
		}

	}

	/**
	 * Достаем сообщение для автоматического скоринга в зависимости от типа продукта
	 *
	 * @return string
	 */
	private function getAutomaticScoringMessage()
	{
		$iProductType = $this->getSubscriptionRequestType();

		if ($iProductType == AdminKreddyApiComponent::PRODUCT_TYPE_KREDDY_LINE_POSTPAID) {
			return self::C_DO_SUBSCRIBE_MSG_SCORING_ACCEPTED_POSTPAID;
		}

		return self::C_DO_SUBSCRIBE_MSG_SCORING_ACCEPTED;
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
		if (SiteParams::getIsIvanovoSite()) {
			$sMessage = self::C_SUBSCRIPTION_NOT_AVAILABLE_IVANOVO;
		} else {
			$sMessage = self::C_SUBSCRIPTION_NOT_AVAILABLE;
		}

		$sMessage = strtr($sMessage, $this->formatStatusMessage());

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
	 *
	 */
	public function increaseSmsPassTries()
	{
		Yii::app()->session['iSmsPassTries'] = (Yii::app()->session['iSmsPassTries'])
			? (Yii::app()->session['iSmsPassTries'] + 1)
			: 1;
	}

	/**
	 * @return bool
	 */
	public function getIsSmsPassTriesExceed()
	{
		//увеличиваем счетчик попыток
		$this->increaseSmsPassTries();

		//проверяем, не кончились ли попытки
		return (Yii::app()->session['iSmsPassTries'] > self::C_MAX_PASS_SMS_CODE_TRIES);
	}

	public function resetSmsPassTries()
	{
		Yii::app()->session['iSmsPassTries'] = 0;
	}

	/**
	 *
	 */
	protected function increaseSmsCodeTries()
	{
		Yii::app()->session['iSmsCodeTries'] = (Yii::app()->session['iSmsCodeTries'])
			? (Yii::app()->session['iSmsCodeTries'] + 1)
			: 1;
	}

	/**
	 * @return bool
	 */
	public function getIsSmsCodeTriesExceed()
	{
		//увеличиваем счетчик попыток
		$this->increaseSmsCodeTries();

		//проверяем, не кончились ли попытки
		return (Yii::app()->session['iSmsCodeTries'] > self::C_MAX_SMS_CODE_TRIES);
	}

	public function resetSmsCodeTries()
	{
		Yii::app()->session['iSmsCodeTries'] = 0;
	}

	/**
	 * Получаем данные от API и выдаем соответствующий массив для создания виджета гибкого займа
	 *
	 * @return array
	 */
	public function getFlexibleProduct()
	{
		$aProducts = $this->getProducts();


		$aFlexProduct = array();
		if (is_array($aProducts)) {
			foreach ($aProducts as $aProduct) {
				$iAmount = (!empty($aProduct['amount'])) ? $aProduct['amount'] : 0;
				$aFlexProduct[$iAmount] = $iAmount;
			}
		}

		return $aFlexProduct;
	}

	/**
	 * Получаем список дней (сроков займа) для "гибкого" продукта
	 *
	 * @return array|bool
	 */
	public function getFlexibleProductTime()
	{
		$aProducts = $this->getProducts();

		$aDays = array();
		if (is_array($aProducts)) {
			$aProduct = reset($aProducts);
			if (is_array($aProduct) && isset($aProduct['percentage']) && is_array($aProduct['percentage'])) {
				foreach ($aProduct['percentage'] as $iKey => $aDayPercent) {
					$aDays[$iKey] = $iKey;
				}
			}
		}

		return $aDays;
	}

	/**
	 * Получаем процентную сетку для "гибкого" продукта
	 *
	 * @return array|bool
	 */
	public function getFlexibleProductPercentage()
	{
		$aProducts = $this->getProducts();

		$aFlexProductPercentage = array();
		if (is_array($aProducts)) {
			foreach ($aProducts as $aProduct) {
				$iAmount = (!empty($aProduct['amount'])) ? $aProduct['amount'] : 0;
				$aFlexProductPercentage[$iAmount] = isset($aProduct['percentage']) ? $aProduct['percentage'] : array();
			}
		}

		return $aFlexProductPercentage;
	}

	/**
	 * Получаем стоимости каналов для "гибких" продуктов
	 *
	 * @return array
	 */
	public function getFlexibleProductChannelCosts()
	{
		$aProducts = $this->getProducts();


		$aFlexChannelCosts = array();
		if (is_array($aProducts)) {
			foreach ($aProducts as $aProduct) {
				if (isset($aProduct['amount']) && isset($aProduct['channels']) && is_array($aProduct['channels'])) {
					foreach ($aProduct['channels'] as $iKey => $aChannel) {
						if (isset($aChannel['additional_cost']) && ($aChannel['additional_cost'] > 0)) {
							if (!empty($aFlexChannelCosts[$aProduct['amount']])) {
								$aFlexChannelCosts[$aProduct['amount']] += array($iKey => $aChannel['additional_cost']);
							} else {
								$aFlexChannelCosts[$aProduct['amount']] = array($iKey => $aChannel['additional_cost']);
							}
						}
					}
				}


			}
		}

		return $aFlexChannelCosts;
	}

	/**
	 * @param array $aPassportData
	 */
	public function setPassportData(array $aPassportData)
	{
		Yii::app()->session['aPassportData'] = $aPassportData;
	}

	/**
	 * @return array
	 */
	public function getPassportData()
	{
		return Yii::app()->session['aPassportData'];
	}

	/**
	 * @param array $aNumericCode
	 */
	public function setNumericCode(array $aNumericCode)
	{
		Yii::app()->session['aNumericCode'] = $aNumericCode;
	}

	/**
	 * @return array
	 */
	public function getNumericCode()
	{
		return Yii::app()->session['aNumericCode'];
	}

	/**
	 * @param array $aSecretQuestion
	 */
	public function setSecretQuestion(array $aSecretQuestion)
	{
		Yii::app()->session['aSecretQuestion'] = $aSecretQuestion;
	}

	/**
	 * @return array
	 */
	public function getSecretQuestion()
	{
		return Yii::app()->session['aSecretQuestion'];
	}

	/**
	 * @param array $aSmsAuthSetting
	 */
	public function setSmsAuthSetting(array $aSmsAuthSetting)
	{
		Yii::app()->session['aSmsAuthSetting'] = $aSmsAuthSetting;
	}

	/**
	 * @return array
	 */
	public function getSmsAuthSetting()
	{
		return Yii::app()->session['aSmsAuthSetting'];
	}

	/**
	 * @param $sField
	 *
	 * @return string|bool
	 */
	public function getPassportDataField($sField)
	{
		if ($sField === 'passport_change_reason' && !empty(Yii::app()->session['aPassportData'][$sField])) {
			return (!empty(Dictionaries::$aChangePassportReasons[Yii::app()->session['aPassportData'][$sField]]))
				? Dictionaries::$aChangePassportReasons[Yii::app()->session['aPassportData'][$sField]]
				: false;
		}

		return (!empty(Yii::app()->session['aPassportData'][$sField]))
			? Yii::app()->session['aPassportData'][$sField]
			: false;
	}

	/**
	 * @param $bClientIsOnIdentify
	 *
	 */
	public function setClientOnIdentify($bClientIsOnIdentify)
	{
		Yii::app()->session['bClientOnIdentify'] = $bClientIsOnIdentify;
	}

	/**
	 * @return bool
	 */
	public function getClientOnIdentify()
	{
		return (!empty(Yii::app()->session['bClientOnIdentify']));
	}

	/**
	 * @return bool|string
	 */
	public function getResetPassPhone()
	{
		$aData = $this->getResetPassData();

		return (!empty($aData['phone'])) ? $aData['phone'] : false;
	}

	/**
	 * @param array $aPassword
	 */
	public function setPassword(array $aPassword)
	{
		Yii::app()->session['aPassword'] = $aPassword;
	}

	/**
	 * @return array
	 */
	public function getPassword()
	{
		return (!empty(Yii::app()->session['aPassword'])) ? Yii::app()->session['aPassword'] : array();
	}

	/**
	 * Отправка СМС сообщения через API (для регистрации)
	 *
	 * @param $sPhone
	 * @param $sMessage
	 *
	 * @return bool
	 */
	public function sendSms($sPhone, $sMessage)
	{
		if (!Yii::app()->params['bSmsGateIsOff']) {

			$this->requestAdminKreddyApi('siteClient/sendSms', array(
				'number'  => $sPhone,
				'message' => $sMessage,
			));
			if ($this->getIsError()) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function getIsNeedRedirect()
	{
		$aInfo = $this->getClientInfo();

		return ($aInfo['code'] == self::ERROR_NEED_REDIRECT);
	}

	/**
	 * @param $iAmount
	 */
	public function setSubscribeFlexAmount($iAmount)
	{
		Yii::app()->session['subscribeFlexAmount'] = $iAmount;
	}

	/**
	 *
	 */
	public function getSubscribeFlexAmount()
	{
		return (isset(Yii::app()->session['subscribeFlexAmount']))
			? Yii::app()->session['subscribeFlexAmount']
			: false;
	}

	/**
	 * @param $iChannelId
	 */
	public function setSubscribeFlexChannelId($iChannelId)
	{
		Yii::app()->session['subscribeFlexChannelId'] = $iChannelId;
	}

	/**
	 * @return string|bool
	 */
	public function getSubscribeFlexChannelId()
	{
		return (isset(Yii::app()->session['subscribeFlexChannelId']))
			? Yii::app()->session['subscribeFlexChannelId']
			: false;
	}

	/**
	 * Функция получает список каналов в виде строки "1_2_3", и выбирает из списка каналов один, доступный клиенту
	 *
	 * @param $sChannelsId
	 *
	 * @return int
	 */
	public function getClientSelectedChannelByIdString($sChannelsId)
	{
		//список каналов из сессии (выбранный при регистрации канал/список каналов) разбиваем на массив каналов (если пришел в виде "1_2_3")
		$aChannelsId = explode('_', $sChannelsId);

		//получаем список каналов, доступных клиен
		$aClientChannels = Yii::app()->adminKreddyApi->getClientChannels();

		$iChannelId = 0;
		//если есть канал из сессии и список каналов клиента не пуст
		if (!empty($aClientChannels) && !empty($aChannelsId) > 0) {
			//перебираем список каналов клиента
			foreach ($aClientChannels as $iClientChannel) {
				//если текущий канал есть в массиве каналов из сессии, то его номер устанавливаем в $iChannelId
				if (in_array($iClientChannel, $aChannelsId)) {
					$iChannelId = (int)$iClientChannel;

				}
			}
		}

		return $iChannelId;
	}

	/**
	 * @param $iTime
	 */
	public function setSubscribeFlexTime($iTime)
	{
		Yii::app()->session['subscribeFlexTime'] = $iTime;
	}

	/**
	 * @param bool $bFormat
	 *
	 * @return bool
	 */
	public function getSubscribeFlexTime($bFormat = false)
	{

		$iFlexTime = (isset(Yii::app()->session['subscribeFlexTime']))
			? Yii::app()->session['subscribeFlexTime']
			: false;
		if ($bFormat) {

			$iFlexTimeTo = time() + ($iFlexTime * 60 * 60 * 24);
			$this->formatRusDate($iFlexTimeTo, false);


			return ($iFlexTime) ? $this->formatRusDate($iFlexTimeTo, false) : false;
		}

		return $iFlexTime;
	}

	/**
	 * Считаем стоимость с учетом процентов и стоимости использования канала
	 */
	public function getSubscribeFlexCost()
	{
		$iAmount = $this->getSubscribeFlexAmount();
		$iTime = $this->getSubscribeFlexTime();
		$iChannelId = $this->getSubscribeFlexChannelId();
		$aPercentage = $this->getFlexibleProductPercentage();

		$aChannelCosts = $this->getFlexibleProductChannelCosts();

		// получаем стоимость выбранного канала
		$iChannelCost = (!empty($aChannelCosts[$iAmount][$iChannelId]))
			? $aChannelCosts[$iAmount][$iChannelId]
			: 0;
		$iPercent = (!empty($aPercentage[$iAmount][$iTime]))
			? $aPercentage[$iAmount][$iTime] :
			0;

		return $iAmount + $iChannelCost + $iPercent;
	}

	/**
	 * Получаем ID продукта по его amount'у
	 *
	 * @return int
	 */
	public function getSubscribeFlexProductId()
	{
		$aProducts = $this->getProducts();
		$iAmount = (int)$this->getSubscribeFlexAmount();

		$iProductId = 0;
		if (is_array($aProducts)) {
			foreach ($aProducts as $aProduct) {
				if (!empty($aProduct['amount']) && (int)$aProduct['amount'] === $iAmount) {
					$iProductId = $aProduct['id'];
				}
			}
		}

		return $iProductId;
	}

	/**
	 * Проверка в getInfo есть ли привязанная карта
	 *
	 * @return bool
	 */
	public function getIsClientCardExists()
	{
		$aClientInfo = $this->getClientInfo();

		return (isset($aClientInfo['bank_card_exists']) && $aClientInfo['bank_card_exists'] === true);
	}

	/**
	 * @return bool
	 */
	public function getIsFirstAddingCard()
	{
		$bIsFirstAddingCard = (empty(Yii::app()->session['account_addCard']));

		if ($bIsFirstAddingCard) {
			Yii::app()->session['account_addCard'] = true;
		}

		return $bIsFirstAddingCard;
	}

	/**
	 * @return bool
	 */
	public function getIsFirstVerifyingCard()
	{
		$bIsFirstVerifyingCard = (empty(Yii::app()->session['account_verifyCard']));

		if ($bIsFirstVerifyingCard) {
			Yii::app()->session['account_verifyCard'] = true;
		}

		return $bIsFirstVerifyingCard;
	}

	/**
	 * Установка флага, что сессия пользователя истекла или не истекла.
	 *
	 * @param bool $bFlag
	 */
	public function setUserSessionExpired($bFlag = true)
	{
		Yii::app()->request->cookies['accountSessionExpired'] = new CHttpCookie('accountSessionExpired', $bFlag);
	}

	/**
	 * Истекла ли сессия пользователя
	 *
	 * @return bool
	 */
	public function getIsUserSessionExpired()
	{
		$bResult = (!empty(Yii::app()->request->cookies['accountSessionExpired']->value));

		// если непусто, очищаем куку, чтобы сообщение показывалось всего один раз.
		if ($bResult) {
			unset(Yii::app()->request->cookies['accountSessionExpired']);
		}

		return $bResult;
	}

	/**
	 * @param $aResult
	 *
	 * @return bool
	 */
	private function checkChangeResultMessage($aResult)
	{
		// Ошибок нет
		if ($aResult['code'] === self::ERROR_NONE && $aResult['sms_status'] === self::SMS_AUTH_OK) {

			$this->setLastSmsMessage($aResult['sms_message']);

			return true;
		}

		// смс не ок - отображаем ошибку смс
		if ($aResult['sms_status'] !== self::SMS_AUTH_OK && $aResult['sms_message']) {

			$this->setLastSmsMessage($aResult['sms_message']);

		} elseif ($aResult['sms_status'] === self::SMS_AUTH_OK) {

			$this->setLastSmsMessage($aResult['message']);

		} else {

			$this->setLastSmsMessage(self::ERROR_MESSAGE_UNKNOWN);

		}

		return false;
	}

	/**
	 * @param $iSmsCode
	 */
	private function setLastSmsStatus($iSmsCode)
	{
		$this->iSmsCode = $iSmsCode;
	}

	/**
	 * @return bool
	 */
	public function isSuccessfulLastSmsCode()
	{
		return $this->iSmsCode == self::SMS_AUTH_OK;
	}

	/**
	 * @param $aRequest
	 *
	 * @return array
	 */
	public function sendInfoFromEmail($aRequest)
	{
		return $this->requestAdminKreddyApi(self::API_ACTION_EMAIL_INFO, $aRequest);
	}

	/**
	 * Стоимость оформляемого продукта для процедуры оформления subscribe
	 *
	 * @return bool|string
	 */
	private function getSubscribeProductCost()
	{
		$iProductId = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();
		$iChannelId = Yii::app()->adminKreddyApi->getSubscribeSelectedChannel();

		return Yii::app()->adminKreddyApi->getProductCostById($iProductId, $iChannelId);
	}

	/**
	 * @return bool
	 */
	public function isSelectedChannelBankCard()
	{
		$iChannelId = $this->getLoanSelectedChannel();

		$aAvailableChannels = $this->getAvailableChannelValues();

		if (!isset($aAvailableChannels[self::C_CARD])) {
			return false;
		}

		return $aAvailableChannels[self::C_CARD] == $iChannelId;
	}

	/**
	 * Условия погашения абонентской платы в зависимости от типа продукта
	 *
	 * @param $iProductId
	 *
	 * @return string
	 */
	public function getPaymentRuleByProduct($iProductId)
	{
		$aProducts = $this->getProducts();

		if (!isset($aProducts[$iProductId]['type'])) {
			return '';
		}

		$iProductType = $aProducts[$iProductId]['type'];

		if ($iProductType == self::PRODUCT_TYPE_KREDDY_LINE_POSTPAID) {
			return self::C_KREDDY_LINE_POSTPAID_PAY_RULES;
		}

		return self::C_KREDDY_LINE_PAY_RULES;
	}

	/**
	 * @return bool
	 */
	public function isSubscriptionActive()
	{
		$cClientStatus = $this->getClientStatus();

		return in_array($cClientStatus, $this->aSubscriptionActiveStates);
	}
}
