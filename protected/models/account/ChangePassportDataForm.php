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
	public $change_passport_ticket;
	public $change_passport_reason;
	public $change_passport_department;
	public $confirm;

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



				array('old_passport_series', 'checkOldPassport','passport_not_changed'=>'passport_not_changed', 'message' => 'Необходимо указать серию паспорта'),
				array('old_passport_number', 'checkOldPassport','passport_not_changed'=>'passport_not_changed', 'message' => 'Необходимо указать номер паспорта'),
				array('change_passport_reason', 'checkOldPassport','passport_not_changed'=>'passport_not_changed', 'message' => 'Необходимо указать номер паспорта'),

				array('change_passport_reason','in','range'=>array_keys(Dictionaries::$aChangePassportReasons)),

				array('change_passport_ticket', 'numerical', 'integerOnly' => true, 'min' => 1, 'tooSmall' => 'Номер заявления должен быть числом'),

				//TODO сделать отдельное правило проверки
				array('change_passport_ticket', 'checkOldPassport', 'passport_not_changed'=>'passport_not_changed', 'message' => 'Необходимо указать номер талона-уведомления, полученного при подаче заявления об утере или краже паспорта'),
				array('change_passport_department', 'checkOldPassport', 'passport_not_changed'=>'passport_not_changed', 'message' => 'Необходимо указать наименование и адрес отделения МВД, принявшего заявление об утере или краже паспорта'),

				array('change_passport_department','checkValidRus','message'=>'Поле может содержать только русские буквы, цифры, пробелы и знаки препинания'),

				array('passport_not_changed', 'numerical'),

				array('confirm', 'required', 'requiredValue' => 1, 'message' => 'Необходимо подтвердить свое согласие на обработку данных'),
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

				'confirm'            => 'Я подтверждаю достоверность введенных данных и даю согласие на их обработку (<a data-toggle="modal" href="#privacy">подробная информация</a>)',

				'passport_number'     => 'Серия/номер',

				'old_passport_number' => 'Серия/номер',
				'old_passport_series' => 'Серия/номер',
				'change_passport_ticket'           => 'Номер талона-уведомления',
				'passport_not_changed' => 'Паспорт не менялся на новый',
				'change_passport_reason'=>'Причина смены паспорта',
				'change_passport_department'=>'Отделеление МВД России, принявшее заявление'

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
			'change_passport_ticket',
			'change_passport_department',
			'passport_not_changed',
			'change_passport_reason',

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
			'confirm'

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

	/**
	 * проверка что содержит только русские буквы и знаки препинания
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidRus($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkValidRus($attribute, $param);
	}
}

