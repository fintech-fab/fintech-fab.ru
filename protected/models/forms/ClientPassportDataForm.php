<?php
/**
 * Class ClientPassportDataForm
 */
class ClientPassportDataForm extends ClientFullForm
{
	/**
	 * @return array
	 */
	public function rules()
	{
		// всегда обязательные поля
		$aRequired = array(
			'passport_number',
			'passport_series',
			'passport_date',
			'passport_code',
			'passport_issued',
			'document',
			'document_number',

			'birthday',
		);

		$aMyRules =
			array(
				array(
					'passport_date', 'checkValidPassportDate', 'birthDate'            => 'birthday',
					                                           'message'              => 'Введите корректное значение даты выдачи паспорта',
					                                           'messageExpiredDate'   => 'Паспорт просрочен (проверьте корректность введенной даты рождения)',
					                                           'messageEmptyBirthday' => 'Сначала введите корректное значение даты рождения',
				)
			);

		$aRules = array_merge($this->getRulesByFields(
			array(
				'passport_number',
				'passport_series',
				'passport_date',
				'passport_code',
				'passport_issued',
				'document',
				'document_number',
			),
			$aRequired
		), $aMyRules);

		return $aRules;
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'passport_number',
			'passport_series',
			'passport_date',
			'passport_code',
			'passport_issued',
			'document',
			'document_number',

			//обязательно требуется для валидации, берется из информации предыдущих форм
			'birthday',
		);
	}
}
