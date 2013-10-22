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
	public $passport_change_ticket;
	public $passport_change_reason;
	public $passport_change_department;
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
		//TODO написать тесты на весь этот код
		//TODO проверить все правила на вероятность XSS
		$aMyRules =
			array(
				array('old_passport_series', 'match', 'message' => 'Серия паспорта должна состоять из четырех цифр', 'pattern' => '/^\d{' . SiteParams::C_PASSPORT_S_LENGTH . '}$/'),
				array('old_passport_number', 'match', 'message' => 'Номер паспорта должен состоять из шести цифр', 'pattern' => '/^\d{' . SiteParams::C_PASSPORT_N_LENGTH . '}$/'),



				array('old_passport_series', 'checkOldPassport','passport_not_changed'=>'passport_not_changed', 'message' => 'Необходимо указать серию паспорта'),
				array('old_passport_number', 'checkOldPassport','passport_not_changed'=>'passport_not_changed', 'message' => 'Необходимо указать номер паспорта'),
				array('passport_change_reason', 'checkOldPassport','passport_not_changed'=>'passport_not_changed', 'message' => 'Необходимо указать номер паспорта'),

				array('passport_change_reason','in','range'=>array_keys(Dictionaries::$aChangePassportReasons)),

				array('passport_change_ticket', 'numerical', 'integerOnly' => true, 'min' => 1, 'tooSmall' => 'Номер заявления должен быть числом'),


				array('passport_change_ticket', 'checkPassportLostStolen', 'passport_change_reason'=>'passport_change_reason', 'message' => 'Необходимо указать номер талона-уведомления, полученного при подаче заявления об утере или краже паспорта'),
				array('passport_change_department', 'checkPassportLostStolen', 'passport_change_reason'=>'passport_change_reason', 'message' => 'Необходимо указать наименование и адрес отделения МВД, принявшего заявление об утере или краже паспорта'),

				array('passport_change_department','checkValidRus','message'=>'Поле может содержать только русские буквы, цифры, пробелы и знаки препинания'),

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
				'passport_change_ticket'           => 'Номер талона-уведомления',
				'passport_not_changed' => 'Паспорт не менялся на новый',
				'passport_change_reason'=>'Причина смены паспорта',
				'passport_change_department'=>'Отделеление МВД России, принявшее заявление'

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
			'passport_change_ticket',
			'passport_change_department',
			'passport_not_changed',
			'passport_change_reason',

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
	 * Проверка полей старого паспорта на required, если стоит чекбокс - то не required
	 *
	 * @param $attribute
	 * @param $param
	 */

	public function checkOldPassport($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkOldPassport($attribute, $param);
	}

	/**
	 * Проверка required дополнительных полей в случае если паспорт утерян или украден
	 *
	 * @param $attribute
	 * @param $param
	 */
	public function checkPassportLostStolen($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkPassportLostStolen($attribute, $param);
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

