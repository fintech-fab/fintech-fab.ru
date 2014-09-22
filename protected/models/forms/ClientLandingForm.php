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
			'birthday',
			'email',
			'agree',
			'phone',
			'sex'
		);

		$aRulesFields = array(
			'first_name',
			'last_name',
			'third_name',
			'birthday',
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
			'birthday',
			'sex'
		);
	}

	/**
	 * @param $aBirthday
	 */
	public function setBirthdayFromParts($aBirthday)
	{

		if (isset($aBirthday['day']) && isset($aBirthday['month']) && isset($aBirthday['year'])) {
			$this->birthday = sprintf('%02s.%02s.%s', $aBirthday['day'], $aBirthday['month'], $aBirthday['year']);
		}
	}

}
