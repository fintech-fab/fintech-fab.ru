<?php

namespace FintechFab\ActionsCalc\Components;

use Log;
use Queue;
use FintechFab\ActionsCalc\Queue\SendHttp;

/**
 * Class ResultHandler
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class ResultHandler
{

	/**
	 * @param $url
	 * @param $iSignalId
	 */
	public function sendHttpToQueue($url, $iSignalId)
	{
		Log::info("Queue: sendHttpToQueue, url:$url, iSignalId:$iSignalId");

		$sResultHash = $this->generateResultHash();

		Queue::connection('ff-actions-calc')->push(SendHttp::class, [
			'sUrl'        => $url,
			'iSignalId'   => $iSignalId,
			'sResultHash' => $sResultHash,
		]);

	}

	/**
	 * Personal queue id for request or queue result
	 *
	 * @return string
	 */
	private function generateResultHash()
	{
		return uniqid('que_');
	}
}