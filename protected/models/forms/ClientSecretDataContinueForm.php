<?php

/**
 * Class ClientSecretDataContinueForm
 */
class ClientSecretDataContinueForm extends ClientFullForm
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
		);

		$aRules = $this->getRulesByFields(
			array(
				'numeric_code',
				'secret_question',
				'secret_answer',
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
		);
	}
}
