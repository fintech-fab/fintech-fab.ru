<?php

/**
 * Created by PhpStorm.
 * User: d.laptev
 * Date: 16.09.14
 * Time: 12:07
 */
class SmsCodeComponent extends CComponent
{

	/**
	 * Получить авторизацию на сайте
	 */
	const C_TYPE_SITE_AUTH = 1;

	/**
	 * Подтверждение сброса пароля
	 */
//	const C_TYPE_RESET_PASSWORD = 2;

	/**
	 * Подписка на продукт
	 */
	const C_TYPE_SUBSCRIBE = 3;

	/**
	 * Получение займа
	 */
	const C_TYPE_LOAN = 4;

	/**
	 * Изменение персональных данных
	 */
	const C_TYPE_CHANGE_EMAIL = 5;
	const C_TYPE_CHANGE_NUMERIC_CODE = 6;
	const C_TYPE_CHANGE_PASSPORT = 7;
	const C_TYPE_CHANGE_PASSWORD = 8;
	const C_TYPE_CHANGE_SECRET_QUESTION = 9;

	/**
	 * Изменение настроек смс-аутентификации
	 */
	const C_TYPE_CHANGE_SMS_AUTH_SETTING = 10;

	/**
	 * Состояния
	 */
	const C_STATE_NEED_SEND = 'need_send';
	const C_STATE_NEED_CHECK = 'need_check';
	const C_STATE_NEED_CHECK_OK = 'need_check_ok';
	const C_STATE_ERROR = 'error';

	public static $aApiActions = array(
		self::C_TYPE_SITE_AUTH                    => AdminKreddyApiComponent::API_ACTION_CHECK_SMS_CODE,
//		self::C_TYPE_RESET_PASSWORD               => '',
		self::C_TYPE_SUBSCRIBE                    => AdminKreddyApiComponent::API_ACTION_DO_CONFIRM_SUBSCRIPTION,
		self::C_TYPE_LOAN                         => AdminKreddyApiComponent::API_ACTION_LOAN_CONFIRM,
		self::C_TYPE_CHANGE_EMAIL                 => AdminKreddyApiComponent::API_ACTION_CHANGE_EMAIL,
		self::C_TYPE_CHANGE_NUMERIC_CODE          => AdminKreddyApiComponent::API_ACTION_CHANGE_NUMERIC_CODE,
		self::C_TYPE_CHANGE_PASSPORT              => AdminKreddyApiComponent::API_ACTION_CHANGE_PASSPORT,
		self::C_TYPE_CHANGE_PASSWORD              => AdminKreddyApiComponent::API_ACTION_CHANGE_PASSWORD,
		self::C_TYPE_CHANGE_SECRET_QUESTION       => AdminKreddyApiComponent::API_ACTION_CHANGE_SECRET_QUESTION,
		self::C_TYPE_CHANGE_SMS_AUTH_SETTING      => AdminKreddyApiComponent::API_ACTION_CHANGE_SMS_AUTH_SETTING,
	);

	public static $aSiteActions = array(
		self::C_TYPE_SITE_AUTH                    => '/account/smsPassAuth',
//		self::C_TYPE_RESET_PASSWORD               => '',
		self::C_TYPE_SUBSCRIBE                    => '/account/doSubscribeConfirm',
		self::C_TYPE_LOAN                         => '/account/doLoanConfirm',
		self::C_TYPE_CHANGE_EMAIL                 => '/account/changeEmailSendSmsCode',
		self::C_TYPE_CHANGE_NUMERIC_CODE          => '/account/changeNumericCodeSendSmsCode',
		self::C_TYPE_CHANGE_PASSPORT              => '/account/changePassportSendSmsCode',
		self::C_TYPE_CHANGE_PASSWORD              => '/account/changePasswordSendSmsCode',
		self::C_TYPE_CHANGE_SECRET_QUESTION       => '/account/changeSecretQuestionSendSmsCode',
		self::C_TYPE_CHANGE_SMS_AUTH_SETTING      => '/account/changeSmsAuthSettingSendSmsCode',
	);

	public function init()
	{
	}

	public function getState($sType)
	{
		if (!empty(Yii::app()->session['SmsCodeState' . $sType])) {
			return Yii::app()->session['SmsCodeState' . $sType];
		}

		return self::C_STATE_NEED_SEND;
	}

	public function setState($sState, $sType)
	{
		Yii::app()->session['SmsCodeState' . $sType] = $sState;
	}

	public function getActionByType($sType)
	{
		return self::$aSiteActions[$sType];
	}

} 