<?php

namespace FintechFab\ActionsCalc\Queue;

use Log;
use Illuminate\Queue\Jobs\Job;

/**
 * Class ToForeign
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class ToForeign
{

	private $_foreignJob;

	/**
	 * @param Job   $job
	 * @param array $aData
	 */
	public function fire(Job $job, $aData)
	{

	}
}