<?php
/**
 * Class ClientSecretDataForm
 */
class ClientSecretDataForm extends ClientFullForm
{
	/**
	 * @return array
	 */
	public function rules()
	{
		// всегда обязательные поля
		$aRequired = array(
			'numeric_code',
			'secret_question',
			'secret_answer',
			'password',
			'password_repeat',
		);

		$aRules = $this->getRulesByFields(
			array(
				'numeric_code',
				'secret_question',
				'secret_answer',
				'password',
				'password_repeat',
			),
			$aRequired
		);

		return $aRules;
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'numeric_code',
			'secret_question',
			'secret_answer',
			'password',
			'password_repeat',
		);
	}
}
