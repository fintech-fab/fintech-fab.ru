<?php
/**
 * Created by JetBrains PhpStorm.
 * User: popov
 * Date: 28.08.13
 * Time: 12:02
 * To change this template use File | Settings | File Templates.
 */

class AdminKreddyApi extends CModel
{

	const ERROR_NONE = 0;
	const ERROR_AUTH = 2;
	const ERROR_TOKEN_EXPIRE = 5;

	private $token;

	public function attributeNames()
	{
		return array('token' => 'Token');
	}

	/**
	 * Constructor.
	 */

	public function __construct()
	{
		$this->init();
	}


	public function init()
	{
		$this->token = $this->getSessionToken();
		if (!empty($this->token)) {
			$this->renewClientToken();
		}
	}

	public function getAuth($sPhone, $sPassword)
	{
		//заглушка
		$aTokenData = array('code' => 1);
		if ($sPhone == "9154701913" && $sPassword == "159753") {
			$aTokenData = array('code' => self::ERROR_NONE, 'token' => '159753');
		}

		if ($aTokenData['code'] === self::ERROR_NONE) {
			$this->setSessionToken($aTokenData['token']);
			$this->token = $aTokenData['token'];

			return true;
		} else {
			return false;
		}
	}

	public function renewClientToken()
	{
		//заглушка
		//отсылаем текущий токен
		//получаем токен в ответ
		$aTokenData = array('code' => self::ERROR_NONE, 'token' => '159753');

		if ($aTokenData['code']) {
			$this->setSessionToken($aTokenData['token']);
			$this->token = $aTokenData['token'];

			return true;
		} else {
			return false;
		}
	}

	public function getClientData()
	{
		$aData = array('code' => self::ERROR_AUTH, 'first_name' => '', 'last_name' => '', 'third_name' => '', 'balance' => '');
		if (!empty($this->token)) {
			//тут типа запрос данных по токену
			$aGetData = $this->getData('base', $this->token);

			$aData = array_merge($aData, $aGetData);
		} else {
			$aData = false;
		}

		return $aData;
	}

	public function getClientName()
	{
		$sToken = $this->getSessionToken();
		$aData = array();
		if (!empty($sToken)) {
			//тут типа запрос данных по токену
			$aData = $this->getData('name', $sToken);
		} else {
			$aData = false;
		}

		return $aData;
	}

	private function getData($sType, $sToken)
	{
		$aData = array('code' => self::ERROR_AUTH);
		//тут curl запрашивает данные
		//заглушка

		switch ($sType) {
			case 'base':
				$aData = array('code' => self::ERROR_TOKEN_EXPIRE);
				break;
			case 'secure':
				$aData = array('');
				break;
		}

		if ($aData['code'] === self::ERROR_TOKEN_EXPIRE) {
			$this->renewClientToken();
			//тут перезапрос данных
			//заглушка
			switch ($sType) {
				case 'base':
					$aData = array('code' => self::ERROR_NONE, 'balance' => '-10000', 'first_name' => 'Василий', 'last_name' => 'Пупкин', 'third_name' => 'Иванович');
					break;
				case 'secure':
					$aData = array('');
					break;
			}
		}


		return $aData;
	}

	/**
	 * @return mixed
	 */

	private function getSessionToken()
	{
		return Yii::app()->session['akApi_token'];
	}

	/**
	 * @param $token
	 */

	private function setSessionToken($token)
	{
		Yii::app()->session['akApi_token'] = $token;
	}

}