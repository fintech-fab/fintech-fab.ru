<?php
/**
 * Class SMSCodeForm
 */
class SMSCodeForm extends CFormModel
{
	/**
	 * @var boolean заполненность формы
	 */
	public $smsCode;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array();

		$aRules[] = array('smsCode', 'required', 'message' => 'Поле обязательно к заполнению');

		//$aRules[] = array('smsPassword', 'match', 'message' => 'SMS-код состоит из '.SiteParams::C_SMSCODE_LENGTH.' цифр',
		//	'pattern' => '/^\d{'.SiteParams::C_SMSCODE_LENGTH.'}$/');

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array('smsCode' => 'Код из SMS');
	}
}
