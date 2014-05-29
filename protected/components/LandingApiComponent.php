<?php

/**
 * Class LandingApiComponent
 */
class LandingApiComponent
{
	const RESPONSE_CODE_SUCCESS = 0; // запрос успешно выполнен
	const RESPONSE_CODE_ERROR_UNKNOWN = -1; // неизвестная ошибка
	const RESPONSE_CODE_ERROR_VALIDATION = -2; // ошибка валидации
	const RESPONSE_CODE_ERROR_SIGN = -3; // ошибка проверки подписи
	const RESPONSE_CODE_ERROR_EMAIL_PHONE = -4; // ошибка проверки уникальности телефона или email
	const RESPONSE_CODE_ERROR_CHECK_CODE = -5; // ошибка проверки СМС- или email-кода

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
	 * @param $sData
	 * @param $sSign
	 *
	 * @return bool
	 */
	public function checkSign($sData, $sSign)
	{
		$sTempSign = $this->generateSign($sData);

		return $sSign == $sTempSign;
	}

	/**
	 * @param $sData
	 *
	 * @return string
	 */
	public function generateSign($sData)
	{
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
		//TODO тут определять отдельно ошибку повторного телефона и email и выгружать ее отдельным кодом
		//TODO возможно убрать из валидаторов проверку по телефону, и отправлять всё на проверку в siteApi

		$aResponse = Yii::app()->landingApi->generateErrorResponse(LandingApiComponent::RESPONSE_CODE_ERROR_VALIDATION);

		$aErrorAttributes = array_keys($oClientFastRegForm->getErrors());

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

		if (empty($oClient->emailcode) || $oClient->email_code != $sEmailCode) {
			return false;
		}

		return true;
	}
}