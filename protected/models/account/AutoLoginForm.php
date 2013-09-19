<?php

/**
 * Модель предназначена для автоматического входа клиента в личный кабинет после заполнения анкеты
 *
 * @method FormFieldValidateBehavior asa()
 */
class AutoLoginForm extends CFormModel
{
	public $username;

	private $_identity;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username', 'required'),
			// password needs to be authenticated
			array('username', 'checkValidClientPhone', 'message' => 'Номер телефона должен содержать десять цифр'),
			array('username', 'match', 'message' => 'Номер телефона должен начинаться на +7 9', 'pattern' => '/^9\d{' . (SiteParams::C_PHONE_LENGTH - 1) . '}$/')
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username' => 'Номер телефона',
		);
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 *
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if ($this->_identity === null) {
			$this->_identity = new UserIdentity($this->username, '');
			$this->_identity->autoAuthenticate();
		}
		if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
			$duration = 0;
			Yii::app()->user->login($this->_identity, $duration);

			return true;
		} else {
			return false;
		}
	}

	/**
	 * @return bool
	 */
	public function beforeValidate()
	{
		if ($this->username) {
			//очистка данных
			$this->username = ltrim($this->username, '+ ');
			$this->username = preg_replace('/[^\d]/', '', $this->username);

			// убираем лишний знак слева (8-ка или 7-ка)
			if (strlen($this->username) == 11) {
				$this->username = substr($this->username, 1, 10);
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
