<?php

namespace FintechFab\ActionsCalc\Queue;


use FintechFab\ActionsCalc\Models\ResultSignal;
use Illuminate\Queue\Jobs\Job;
use Log;

class SendHttp
{

	/**
	 * @param Job   $job
	 * @param array $data
	 */
	public function fire(Job $job, $data)
	{
		$signalId = $data['signalId'];
		$signalResult = ResultSignal::find($signalId);

		if ($signalResult == null) {
			$job->delete();
			exit();
		}

		$url = $data['url'];
		$signalSid = $signalResult->signal_sid;
		$isSend = $this->makeCurl($url, $signalSid);

		//Если отправлен результат то удаляем задачу и ставим флаг
		if ($isSend) {
			$job->delete();
			$signalResult->setFlagUrlTrue();
			Log::info('Результат отправлен по http.');

		}

		$cnt = $job->attempts();

		if ($cnt > 50) {
			$job->delete();
			Log::info('Выполнено ' . $cnt . ' попыток отправить curl. Задача удалена.', $data);
			exit();
		}

		Log::info('Перевыставлена задача, попытка номер ' . $cnt, $data);
		$job->release(60);
	}

	private function makeCurl($url, $signalSid)
	{
		$postData = array('signalSid' => $signalSid);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

		$httpResponse = curl_exec($ch);
		$httpError = curl_error($ch);

		if (!$httpResponse || $httpError) {
			Log::info("Ошибка CURL. httpResponse = $httpResponse , httpError = $httpError");

			return false;
		}

		Log::info("CURL успешно отработал.  httpResponse = $httpResponse");

		return true;

	}
} 