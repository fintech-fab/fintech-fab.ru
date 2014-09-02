<?php

/**
 * Class ChangeEmailForm
 */
class ChangeEmailForm extends ClientFullForm
{

	/**
	 * @return array
	 */
	public function rules()
	{
		// всегда обязательные поля
		$aRequired = array(
			'email',
		);
		$aMyRules = array();
		$aRules = array_merge($this->getRulesByFields(
			array(
				'email'
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
				'email' => 'Введи новый адрес электронной почты'

			)
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'email',

		);
	}
}

