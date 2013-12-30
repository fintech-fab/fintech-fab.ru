<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 * @method FormFieldValidateBehavior asa()
 */
class AccountLoginForm extends CFormModel
{
	public $username;
	public $password;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// password needs to be authenticated
			array('password', 'authenticate'),
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
			'password' => 'Пароль',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$this->_identity = new UserIdentity($this->username, $this->password);
			if (!$this->_identity->authenticate()) {
				if ($this->_identity->errorCode == UserIdentity::ERROR_TRIES_EXCEED) {
					$this->addError('password', UserIdentity::ERROR_MESSAGE_TRIES_EXCEED);
				} else {
					$this->addError('password', 'Неверный номер или пароль.');
				}
			}
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 *
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if ($this->_identity === null) {
			$this->_identity = new UserIdentity($this->username, $this->password);
			$this->_identity->authenticate();
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
