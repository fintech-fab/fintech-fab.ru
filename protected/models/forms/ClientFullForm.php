<?php

/**
 * Class ClientFullForm
 *
 * @method FormFieldValidateBehavior asa()
 *
 */
class ClientFullForm extends ClientCreateFormAbstract
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

			'birthday',

			'phone',
			'email',

			'sex',

			'passport_series',
			'passport_number',
			'passport_date',
			'passport_issued',
			'passport_code',

			'document',
			'document_number',

			'address_reg_region',
			'address_reg_city',
			'address_reg_address',

			'relatives_one_fio',
			'relatives_one_phone',

			'status',
			'loan_purpose',
			'have_past_credit',

			'numeric_code',
			'agree',
			'secret_question',
			'secret_answer',
			'password',
			'password_repeat',
		);

		$aMyRules =
			array(
				array('phone', 'compare', 'operator' => '!=', 'compareAttribute' => 'relatives_one_phone', 'message' => 'Номер не должен совпадать с телефоном контактного лица!'),
				array('phone', 'compare', 'operator' => '!=', 'compareAttribute' => 'friends_phone', 'message' => 'Номер не должен совпадать с телефоном дополнительного контакта!'),
			);
		$aRules = array_merge($this->getRulesByFields(
			array(
				'first_name',
				'last_name',
				'third_name',

				'birthday',

				'phone',
				'email',

				'sex',

				'passport_series',
				'passport_number',
				'passport_date',
				'passport_issued',
				'passport_code',

				'document',
				'document_number',

				'address_reg_region',
				'address_reg_city',
				'address_reg_address',

				'address_reg_as_res',

				'address_res_region',
				'address_res_city',
				'address_res_address',

				'relatives_one_fio',
				'relatives_one_phone',

				'friends_fio',
				'friends_phone',

				'status',
				'income_source',
				'educational_institution_name',
				'educational_institution_phone',
				'job_company',
				'job_position',
				'job_phone',
				'job_time',
				'job_monthly_income',
				'job_monthly_outcome',

				'numeric_code',
				'secret_question',
				'secret_answer',
				'password',
				'password_repeat',
				'loan_purpose',
				'have_past_credit',
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
				'relatives_one_fio'             => 'ФИО',
				'relatives_one_phone'           => 'Телефон',

				'friends_fio'                   => 'ФИО',
				'friends_phone'                 => 'Телефон',

				'agree'                         => 'Я подтверждаю достоверность введенных данных и даю согласие на их обработку (<a onclick="return doOpenModalFrame(\'/pages/viewPartial/usloviya\', \'Согласие на обработку персональных данных. Условия обслуживания и передачи информации.\')"  href="#">подробная информация</a>)',

				'passport_number'               => 'Серия/номер',
				'passport_series'               => 'Серия/номер',

				'secret_question'               => 'Секретный вопрос',
				'secret_answer'                 => 'Ответ на секретный вопрос',

				'product'                       => 'Продукт',
				'address_reg_as_res'            => 'фактический адрес совпадает с пропиской',

				'status'                        => 'Статус',
				'educational_institution_name'  => 'Название учебного заведения',
				'educational_institution_phone' => 'Телефон учебного заведения',
				'job_monthly_income'            => 'Среднемесячный доход',
				'job_monthly_outcome'           => 'Среднемесячный расход',
				'income_source'                 => 'Источник дохода',

				'password'                      => 'Пароль для входа в личный кабинет',
				'password_repeat'               => 'Подтверждение пароля',

				'loan_purpose'                  => 'Цель получения денег',
				'have_past_credit'              => 'Наличие кредитов и займов в прошлом',

			)
		);
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

			'passport_series',
			'passport_number',
			'passport_date',
			'passport_issued',
			'passport_code',

			'document',
			'document_number',

			'address_reg_region',
			'address_reg_city',
			'address_reg_address',

			'address_reg_as_res',

			'address_res_region',
			'address_res_city',
			'address_res_address',

			'relatives_one_fio',
			'relatives_one_phone',

			'friends_fio',
			'friends_phone',

			'status',
			'educational_institution_name',
			'educational_institution_phone',
			'job_company',
			'job_position',
			'job_phone',
			'job_time',
			'job_monthly_income',
			'job_monthly_outcome',
			'income_source',

			'loan_purpose',
			'have_past_credit',

			'numeric_code',
			'secret_question',
			'secret_answer',
			'agree',
			'password',
			'password_repeat',
		);
	}

}

