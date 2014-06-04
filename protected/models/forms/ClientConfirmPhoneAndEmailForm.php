<?php

/**
 * Class ClientConfirmPhoneAndEmailForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientConfirmPhoneAndEmailForm extends ClientCreateFormAbstract
{
	/**
	 * @var boolean заполненность формы
	 */
	public $sms_code;
	public $email_code;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array();

		$aRules[] = array('sms_code, email_code', 'required', 'message' => 'Поле обязательно к заполнению');
		$aRules[] = array(
			'sms_code', 'match', 'message' => 'SMS-код состоит из ' . SiteParams::C_SMS_CODE_LENGTH . ' цифр',
			                     'pattern' => '/^\d{' . SiteParams::C_SMS_CODE_LENGTH . '}$/'
		);
		$aRules[] = array(
			'email_code', 'match', 'message' => 'Проверочный код состоит из ' . SiteParams::C_EMAIL_CODE_LENGTH . ' цифр',
			                       'pattern' => '/^\d{' . SiteParams::C_EMAIL_CODE_LENGTH . '}$/'
		);

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'sms_code'   => 'Код подтверждения из SMS',
			'email_code' => 'Код подтверждения из e-mail',
		);

	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'sms_code',
			'email_code',
		);
	}
}
