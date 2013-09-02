<?php


/**
 *
 * @example
$oSmsGate = SmsGateSender::getInstance();
$oSmsGate->send("7$to", $message, $translit, $time, $id, $format, $sender);
 */
class SmsGateSender
{

	private static $_init = null;
	private $_external_url = 'https://www.stramedia.ru/modules/send_sms.php';
	private $_external_user = 'kreddy';
	private $_external_pwd = 'Ht2411s';

	/**
	 * This static function, recommend first run
	 *
	 * @return object
	 */

	public static function getInstance()
	{
		if (self::$_init === null) {
			self::$_init = new self();
		}

		return self::$_init;
	}

	/**
	 * Функция отправки SMS
	 *
	 * @param string $sPhone  - номер телефона с кодом (семеркой)
	 * @param string $message - отправляемое сообщение
	 *
	 * @return boolean
	 */
	public function send($sPhone, $message)
	{
		/*$aPostQuery = http_build_query(
			array(
				'username' => $this->_external_user,
				'password' => $this->_external_pwd,
				'to'       => $sPhone,
				'from'     => 'KREDDY',
				'coding'   => 2,
				'text'     => $message,
				'priority' => 0,
				'mclass'   => 1,
				'dlrmask'  => 31
			)
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->_external_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $aPostQuery);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$sResult = trim(curl_exec($ch));*/

		//Logger::write('sms_gate_send', $sPhone . '/' . $message . '/' . $sResult, 0);

		return true;
	}

}
