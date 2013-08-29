<?php
/**
 * Class ClientConfirmPhoneViaSMSForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class SMSPasswordForm extends CFormModel
{
	/**
	 * @var boolean заполненность формы
	 */
	public $smsPassword;

	public function rules()
	{
		$aRules = array();

		$aRules[] = array('smsPassword', 'required', 'message' => 'Поле обязательно к заполнению');

		//$aRules[] = array('smsPassword', 'match', 'message' => 'SMS-код состоит из '.SiteParams::C_SMSCODE_LENGTH.' цифр',
		//	'pattern' => '/^\d{'.SiteParams::C_SMSCODE_LENGTH.'}$/');

		return $aRules;

	}

	public function attributeLabels()
	{
		return array('smsPassword' => 'Пароль из SMS');
	}
}
