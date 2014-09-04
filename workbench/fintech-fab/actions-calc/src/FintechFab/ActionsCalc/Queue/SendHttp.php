<?php

namespace FintechFab\ActionsCalc\Queue;

use FintechFab\ActionsCalc\Components\Registrator;
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
	public function fire(Job $job, $aData)
	{
		Log::info('Queue SendHttp fire -', $aData);

		/** @var Signal $oSignal */
		$oSignal = Signal::find((int)$aData['iSignalId']);
		if (is_null($oSignal)) {
			$job->delete();

			return;
		}
		$sSignalSid = $oSignal->signal_sid;

		// is request sent?
		if ($this->makeCurlRequest($aData['sUrl'], $sSignalSid)) {

			$aSignalAttributes = $oSignal->getAttributes();
			Registrator::registerSignal($aSignalAttributes, true, false, $aData['sResultHash']);

			$job->delete();
			// TODO: failed_jobs table.
			return;
		}

		// job failed?
		if ($job->attempts() > 50) {
			$job->delete();
			Log::info('Queue attempts exceeded.', $aData);

			return;
		}

		Log::info('Job release. Attempts: ' . $job->attempts(), $aData);
		$job->release(60);
	}

	/**
	 * @param $sUrl
	 * @param $sSignalSid
	 *
	 * @return bool
	 */
	private function makeCurlRequest($sUrl, $sSignalSid)
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