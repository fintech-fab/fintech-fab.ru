<?php

namespace FintechFab\ActionsCalc\Queue;


use Illuminate\Queue\Jobs\Job;

class QueueHandler
{
	public function fire(Job $job, array $data)
	{
		$job->delete();
		dd($data);
	}

} 