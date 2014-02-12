<?php

/**
 * Class ChangeSmsAuthSettingForm
 */
class ChangeSmsAuthSettingForm extends ClientFullForm
{

	public $sms_auth_enabled;

	/**
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('sms_auth_enabled', 'required'),
		);
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'sms_auth_enabled' => 'Двухфакторная аутентификация'
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'sms_auth_enabled'
		);
	}
}

