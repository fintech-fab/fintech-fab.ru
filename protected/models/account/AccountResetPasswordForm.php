<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 * @method FormFieldValidateBehavior asa()
 */
class AccountResetPasswordForm extends CFormModel
{
	public $phone;
	public $smsCode;
	public $passport_series;
	public $passport_number;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('phone, passport_series, passport_number', 'required'),
			array('phone', 'checkValidClientPhone', 'message' => 'Номер телефона должен содержать десять цифр'),
			array(
				'phone', 'match', 'message' => 'Номер телефона должен начинаться на +7 9',
				                  'pattern' => '/^9\d{' . (SiteParams::C_PHONE_LENGTH - 1) . '}$/'
			),
			array('smsCode', 'required', 'message' => 'Поле обязательно к заполнению', 'on' => 'codeRequired'),
			array('passport_series', 'match', 'message' => 'Серия паспорта должна состоять из четырех цифр', 'pattern' => '/^\d{' . SiteParams::C_PASSPORT_S_LENGTH . '}$/'),
			array('passport_number', 'match', 'message' => 'Номер паспорта должен состоять из шести цифр', 'pattern' => '/^\d{' . SiteParams::C_PASSPORT_N_LENGTH . '}$/')
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'phone'   => 'Телефон',
			'passport_series'=>'Серия/номер паспорта',
			'passport_number'=>'Серия/номер паспорта',
			'smsCode' => 'Код',
		);
	}

	/**
	 * @return bool
	 */
	public function beforeValidate()
	{
		if ($this->phone) {
			//очистка данных
			$this->phone = ltrim($this->phone, '+ ');
			$this->phone = preg_replace('/[^\d]/', '', $this->phone);

			// убираем лишний знак слева (8-ка или 7-ка)
			if (strlen($this->phone) == 11) {
				$this->phone = substr($this->phone, 1, 10);
			}
		}

		return parent::beforeValidate();
	}

	/**
	 * проверка номера телефона
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidClientPhone($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkValidClientPhone($attribute, $param);
	}

	/**
	 * подключаем общий помощник по валидации разных данных
	 * @return array
	 */
	public function behaviors()
	{
		return array(
			'FormFieldValidateBehavior' => array(
				'class' => 'application.extensions.behaviors.FormFieldValidateBehavior',
			),
		);
	}
}
