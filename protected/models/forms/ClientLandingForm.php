<?php

/**
 * Class ClientFastRegForm
 */
class ClientLandingForm extends ClientFullForm
{
	/**
	 * @return array
	 */
	public function rules()
	{
		$aRequired = array(
			'first_name',
			'last_name',
			'third_name',
			'email',
			'agree',
			'phone',
			'sex'
		);

		$aRulesFields = array(
			'first_name',
			'last_name',
			'third_name',
			'email',
			'phone',
			'agree',
			'sex'
		);

		$aRules = $this->getRulesByFields(
			$aRulesFields,
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
			'email',
			'agree',
			'phone',
			'sex'
		);
	}
}
