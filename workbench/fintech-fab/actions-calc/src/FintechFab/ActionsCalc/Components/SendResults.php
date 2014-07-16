<?php

namespace FintechFab\ActionsCalc\Components;


use Log;
use Queue;

class SendResults
{

	public static function makeCurl($url, $signalSid)
	{
		$postData = array('signalSid' => $signalSid);

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
		Queue::connection('ff-actions-calc')->push('FintechFab\ActionsCalc\Queue\QueueHandler', array(
			'url'       => $queue,
			'signalSid' => $signalSid,
		));

		Log::info('Результат поставлен в очередь, класс для выполнения FintechFab\\ActionsCalc\\Queue\\QueueHandler');
	}

}