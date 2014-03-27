<?php

/**
 * Class ClientPersonalDataForm
 */
class ClientPersonalDataForm extends ClientFullForm
{
	/**
	 * @return array
	 */
	public function rules()
	{
		// всегда обязательные поля
		$aRequired = array(
			'first_name',
			'last_name',
			'third_name',
			'email',
			'sex',
			'agree',

			'birthday',
			'phone',
		);

		$aRules = $this->getRulesByFields(
			array(
				'first_name',
				'last_name',
				'third_name',
				'email',
				'sex',
				'agree',

				'birthday',
				'phone',
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
			'first_name',
			'last_name',
			'third_name',

			'birthday',

			'phone',
			'email',

			'sex',
			'agree',
		);
	}
}
