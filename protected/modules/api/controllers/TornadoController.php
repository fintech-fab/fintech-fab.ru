<?php

/**
 * Контроллер API для лэндинга online.kreddy.ru
 *
 * Class TornadoController
 */
class TornadoController extends Controller
{
	/**
	 * Модель логгера
	 *
	 * @var ApiLog
	 */
	protected $oRequestLog;

	/**
	 * Массив с ответом API
	 *
	 * @var
	 */
	protected $aResponse = array(
		'code' => -1,
	);

	protected $bTest;


	public function init()
	{
		$this->disableDebugToolbar();
		$this->_disableLog();

		$this->oRequestLog = new ApiLog();

		Yii::app()->errorHandler->errorAction = 'api/tornado/error';

		$this->bTest = Yii::app()->request->getPost('test', false);
	}

	public function actionSignup()
	{
		$aClientRegisterForm = Yii::app()->tornadoApi->getClientPostData();
		$sSign = Yii::app()->tornadoApi->getPostSign();

		// проверим подпись
		if (!Yii::app()->tornadoApi->checkSign($aClientRegisterForm, $sSign)) {
			$this->aResponse = Yii::app()->tornadoApi->generateErrorResponse(TornadoApiComponent::RESPONSE_CODE_ERROR_SIGN);

			return;
		}

		// подготовим данные к проверке
		Yii::app()->tornadoApi->prepareData($aClientRegisterForm);

		$oClientFastRegForm = new ClientApiRegForm();
		$oClientFastRegForm->setAttributes($aClientRegisterForm);

		// валидируем
		if (!$oClientFastRegForm->validate()) {
			$this->aResponse = Yii::app()->tornadoApi->generateValidationErrorResponse($oClientFastRegForm);

			return;
		}

		// получим массив данных формы
		$aClientData = $oClientFastRegForm->getAttributes();

		// генерируем коды
		$sSmsCode = Yii::app()->clientForm->generateCode(SiteParams::C_SMS_CODE_LENGTH);
		$sEmailCode = Yii::app()->clientForm->generateCode(SiteParams::C_EMAIL_CODE_LENGTH);

		$sMessage = "Ваш код подтверждения: " . $sSmsCode;
		// отправляем СМС через API
		$bSmsSentOk = Yii::app()->adminKreddyApi->sendSms($aClientData['phone'], $sMessage);
		// отправляем email через API
		$bEmailSentOk = Yii::app()->adminKreddyApi->sendEmailCode($aClientData['email'], $sEmailCode, $aClientRegisterForm['email_back_url']);

		// пишем данные в БД
		$oClient = ClientData::addClient($oClientFastRegForm->phone);
		$iClientId = $oClient->client_id;

		//если клиент запрашивает СМС, значит, заполнил анкету полностью
		$aClientData['complete'] = 1;

		$aClientData['sms_code'] = $sSmsCode;
		$aClientData['email_code'] = $sEmailCode;

		$bSaveResult = ClientData::saveClientDataById($aClientData, $iClientId);


		// проверим, вдруг что пошло не так, поругаемся ошибкой
		if (!$bSaveResult || !$bSmsSentOk || !$bEmailSentOk) {
			$this->responseUnknownError();

			return;
		}

		$sToken = CryptArray::encrypt(
			array(
				'id'              => $iClientId,
				'timestamp'       => SiteParams::getTime(),
				'sms_timestamp'   => SiteParams::getTime(),
				'email_timestamp' => SiteParams::getTime(),
			)
		);

		$this->aResponse = array(
			'code'  => TornadoApiComponent::RESPONSE_CODE_SUCCESS,
			'token' => $sToken,
		);

		return;
	}

	public function actionClientCode()
	{
		$aClientCodesForm = Yii::app()->tornadoApi->getClientCodesPostData();

		$sSign = Yii::app()->tornadoApi->getPostSign();

		// проверим подпись
		if (!Yii::app()->tornadoApi->checkSign($aClientCodesForm, $sSign)) {
			$this->aResponse = Yii::app()->tornadoApi->generateErrorResponse(TornadoApiComponent::RESPONSE_CODE_ERROR_SIGN);

			return;
		}

		$aTokenData = CryptArray::decrypt($aClientCodesForm['token']);

		try {
			list($sClientId, $sTimestamp) = $aTokenData;
		} catch (Exception $e) {
			$this->aResponse = Yii::app()->tornadoApi->generateErrorResponse(TornadoApiComponent::RESPONSE_CODE_ERROR_CHECK_TOKEN);

			return;
		}

		/**
		 * получаем ID в виде int, если вдруг код расшифрован неверно, но приведение к int даст валидный результат,
		 * то ничего страшного, дальше прежде чем что-то делать мы проверим коды по БД, и для неправильного ID они не
		 * пройдут проверку
		 */
		$iClientId = (int)$sClientId;
		$iTimestamp = (int)$sTimestamp;

		// при правильно расшифрованном коде сравнение должно пройти успешно, т.к. это не строгое сравнение
		if ($sClientId != $iClientId) {
			$this->aResponse = Yii::app()->tornadoApi->generateErrorResponse(TornadoApiComponent::RESPONSE_CODE_ERROR_CHECK_TOKEN);

			return;
		}

		// время действия токена 1 час
		if ($iTimestamp + SiteParams::CTIME_HOUR < SiteParams::getTime()) {
			$this->aResponse = Yii::app()->tornadoApi->generateErrorResponse(TornadoApiComponent::RESPONSE_CODE_ERROR_TOKEN_TIMEOUT);

			return;
		}

		// ищем в базе клиента
		$oClient = ClientData::getClientById($iClientId);

		if (!$oClient) {
			$this->responseUnknownError();

			return;
		}

		// проверяем коды email и sms
		if (!Yii::app()->tornadoApi->checkCodes($oClient, $aClientCodesForm['sms_code'], $aClientCodesForm['email_code'])) {
			$this->aResponse = Yii::app()->tornadoApi->generateErrorResponse(TornadoApiComponent::RESPONSE_CODE_ERROR_CHECK_CODE);

			return;
		}


		// отправляем в API
		$aClientData = $oClient->getAttributes();

		if (!$this->bTest) {
			$mToken = Yii::app()->adminKreddyApi->createTornadoApiClient($aClientData);
		} else {
			$mToken = 'sometokendatafortest';
		}

		// проверим ответ сервера, не существует ли уже такой клиент
		$bClientExists = Yii::app()->adminKreddyApi->getIsClientExistsError();
		if ($bClientExists) {
			$this->aResponse = Yii::app()->tornadoApi->generateErrorResponse(TornadoApiComponent::RESPONSE_CODE_ERROR_EMAIL_PHONE);

			return;
		}

		// если ошибки существования клиента не возникло, но все равно регистрация не удалась, ответим неизвестной ошибкой
		if (!$mToken) {
			$this->responseUnknownError();

			return;
		}

		// поставим флаги подтверждения СМС и заполнения анкеты
		$oClient->flag_sms_confirmed = 1;
		$oClient->complete = 1;

		// сохраним токен для авторизации клиента после редиректа
		$oClient->api_token = $mToken;
		$oClient->save();

		$sClientHash = CryptArray::encrypt(
			array(
				'id'    => $iClientId,
				'phone' => $oClient->phone,
			)
		);

		$this->aResponse = array(
			'code' => TornadoApiComponent::RESPONSE_CODE_SUCCESS,
			'redirect_url' => $this->createAbsoluteUrl('/account/redirect', array('client' => $sClientHash)), // тут некий код клиента, например client_id по таблице сайта или хэш от client_id
		);

		return;
	}

	/**
	 * Повторная отправка СМС кода
	 */
	public function actionReSendSms()
	{
		$this->resendCode();
	}

	/**
	 * Повторная отправка e-mail кода
	 */
	public function actionReSendEmail()
	{
		$this->resendCode(true);
	}

	public function actionError()
	{
		$sMessage = null;

		if ($aError = Yii::app()->errorHandler->error) {
			$sMessage = $aError['message'];
		}

		$this->aResponse = Yii::app()->tornadoApi->generateErrorResponse(
			TornadoApiComponent::RESPONSE_CODE_ERROR_UNKNOWN,
			$sMessage
		);

		return;
	}

	/**
	 * Функция повторной посылки кодов, по-умолчанию СМС, при параметре true - email
	 *
	 * @param bool $bResendEmail
	 */
	protected function resendCode($bResendEmail = false)
	{
		$aResendCodesData = Yii::app()->tornadoApi->getResendCodesPostData();

		$sSign = Yii::app()->tornadoApi->getPostSign();

		// проверим подпись
		if (!Yii::app()->tornadoApi->checkSign($aResendCodesData, $sSign)) {
			$this->aResponse = Yii::app()->tornadoApi->generateErrorResponse(TornadoApiComponent::RESPONSE_CODE_ERROR_SIGN);

			return;
		}

		$aTokenData = CryptArray::decrypt($aResendCodesData['token']);

		try {
			list($sClientId, $sTimestamp, $sSmsTimestamp, $sEmailTimestamp) = $aTokenData;
		} catch (Exception $e) {
			$this->aResponse = Yii::app()->tornadoApi->generateErrorResponse(TornadoApiComponent::RESPONSE_CODE_ERROR_CHECK_TOKEN);

			return;
		}

		$iClientId = (int)$sClientId;
		$iTimestamp = (int)$sTimestamp;
		$iSmsTimestamp = (int)$sSmsTimestamp;
		$iEmailTimestamp = (int)$sEmailTimestamp;

		// при правильно расшифрованном коде сравнение должно пройти успешно, т.к. это не строгое сравнение
		if ($sClientId != $iClientId) {
			$this->aResponse = Yii::app()->tornadoApi->generateErrorResponse(TornadoApiComponent::RESPONSE_CODE_ERROR_CHECK_TOKEN);

			return;
		}

		// время действия токена 1 час
		if ($iTimestamp + SiteParams::CTIME_HOUR < SiteParams::getTime()) {
			$this->aResponse = Yii::app()->tornadoApi->generateErrorResponse(TornadoApiComponent::RESPONSE_CODE_ERROR_TOKEN_TIMEOUT);

			return;
		}

		// определим, по какому времени нужно сделать проверку, исходя из запрошенного кода
		if ($bResendEmail) {
			$iCheckTimestamp = $iEmailTimestamp;
		} else {
			$iCheckTimestamp = $iSmsTimestamp;
		}


		// если со времени отправки прошло менее 3 минут
		$iSecondsToResend = ($iCheckTimestamp + 3 * 60) - SiteParams::getTime();

		if ($iSecondsToResend > 0) {
			$this->aResponse = array(
				'code'              => TornadoApiComponent::RESPONSE_CODE_ERROR_RESEND,
				'seconds_to_resend' => $iSecondsToResend, // секунд до возможности переотправки кода
			);

			return;
		}

		// ищем в базе клиента
		$oClient = ClientData::getClientById($iClientId);

		if (!$oClient) {
			$this->responseUnknownError();

			return;
		}

		if ($bResendEmail) {
			// отправляем email через API
			$bSentOk = Yii::app()->adminKreddyApi->sendEmailCode($oClient->email, $oClient->email_code);
		} else {
			$sMessage = "Ваш код подтверждения: " . $oClient->sms_code;
			// отправляем СМС через API
			$bSentOk = Yii::app()->adminKreddyApi->sendSms($oClient->phone, $sMessage);

		}
		// проверим, вдруг что пошло не так, поругаемся ошибкой
		if (!$bSentOk) {
			$this->responseUnknownError();

			return;
		}

		// установим по-умолчанию старое время отправки
		$iSmsNewTimestamp = $iSmsTimestamp;
		$iEmailNewTimestamp = $iEmailTimestamp;

		// в зависимости от запрошенного кода, ставим новое время - текущее
		if ($bResendEmail) {
			$iEmailNewTimestamp = SiteParams::getTime();
		} else {
			$iSmsNewTimestamp = SiteParams::getTime();
		}

		$sToken = CryptArray::encrypt(
			array(
				'id'              => $iClientId,
				'timestamp'       => SiteParams::getTime(),
				'sms_timestamp'   => $iSmsNewTimestamp,
				'email_timestamp' => $iEmailNewTimestamp,
			)
		);

		$this->aResponse = array(
			'code'  => TornadoApiComponent::RESPONSE_CODE_SUCCESS,
			'token' => $sToken,
		);
	}

	protected function responseUnknownError()
	{
		$this->aResponse = Yii::app()->tornadoApi->generateErrorResponse(TornadoApiComponent::RESPONSE_CODE_ERROR_UNKNOWN);

		return;
	}

	/**
	 * @param $sAction
	 */
	protected function doApiLog($sAction)
	{
		$sController = $this->id;

		$sResponse = var_export($this->aResponse, true);

		$sPath = Yii::app()->request->getRequestUri();

		$sRequest = var_export($_POST, true);

		$iResponseCode = $this->aResponse['code'];

		//пишем в лог собранную информацию
		$this->oRequestLog->write($sController, $sAction, $sPath, $sRequest, $iResponseCode, $sResponse);
	}

	/**
	 * @param CAction $action
	 */
	protected function afterAction($action)
	{
		$sAction = $action->id;

		// логируем запрос
		$this->doApiLog($sAction);

		// устанавливаем заголовок
		header('Content-Type: application/json');

		// отправляем ответ
		echo CJSON::encode($this->aResponse);

		parent::afterAction($action);
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}