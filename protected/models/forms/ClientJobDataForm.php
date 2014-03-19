<?php

/**
 * Class ClientJobDataForm
 */
class ClientJobDataForm extends ClientFullForm
{
	/**
	 * @return array
	 */
	public function rules()
	{
		// всегда обязательные поля
		$aRequired = array(
			'status',
			'relatives_one_fio',
			'relatives_one_phone',
			'loan_purpose',
			'have_past_credit',
		);

		$aRules = $this->getRulesByFields(
			array(
				'status',
				'relatives_one_fio',
				'relatives_one_phone',
				'loan_purpose',
				'have_past_credit',

				//обязательно требуется для валидации, берется из информации предыдущих форм
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
			'status',
			'relatives_one_fio',
			'relatives_one_phone',
			'loan_purpose',
			'have_past_credit',
			//обязательно требуется для валидации, берется из информации предыдущих форм
			'phone',
		);
	}
}
