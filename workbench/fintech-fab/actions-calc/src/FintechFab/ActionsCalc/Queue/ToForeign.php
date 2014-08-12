<?php

namespace FintechFab\ActionsCalc\Queue;

use Queue;
use Log;
use Illuminate\Queue\Jobs\Job;

/**
 * Class ToForeign
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class ToForeign
{

	/**
	 * @param Job   $job
	 * @param array $aData
	 */
	public function fire(Job $job, $aData)
	{

		$bIsConnected = Queue::connected($aData['sForeignQueue']);

		if ($bIsConnected) {
			Queue::connection($aData['sForeignQueue'])->push($aData['sForeignJob'], [
				'sSignalSid'  => $aData['sSignalSid'],
				'sResultHash' => $aData['sResultHash'],
			]);
			Log::info('Job ToForeign', $aData);
			$job->delete();
			exit();
		} else {
			Log::info('Job release. Attempts: ' . $job->attempts(), $aData);
			$job->release(60);
		}

		// job failed?
		if ($job->attempts() > 50) {
			$job->delete();
			Log::info('Queue attempts exceeded.', $aData);
			exit();
		}
	}

}