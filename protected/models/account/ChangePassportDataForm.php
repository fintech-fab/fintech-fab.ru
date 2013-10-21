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
	public $statement;

	/**
	 * @return array
	 */
	public function rules()
	{
		// всегда обязательные поля
		$aRequired = array(
			'old_passport_series',
			'old_passport_number',
			'statement',

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
				array('statement', 'numerical', 'integerOnly' => true, 'min' => 1, 'tooSmall' => 'Номер заявления должен быть числом.')
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
				'statement'           => 'Номер заявления о смене паспорта'

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

}

