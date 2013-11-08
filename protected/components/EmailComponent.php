<?php
/**
 * Class EmailComponent
 */
class EmailComponent extends CComponent
{

	public function init()
	{

	}

	/**
	 * Отправить email
	 *
	 * @param string $sEmail
	 * @param string $sSubject
	 * @param string $sMessage
	 * @param null   $sFrom
	 */
	public static function sendEmail($sEmail, $sSubject, $sMessage, $sFrom = null)
	{
		$headers = array(
			'MIME-Version: 1.0',
			'Content-type: text/plain; charset=UTF-8',
			'From: '.$sFrom,
		);

		$sSubject = '=?UTF-8?B?' . base64_encode($sSubject) . '?=';

		mail($sEmail, $sSubject, $sMessage, implode("\r\n", $headers));
	}
}
