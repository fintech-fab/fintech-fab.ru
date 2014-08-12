<?php

namespace FintechFab\ActionsCalc\Components;

use Log;
use Queue;
use FintechFab\ActionsCalc\Queue\SendHttp;
use FintechFab\ActionsCalc\Queue\ToForeign;

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
	 * Response with results to queue.
	 *
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
	 * Result to queue.
	 * Listening and attempting to put to foreign queue.
	 * Because if putting to foreign queue fails, we'l have fatal.
	 *
	 * @param $foreign_job
	 * @param $foreign_queue
	 * @param $signalSid
	 */
	public function resultToQueue($foreign_job, $foreign_queue, $signalSid)
	{
		Queue::connection('ff-actions-calc-result')->push(ToForeign::class, [
			'sSignalSid'    => $signalSid,
			'sForeignQueue' => $foreign_queue,
			'sForeignJob'   => $foreign_job,
			'sResultHash'   => $this->_sResultHash,
		]);

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