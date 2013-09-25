<?php
/**
 * Class ClientConfirmPhoneViaSMSForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientConfirmPhoneViaSMSForm extends ClientCreateFormAbstract
{
	/**
	 * @var boolean заполненность формы
	 */
	public $sms_code;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array();

		$aRules[] = array('sms_code', 'required', 'message' => 'Поле обязательно к заполнению');
		$aRules[] = array('sms_code', 'match', 'message' => 'SMS-код состоит из '.SiteParams::C_SMSCODE_LENGTH.' цифр',
			'pattern' => '/^\d{'.SiteParams::C_SMSCODE_LENGTH.'}$/');

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array('sms_code' => 'Код подтверждения из SMS');

	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'sms_code'
		);
	}
}
