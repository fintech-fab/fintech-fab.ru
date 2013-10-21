<?php
/**
 * Class ChangePassportData
 *
 *
 */

class ChangePassportDataForm extends ClientFullForm
{

	public $old_passport_series;
	public $old_passport_number;
	public $passport_not_changed;
	public $statement;

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
		);

		$aMyRules =
			array(
				array('old_passport_series', 'match', 'message' => 'Серия паспорта должна состоять из четырех цифр', 'pattern' => '/^\d{' . SiteParams::C_PASSPORT_S_LENGTH . '}$/'),
				array('old_passport_number', 'match', 'message' => 'Номер паспорта должен состоять из шести цифр', 'pattern' => '/^\d{' . SiteParams::C_PASSPORT_N_LENGTH . '}$/'),
				array('statement', 'numerical', 'integerOnly' => true, 'min' => 1, 'tooSmall' => 'Номер заявления должен быть числом.'),
				array('old_passport_series', 'checkOldPassport','passport_not_changed'=>'passport_not_changed', 'message' => 'Необходимо заполнить поле "Серия паспорта"'),
				array('old_passport_number', 'checkOldPassport','passport_not_changed'=>'passport_not_changed', 'message' => 'Необходимо заполнить поле "Номер паспорта"'),
				array('statement', 'checkOldPassport', 'passport_not_changed'=>'passport_not_changed', 'message' => 'Необходимо заполнить поле "Номер заявления о смене паспорта"'),
				array('passport_not_changed', 'numerical'),
			);
		$aRules = array_merge($this->getRulesByFields(
			array(
				'first_name',
				'last_name',
				'third_name',

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

				/*'address_reg_as_res',

				'address_res_region',
				'address_res_city',
				'address_res_address',*/
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

				'complete'            => 'Я подтверждаю достоверность введенных данных и даю согласие на их обработку (<a data-toggle="modal" href="#privacy">подробная информация</a>)',

				'passport_number'     => 'Серия/номер',

				'old_passport_number' => 'Серия/номер',
				'old_passport_series' => 'Серия/номер',
				'statement'           => 'Номер заявления о смене паспорта',
				'passport_not_changed' => 'Паспорт не менялся на новый'

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

			'old_passport_series',
			'old_passport_number',
			'statement',
			'passport_not_changed',

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

			/*'address_reg_as_res',

			'address_res_region',
			'address_res_city',
			'address_res_address',*/
		);
	}

	/**
	 * @param $attribute
	 * @param $param
	 */

	public function checkOldPassport($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkOldPassport($attribute, $param);
	}
}

