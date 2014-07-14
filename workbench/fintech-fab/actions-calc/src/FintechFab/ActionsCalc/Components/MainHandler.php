<?php

namespace FintechFab\ActionsCalc\Components;


use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\Rule;
use FintechFab\ActionsCalc\Models\Signal;
use Log;
use Validator;

class MainHandler
{

	/**
	 * @param $data
	 */
	public static function processRequest($data)
	{
		$eventData = (array)json_decode($data['data']);

		//Записываем событие в базу
		$event = new Event();
		$event->newEvent($data['term'], $data['sid'], $eventData);

		// Валидация term sid
		$sidTermValidator = Validator::make($data, [
			'term' => 'required|numeric',
			'sid' => 'required|alpha_dash|min:1'
		]);

		// Без term и sid не имеет смысла гнать скрипт
		if ($sidTermValidator->fails()) {
			$aFailMessages = $sidTermValidator->failed();
			$sLogMessage = 'Ошибки валидации: (';
			foreach ($aFailMessages as $param => $err) {
				$sLogMessage .= ($param . ":" . key($err) . ",");
			}
			$sLogMessage .= ')';
			Log::info($sLogMessage);
			exit();
		}

		//Получаем все правила теминала по событию
		$rules = Rule::getRules($data['term'], $data['sid']);
		$countRules = count($rules);
		Log::info("Всего найдено правил: $countRules");

		//Определяем соответсвующие запросу правила
		$rulesHandler = new RulesHandler();
		$fitRules = $rulesHandler->getFitRules($rules, $eventData);
		$countFitRules = count($fitRules);
		if ($countFitRules == 0) {
			Log::info('Соответствующих запросу правил не найдено');
			die;
		}
		Log::info("Найдено подходящих правил: $countFitRules");

		foreach ($fitRules as $fitRule) {
			Log::info("Соответствующее правило: ", $fitRule);
			$signalSid = $fitRule['signal_sid'];

			$signal = new Signal;
			$signal->newSignal($event->id, $signalSid);
			Log::info("Запись в таблицу сигналов: id  = $signal->id");

			//Отправляем результат по http
			$url = $event->terminal->url;
			if ($url != '') {
				SendResults::makeCurl($url, $signalSid);
				$signal->setFlagUrlTrue();
			}

			//Отправляем результат в очередь
			$queue = $event->terminal->queue;
			if ($queue != '') {
				SendResults::sendQueue($queue, $signalSid);
				$signal->setFlagQueueTrue();
			}

		}

	}

} 