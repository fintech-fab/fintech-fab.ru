<?php

/**
 * Class ClientPersonalDataContinueForm
 */
class ClientPersonalDataContinueForm extends ClientFullForm
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
			),
			$aRequired
		);

		$aRules[] = array('phone', 'safe');

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


	/**
	 * Эти параметры нужны только для загрузку их в форму,
	 * но нельзя позволять их из формы получить и потом сохранить в БД
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		$aAttributes = parent::getAttributes();
		unset($aAttributes['phone']);
		unset($aAttributes['email']);
		unset($aAttributes['last_name']);
		unset($aAttributes['first_name']);
		unset($aAttributes['third_name']);

		return $aAttributes;
	}
}
