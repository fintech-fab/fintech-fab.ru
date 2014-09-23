<?php

/**
 * Created by PhpStorm.
 * User: d.laptev
 * Date: 16.09.14
 * Time: 12:07
 */
class SmsCodeComponent extends CComponent
{

	const C_MAX_SMS_CODE_TRIES = 3;

	/**
	 * Получить авторизацию на сайте
	 */
	const C_TYPE_SITE_AUTH = 1;

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
	 * Изменение настроек автосписания
	 */
	const C_TYPE_CHANGE_AUTO_DEBITING_SETTING = 11;
	/**
	 * Состояния
	 */
	const C_STATE_NEED_SEND = 'need_send';
	const C_STATE_NEED_CHECK = 'need_check';
	const C_STATE_NEED_CHECK_OK = 'need_check_ok';
	const C_STATE_ERROR = 'error';

	public static $aApiActions = array(
		self::C_TYPE_SITE_AUTH                    => AdminKreddyApiComponent::API_ACTION_CHECK_SMS_CODE,
		self::C_TYPE_SUBSCRIBE                    => AdminKreddyApiComponent::API_ACTION_DO_CONFIRM_SUBSCRIPTION,
		self::C_TYPE_LOAN                         => AdminKreddyApiComponent::API_ACTION_LOAN_CONFIRM,
		self::C_TYPE_CHANGE_EMAIL                 => AdminKreddyApiComponent::API_ACTION_CHANGE_EMAIL,
		self::C_TYPE_CHANGE_NUMERIC_CODE          => AdminKreddyApiComponent::API_ACTION_CHANGE_NUMERIC_CODE,
		self::C_TYPE_CHANGE_PASSPORT              => AdminKreddyApiComponent::API_ACTION_CHANGE_PASSPORT,
		self::C_TYPE_CHANGE_PASSWORD              => AdminKreddyApiComponent::API_ACTION_CHANGE_PASSWORD,
		self::C_TYPE_CHANGE_SECRET_QUESTION       => AdminKreddyApiComponent::API_ACTION_CHANGE_SECRET_QUESTION,
		self::C_TYPE_CHANGE_SMS_AUTH_SETTING      => AdminKreddyApiComponent::API_ACTION_CHANGE_SMS_AUTH_SETTING,
		self::C_TYPE_CHANGE_AUTO_DEBITING_SETTING => AdminKreddyApiComponent::API_ACTION_CHANGE_AUTO_DEBITING_SETTING,
	);

	public static $aSiteActions = array(
		self::C_TYPE_SITE_AUTH                    => '/account/smsPassAuth',
		self::C_TYPE_SUBSCRIBE                    => '/account/doSubscribeConfirm',
		self::C_TYPE_LOAN                         => '/account/doLoanConfirm',
		self::C_TYPE_CHANGE_EMAIL                 => '/account/changeEmailSendSmsCode',
		self::C_TYPE_CHANGE_NUMERIC_CODE          => '/account/changeNumericCodeSendSmsCode',
		self::C_TYPE_CHANGE_PASSPORT              => '/account/changePassportSendSmsCode',
		self::C_TYPE_CHANGE_PASSWORD              => '/account/changePasswordSendSmsCode',
		self::C_TYPE_CHANGE_SECRET_QUESTION       => '/account/changeSecretQuestionSendSmsCode',
		self::C_TYPE_CHANGE_SMS_AUTH_SETTING      => '/account/changeSmsAuthSettingSendSmsCode',
		self::C_TYPE_CHANGE_AUTO_DEBITING_SETTING => '/account/changeAutoDebitingSettingSendSmsCode',
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

	public function getResendErrorMessage()
	{
		$sMin = Dictionaries::getNumEnding(SiteParams::API_MINUTES_UNTIL_RESEND, ['минута', 'минуты', 'минут']);

		return 'Повторно отправлять СМС можно не чаще 1 раза в ' . SiteParams::API_MINUTES_UNTIL_RESEND . ' ' . $sMin;
	}

	/**
	 * Получаем время, оставшееся до возможности повторной отправки SMS (форма Восстановление пароля)
	 *
	 * @return integer
	 */
	public function getResetSmsCodeLeftTime()
	{
		$iCurTime = time();
		$iLeftTime = (!empty(Yii::app()->session['resetSmsCodeSentTime']))
			? Yii::app()->session['resetSmsCodeSentTime']
			: $iCurTime;
		$iLeftTime = $iCurTime - $iLeftTime;
		$iLeftTime = SiteParams::API_MINUTES_UNTIL_RESEND * 60 - $iLeftTime;

		return $iLeftTime;
	}

	/**
	 * Сохраняем время отправки СМС-кода для восстановления пароля и ставим флаг "СМС отправлено"
	 */
	public function setResetSmsCodeSentAndTime()
	{
		Yii::app()->session['resetSmsCodeSent'] = true;
		Yii::app()->session['resetSmsCodeSentTime'] = time();
		Yii::app()->session['resetSmsCodeLeftTime'] = SiteParams::API_MINUTES_UNTIL_RESEND * 60;
	}

	/**
	 * очищаем сессии, связанные с отправкой SMS (форма Восстановления пароля)
	 */
	public function clearResetSmsCodeState()
	{
		Yii::app()->session['resetSmsCodeSent'] = null;
		Yii::app()->session['resetSmsCodeSentTime'] = null;
		Yii::app()->session['resetSmsCodeLeftTime'] = null;
		Yii::app()->session['resetPasswordData'] = null;
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
}