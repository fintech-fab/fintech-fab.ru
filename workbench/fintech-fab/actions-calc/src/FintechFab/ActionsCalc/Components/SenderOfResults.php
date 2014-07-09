<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.07.14
 * Time: 14:35
 */

namespace FintechFab\ActionsCalc\Components;


use Log;

class SenderOfResults
{

	public static function makeCurl($url, $signalSid)
	{
		$postData = self::prepareData($signalSid);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

		$httpResponse = curl_exec($ch);
		$httpError = curl_error($ch);

		if (!$httpResponse || $httpError) {
			Log::info("Ошибка CURL. httpResponse = $httpResponse , httpError = $httpError");
		} else {
			Log::info("CURL успешно отработал.  httpResponse = $httpResponse , httpError = $httpError");
		}

	}

	public static function sendQueue($queue, $signalSid)
	{
		//TODO
		//dd($queue, $signalSid);
	}

	private static function prepareData($signalSid)
	{
		return array('signalSid' => $signalSid);
	}
}