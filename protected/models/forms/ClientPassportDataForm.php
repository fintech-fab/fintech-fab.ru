<?php
/**
 * Class ClientPassportDataForm
 */
class ClientPassportDataForm extends ClientFullForm
{
	/**
	 * Используется для заполнения серии и номера паспорта в маскированное поле
	 * при использовании setAttributes разделяется на 2 отдельных поля, соответствующих формату родительского класса,
	 * либо наоборот создается из 2 отдельных полей для вывода в форму, в зависимости от входных данных setAttributes
	 */
	public $passport_full_number;

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
			'passport_full_number',
		);

		$aMyRules =
			array(
				array(
					'passport_date', 'checkValidPassportDate', 'birthDate'            => 'birthday',
					                                           'message'              => 'Введите корректное значение даты выдачи паспорта',
					                                           'messageExpiredDate'   => 'Паспорт просрочен (проверьте корректность введенной даты рождения)',
					                                           'messageEmptyBirthday' => 'Сначала введите корректное значение даты рождения',
				),
				array(
					'passport_full_number', 'match', 'message' => 'Серия и номер паспорта должны состоять из ' . (SiteParams::C_PASSPORT_S_LENGTH + SiteParams::C_PASSPORT_N_LENGTH) . ' цифр и вводиться в формате 1234 / 567890', 'pattern' => '/^\d{' . (SiteParams::C_PASSPORT_S_LENGTH + SiteParams::C_PASSPORT_N_LENGTH) . '}$/'
				),
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

				'birthday'
			),
			$aRequired
		), $aMyRules);

		return $aRules;
	}

	/**
	 * После загрузки данных в модель объединяем номер паспорта из серии и номера в одно поле
	 * либо наоборот разделяем, в зависимости от входных данных
	 *
	 * @param array $values
	 */
	public function setAttributes($values)
	{
		parent::setAttributes($values);

		//если передан только full_number, разделяем его на 2 поля и обнуляем
		if (!empty($this->passport_full_number) && empty($this->passport_number) && empty($this->passport_series)) {
			$this->passport_full_number = preg_replace('/[^\d]/', '', $this->passport_full_number);
			$this->passport_series = substr($this->passport_full_number, 0, 4);
			$this->passport_number = substr($this->passport_full_number, 4, 6);
		} elseif (empty($this->passport_full_number) && !empty($this->passport_number) && !empty($this->passport_series)) {
			//если пустой full_number, но переданы два отдельных поля, объединяем их
			$this->passport_full_number = $this->passport_series . $this->passport_number;
		}

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
			'passport_full_number',

			//обязательно требуется для валидации, берется из информации предыдущих форм
			'birthday',
		);
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(
				'passport_full_number' => 'Серия/номер паспорта',
			)
		);
	}
}
