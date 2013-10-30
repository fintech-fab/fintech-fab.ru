<?php
/**
 * Class ChangePasswordForm
 */

class ChangePasswordForm extends ClientFullForm
{

	public $old_password;

	/**
	 * @return array
	 */
	public function rules()
	{
		// всегда обязательные поля
		$aRequired = array(
			'old_password',
			'password',
			'password_repeat',
		);
		$aMyRules =
			array(
			);
		$aRules = array_merge($this->getRulesByFields(
			array(
				'old_password',
				'password',
				'password_repeat',
			),
			$aRequired
		), $aMyRules);
		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(
				'old_password'=>'Старый пароль',
				'password'=>'Новый пароль',
				'password_repeat'=>'Повторите новый пароль'

			)
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'old_password',
			'password',
			'password_repeat',

		);
	}
}

