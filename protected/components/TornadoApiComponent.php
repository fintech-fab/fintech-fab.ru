<?php

/**
 * Class TornadoApiComponent
 */
class TornadoApiComponent
{
	const RESPONSE_CODE_SUCCESS = 0; // запрос успешно выполнен
	const RESPONSE_CODE_ERROR_UNKNOWN = -1; // неизвестная ошибка
	const RESPONSE_CODE_ERROR_VALIDATION = -2; // ошибка валидации
	const RESPONSE_CODE_ERROR_SIGN = -3; // ошибка проверки подписи
	const RESPONSE_CODE_ERROR_EMAIL_PHONE = -4; // ошибка проверки уникальности телефона или email
	const RESPONSE_CODE_ERROR_CHECK_CODE = -5; // ошибка проверки СМС- или email-кода
	const RESPONSE_CODE_ERROR_CHECK_TOKEN = -6; // ошибка проверки token
	const RESPONSE_CODE_ERROR_RESEND = -7; // ошибка повторной отправки: еще нельзя повторно запросить для этого клиента
	const RESPONSE_CODE_ERROR_TOKEN_TIMEOUT = -8; // ошибка токена: время действия токена истекло

	const CHANNEL_MOBILE = 'mobile'; // канал получения - мобильный
	const CHANNEL_CARD = 'card'; // канал получения - банковская карта

	const PAY_TYPE_PREPAID = 'prepaid'; // предоплата
	const PAY_TYPE_POSTPAID = 'postpaid'; // постоплата

	protected $sSecretKey = 'qwerty123';

	public function init()
	{
		if (!empty(Yii::app()->params['landingApiSecretKey'])) {
			$this->sSecretKey = Yii::app()->params['landingApiSecretKey'];
		}
	}

	/**
	 * @return mixed
	 */
	public function getClientPostData()
	{
		$aClientRegisterForm['last_name'] = trim(Yii::app()->request->getPost('last_name'));
		$aClientRegisterForm['first_name'] = trim(Yii::app()->request->getPost('first_name'));
		$aClientRegisterForm['third_name'] = trim(Yii::app()->request->getPost('third_name'));
		$aClientRegisterForm['email'] = trim(Yii::app()->request->getPost('email'));
		$aClientRegisterForm['phone'] = trim(Yii::app()->request->getPost('phone'));
		$aClientRegisterForm['agree'] = trim(Yii::app()->request->getPost('agree'));
		$aClientRegisterForm['product'] = trim(Yii::app()->request->getPost('product'));
		$aClientRegisterForm['pay_type'] = trim(Yii::app()->request->getPost('pay_type'));
		$aClientRegisterForm['channel'] = trim(Yii::app()->request->getPost('channel'));
		$aClientRegisterForm['birthday'] = trim(Yii::app()->request->getPost('birthday'));
		$aClientRegisterForm['email_back_url'] = trim(Yii::app()->request->getPost('email_back_url'));

		return $aClientRegisterForm;
	}

	public function getClientCodesPostData()
	{
		$aClientCodesForm['sms_code'] = trim(Yii::app()->request->getPost('sms_code'));
		$aClientCodesForm['email_code'] = trim(Yii::app()->request->getPost('email_code'));
		$aClientCodesForm['token'] = trim(Yii::app()->request->getPost('token'));

		return $aClientCodesForm;
	}

	public function getResendCodesPostData()
	{
		$aClientCodesForm['token'] = trim(Yii::app()->request->getPost('token'));

		return $aClientCodesForm;
	}

	/**
	 * @return mixed
	 */
	public function getPostSign()
	{
		$sSign = Yii::app()->request->getPost('sign'); // подпись

		return $sSign;
	}

	/**
	 * @param $aClientRegisterForm
	 *
	 * @return bool
	 * @internal param $aData
	 */
	public function prepareData(&$aClientRegisterForm)
	{
		// подготавливаем данные для обработки и сохранения в БД, приводим к виду, обычному для обычной анкеты
		$iPayType = $this->getPayTypeByName($aClientRegisterForm['pay_type']);

		$iProductAmount = (int)$aClientRegisterForm['product'];

		$iProductId = Yii::app()->productsChannels->getProductByAmountAndType($iProductAmount, $iPayType);

		$sChannel = $aClientRegisterForm['channel'];

		$sChannels = Yii::app()->productsChannels->getChannelsForTornadoApi($sChannel);

		$aClientRegisterForm['product'] = $iProductId;
		$aClientRegisterForm['channel_id'] = $sChannels;
		$aClientRegisterForm['pay_type'] = $iPayType;
		unset($aClientRegisterForm['channel']);
	}

	/**
	 * @param $sPayTypeName
	 *
	 * @return bool
	 */
	public function getPayTypeByName($sPayTypeName)
	{
		$aPayTypes = array(
			'postpaid' => 4,
			'prepaid'  => 3,
		);

		if (!in_array($sPayTypeName, array_keys($aPayTypes))) {
			return false;
		}

		return $aPayTypes[$sPayTypeName];
	}

	/**
	 * @param $aData
	 * @param $sSign
	 *
	 * @return bool
	 */
	public function checkSign($aData, $sSign)
	{

		$sTempSign = $this->generateSign($aData);

		return $sSign == $sTempSign;
	}

	/**
	 * @param $aData
	 *
	 * @return string
	 */
	public function generateSign($aData)
	{
		// соберем данные для проверки подписи
		ksort($aData);
		$sData = implode('', $aData);

		return sha1($sData . $this->sSecretKey);
	}

	/**
	 * @param $cErrorCode
	 *
	 * @return string
	 */
	public function generateErrorResponse($cErrorCode)
	{
		$aResponse = array(
			'code' => $cErrorCode,
		);

		return $aResponse;
	}

	/**
	 * @param ClientFastRegForm $oClientFastRegForm
	 *
	 * @return string
	 */
	public function generateValidationErrorResponse(ClientFastRegForm $oClientFastRegForm)
	{
		$aResponse = Yii::app()->tornadoApi->generateErrorResponse(TornadoApiComponent::RESPONSE_CODE_ERROR_VALIDATION);

		$aErrorAttributes = array_keys($oClientFastRegForm->getErrors());

		// у нас поле зовется channel_id, так что переименуем ошибку
		$iChannelKey = array_search('channel_id', $aErrorAttributes);
		if ($iChannelKey) {
			$aErrorAttributes[$iChannelKey] = 'channel';
		}


		$sErrors = implode(',', $aErrorAttributes);
		$aResponse['no_valid'] = $sErrors;

		return $aResponse;
	}

	/**
	 * Проверка по БД СМС-кода и email-кода
	 *
	 * @param ClientData $oClient
	 * @param            $sSmsCode
	 * @param            $sEmailCode
	 *
	 * @return bool
	 */
	public function checkCodes(ClientData $oClient, $sSmsCode, $sEmailCode)
	{
		if (empty($oClient->sms_code) || $oClient->sms_code != $sSmsCode) {

			return false;
		}

		if (empty($oClient->email_code) || $oClient->email_code != $sEmailCode) {

			return false;
		}

		return true;
	}
}