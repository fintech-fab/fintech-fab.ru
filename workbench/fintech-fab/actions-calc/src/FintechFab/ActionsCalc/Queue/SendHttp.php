<?php

namespace FintechFab\ActionsCalc\Queue;

use FintechFab\ActionsCalc\Models\Signal;
use Log;
use Illuminate\Queue\Jobs\Job;

/**
 * Class SendHttp
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class SendHttp
{

	/**
	 * @param Job   $job
	 * @param array $aData
	 *
	 * @var string  $aData ['sUrl']
	 * @var int     $aData ['iSignalId']
	 * @var string  $aData ['sResultHash']
	 */
	public function fire($job, $aData)
	{
		Log::info('Queue SendHttp fire -', $aData);

		/**
		 * @var Signal $oSignal
		 */
		$oSignal = Signal::find($aData['iSignalId']);
		if (is_null($oSignal)) {
			$job->delete();
		}
		$sSignalSid = $oSignal->signal_sid;

		if ($this->makeCurlResponse($aData['sUrl'], $sSignalSid) && $job->attempts() <= 50) {
			$job->delete();
		}

		$job->release(5); // 60
	}

	/**
	 * @param $sUrl
	 * @param $sSignalSid
	 *
	 * @return bool
	 */
	private function makeCurlResponse($sUrl, $sSignalSid)
	{

		$aPostData = [
			'signal_sid' => $sSignalSid,
		];

		$sh = curl_init($sUrl);
		curl_setopt($sh, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($sh, CURLOPT_POST, true);
		curl_setopt($sh, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($sh, CURLOPT_POSTFIELDS, $aPostData);

		$httpResponse = curl_exec($sh);
		$httpResponseError = curl_error($sh);

		if (curl_errno($sh)) {
			Log::info("CURL error: $httpResponseError");

			return false;
		}

		Log::info("CURL success:", [$httpResponse]);
		curl_close($sh);

		return true;
	}
} 