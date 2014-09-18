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
	public $sendSmsCode;

	public $smsResend;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array();

		$aRules[] = array('smsCode', 'required', 'message' => 'Поле обязательно к заполнению', 'on' => 'codeRequired');
		$aRules[] = array('smsCode', 'safe', 'on' => 'sendRequired');

		$aRules[] = array('sendSmsCode', 'required', 'requiredValue' => 1, 'on' => 'sendRequired');
		$aRules[] = array('sendSmsCode, smsResend', 'safe', 'on' => 'codeRequired');

		$aRules[] = array('smsResend', 'safe');

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array('smsCode' => 'Код из SMS');
	}

	public function setAttributes($values, $safeOnly = true)
	{
		parent::setAttributes($values, $safeOnly);

		if ($this->sendSmsCode == 0) {
			$this->scenario = 'codeRequired';
		}
	}
}
