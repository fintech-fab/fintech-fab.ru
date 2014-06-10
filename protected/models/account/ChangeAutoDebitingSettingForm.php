<?php

/**
 * Class ChangeSmsAuthSettingForm
 */
class ChangeAutoDebitingSettingForm extends ClientFullForm
{
	public $flag_enable_auto_debiting;

	/**
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('flag_enable_auto_debiting', 'required'),
		);
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'flag_enable_auto_debiting' => 'Автосписание'
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'flag_enable_auto_debiting'
		);
	}
}

