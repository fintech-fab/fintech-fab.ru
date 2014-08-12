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
	private $_sResultHash;

	function __construct()
	{
		$this->_sResultHash = $this->generateResultHash();
	}

	/**
	 * @param $url
	 * @param $iSignalId
	 */
	public function sendHttpToQueue($url, $iSignalId)
	{
		Log::info("Queue: sendHttpToQueue, url:$url, iSignalId:$iSignalId");

		Queue::connection('ff-actions-calc')->push(SendHttp::class, [
			'sUrl'        => $url,
			'iSignalId'   => $iSignalId,
			'sResultHash' => $this->_sResultHash,
		]);

	}

	/**
	 * @param $queue
	 * @param $signalSid
	 */
	public function resultToQueue($queue, $signalSid)
	{
		Queue::connection('ff-actions-calc-result')->push($queue, array(
			'sQueue'      => $queue,
			'sSignalSid'  => $signalSid,
			'sResultHash' => $this->_sResultHash,
		));

		// TODO: result to queue to put it in external queue XD
		Log::info('Queue: resultToQueue,');
	}

	/**
	 * @return string
	 */
	public function getResultHash()
	{
		return $this->_sResultHash;
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