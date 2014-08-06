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

		Log::info('Задача для отправки по http поставлена в очередь');
	}

	public function sendQueue($queue, $signalSid)
	{
		Queue::connection('ff-actions-calc-result')->push($queue, array(
			'class_queue' => $queue,
			'signalSid' => $signalSid,
		));

		Log::info('Результат отправлен в очередь, класс для выполнения FintechFab\\ActionsCalc\\Queue\\QueueHandler');
	}

}