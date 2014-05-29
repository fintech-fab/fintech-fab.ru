<?php

/**
 * Контроллер API для лэндинга online.kreddy.ru
 *
 * Class ApiController
 */
class ApiController extends Controller
{
	public function init()
	{
		$this->disableDebugToolbar();
		$this->_disableLog();

		Yii::app()->errorHandler->errorAction = 'api/error';
	}

	public function actionSignup()
	{
		$sLastName = trim(Yii::app()->request->getPost('last_name'));
		$sFirstName = trim(Yii::app()->request->getPost('first_name'));
		$sThirdName = trim(Yii::app()->request->getPost('third_name'));
		$sEmail = trim(Yii::app()->request->getPost('email'));
		$sPhone = trim(Yii::app()->request->getPost('phone'));
		$sAgree = trim(Yii::app()->request->getPost('agree'));
		$sProduct = trim(Yii::app()->request->getPost('product'));
		$sPayType = trim(Yii::app()->request->getPost('pay_type'));
		$sChannel = trim(Yii::app()->request->getPost('channel'));
		$sSign = Yii::app()->request->getPost('sign'); // подпись

		// соберем данные для проверки подписи
		$sData = $sLastName . $sFirstName . $sThirdName . $sEmail . $sPhone . $sAgree . $sProduct . $sPayType . $sChannel;

		// проверим подпись
		if (!Yii::app()->landingApi->checkSign($sData, $sSign)) {
			// TODO сделать логирование неудачных попыток
			$aResponse = Yii::app()->landingApi->generateErrorResponse(LandingApiComponent::RESPONSE_CODE_ERROR_SIGN);
			$this->response($aResponse);
		}

		$iProductId = 1; //TODO определять продукт исходя из типа и суммы
		$iChannelId = 2; //TODO определять канал

		$oClientFastRegForm = new ClientFastRegForm();
		$oClientFastRegForm->last_name = $sLastName;
		$oClientFastRegForm->first_name = $sFirstName;
		$oClientFastRegForm->third_name = $sThirdName;
		$oClientFastRegForm->email = $sEmail;
		$oClientFastRegForm->phone = $sPhone;
		$oClientFastRegForm->agree = (int)$sAgree;
		$oClientFastRegForm->product = $iProductId;
		$oClientFastRegForm->channel_id = $iChannelId;

		// валидируем
		if (!$oClientFastRegForm->validate()) {
			$aResponse = Yii::app()->landingApi->generateValidationErrorResponse($oClientFastRegForm);
			$this->response($aResponse);
		}

		//TODO генерируем коды и пишем в БД

		$aClientData = $oClientFastRegForm->getValidAttributes();

		// пишем данные в БД
		$oClient = ClientData::addClient($oClientFastRegForm->phone);
		$iClientId = $oClient->client_id;
		$bSaveResult = ClientData::saveClientDataById($aClientData, $iClientId);

		if (!$bSaveResult) {
			$this->responseUnknownError();
		}

		// TODO тут отправляем в siteApi запрос на отправку email и sms
		// TODO в siteApi модифицировать методы отправки, они должны проверять наличие телефона и email в БД, и ругаться

		$aResponse = array(
			'code'        => LandingApiComponent::RESPONSE_CODE_SUCCESS,
			'client_code' => CryptArray::encryptVal($iClientId), // код клиента, он будет использоваться для поиска по таблице
		);

		echo CJSON::encode($aResponse);
		Yii::app()->end();
	}

	public function actionSubmitCodes()
	{
		$sSmsCode = trim(Yii::app()->request->getPost('sms_code'));
		$sEmailCode = trim(Yii::app()->request->getPost('email_code'));
		$sClientCode = trim(Yii::app()->request->getPost('client_code'));
		$sSign = Yii::app()->request->getPost('sign'); // подпись

		$sData = $sSmsCode . $sEmailCode . $sClientCode;

		// проверим подпись
		if (!Yii::app()->landingApi->checkSign($sData, $sSign)) {
			$aResponse = Yii::app()->landingApi->generateErrorResponse(LandingApiComponent::RESPONSE_CODE_ERROR_SIGN);
			$this->response($aResponse);
		}

		/**
		 * получаем ID в виде int, если вдруг код расшифрован неверно, но приведение к int даст валидный результат,
		 * то ничего страшного, дальше прежде чем что-то делать мы проверим коды по БД, и для неправильного ID они не
		 * пройдут проверку
		 */
		$iClientId = (int)CryptArray::decryptVal($sClientCode);

		// при правильно расшифрованном коде сравнение должно пройти успешно, т.к. это не строгое сравнение
		if ($sClientCode != $iClientId) {
			$aResponse = Yii::app()->landingApi->generateErrorResponse(LandingApiComponent::RESPONSE_CODE_ERROR_CHECK_CODE);
			$this->response($aResponse);
		}

		// ищем в базе клиента
		$oClient = ClientData::getClientById($iClientId);

		if (!$oClient) {
			$this->responseUnknownError();
		}

		// проверяем коды email и sms
		if (!Yii::app()->landingApi->checkCodes($oClient, $sSmsCode, $sEmailCode)) {
			$aResponse = Yii::app()->landingApi->generateErrorResponse(LandingApiComponent::RESPONSE_CODE_ERROR_CHECK_CODE);
			$this->response($aResponse);
		}


		// TODO отправляем в API и пишем в БД все нужные флаги

		// TODO авторизуем клиента и пишем token в БД


		$sClientHash = CryptArray::encrypt(
			array(
				'id'    => $iClientId,
				'phone' => $oClient->phone,
			)
		);

		$aResponse = array(
			'code'         => LandingApiComponent::RESPONSE_CODE_SUCCESS,
			'redirect_url' => $this->createAbsoluteUrl('/account/redirect', array('client' => $sClientHash)), // тут некий код клиента, например client_id по таблице сайта или хэш от client_id
		);

		$this->response($aResponse);
	}

	public function actionError()
	{
		$this->responseUnknownError();
	}

	protected function responseUnknownError()
	{
		$aResponse = Yii::app()->landingApi->generateErrorResponse(LandingApiComponent::RESPONSE_CODE_ERROR_UNKNOWN);
		$this->response($aResponse);
	}

	/**
	 * @param $aResponse
	 */
	protected function response($aResponse)
	{
		header('Content-Type: application/json');

		echo CJSON::encode($aResponse);
		Yii::app()->end();
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