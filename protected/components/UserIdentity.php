<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 *
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		//если username - имя админа, то логинимся своими средствами
		if ($this->username === Yii::app()->params['adminName']) {
			$users = array(
				// username => password
				Yii::app()->params['adminName'] => Yii::app()->params['adminPassword'],
			);


			if (!isset($users[$this->username])) {
				$this->errorCode = self::ERROR_USERNAME_INVALID;
			} elseif ($users[$this->username] !== $this->password) {
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
			} else {
				$this->errorCode = self::ERROR_NONE;
			}
		} else { //иначе логинимся через API
			$sPhone = $this->username;
			$sPassword = $this->password;
			$bAuth = Yii::app()->adminKreddyApi->getAuth($sPhone, $sPassword);

			if ($bAuth) {
				$this->errorCode = self::ERROR_NONE;
			} else {
				$this->errorCode = self::ERROR_USERNAME_INVALID;
			}
		}

		return !$this->errorCode;
	}

	/**
	 * Функция для автоматического логина после заполнения анкеты
	 *
	 * @return bool
	 */

	public function autoAuthenticate()
	{
		//проверяем, авторизован ли клиент в API (был ли получен и установлен токен при создании клиента)
		$bAuth = Yii::app()->adminKreddyApi->getIsAuth();
		if ($bAuth) {
			$this->errorCode = self::ERROR_NONE;
		} else {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}

		return !$this->errorCode;
	}
}