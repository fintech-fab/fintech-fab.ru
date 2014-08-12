<?php

namespace FintechFab\ActionsCalc\Queue;


use FintechFab\ActionsCalc\Components\MainHandler;
use FintechFab\ActionsCalc\Components\Validators;
use Illuminate\Queue\Jobs\Job;
use Log;

class QueueHandler
{
	/**
	 * @param Job   $job
	 * @param array $data
	 */
	public function fire(Job $job, $data)
	{
		Log::info('Получен запрос через очередь с параметрами:', $data);
		Validators::ValidateRequest($data);

		$mainHandler = new MainHandler();
		$mainHandler->processRequest($data);
		$job->delete();
	}

} 