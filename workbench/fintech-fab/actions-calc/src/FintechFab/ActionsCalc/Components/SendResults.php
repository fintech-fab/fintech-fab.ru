<?php

namespace FintechFab\ActionsCalc\Components;


use Log;
use Queue;

class SendResults
{

	public function sendHttp($url, $signalId)
	{
		Queue::connection('ff-actions-calc')->push('FintechFab\ActionsCalc\Queue\SendHttp', array(
			'url'      => $url,
			'signalId' => $signalId,
		));

		Log::info('Результат для отправки по http поставлен в очередь');
	}

	public function sendQueue($queue, $signalSid)
	{
		Queue::connection('ff-actions-calc-result')->push($queue, array(
			'url'       => $queue,
			'signalSid' => $signalSid,
		));

		Log::info('Результат поставлен в очередь, класс для выполнения FintechFab\\ActionsCalc\\Queue\\QueueHandler');
	}

}