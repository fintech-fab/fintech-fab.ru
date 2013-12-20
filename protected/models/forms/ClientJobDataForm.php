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
			'job_monthly_income',
			'job_monthly_outcome',

			'status',
		);

		$aRules = $this->getRulesByFields(
			array(
				'job_company',
				'job_position',
				'job_phone',
				'job_time',
				'job_monthly_income',
				'job_monthly_outcome',
				'income_source',
				'educational_institution_name',
				'educational_institution_phone',
				'status',

				'friends_phone',
				'relatives_one_phone',
				'phone',
				'status'
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
			'job_company',
			'job_position',
			'job_phone',
			'job_time',
			'job_monthly_income',
			'job_monthly_outcome',
			'income_source',
			'educational_institution_name',
			'educational_institution_phone',
			'status',

			//обязательно требуется для валидации, берется из информации предыдущих форм
			'friends_phone',
			'phone',
			'relatives_one_phone',
			'status'
		);
	}
}
