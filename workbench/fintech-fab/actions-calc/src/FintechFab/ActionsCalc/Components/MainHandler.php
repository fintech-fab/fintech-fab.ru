<?php

namespace FintechFab\ActionsCalc\Components;


use App;
use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\Rule;
use FintechFab\ActionsCalc\Models\Signal;
use Log;

class MainHandler
{

	/**
	 * @param $data
	 *
	 * @return array
	 */
	public function processRequest($data)
	{
		$eventData = (array)json_decode($data['data']);

		//Записываем событие в базу
		$event = new Event();
		$event->newEvent($data['term'], $data['event'], $eventData);


		//Получаем все правила теминала по событию
		$rules = Rule::getRules($data['term'], $data['event']);
		$countRules = count($rules);
		Log::info("Всего найдено правил: $countRules");

		//Определяем соответсвующие запросу правила
		$rulesHandler = new RulesHandler();
		$fitRules = $rulesHandler->getFitRules($rules, $eventData);
		$countFitRules = count($fitRules);
		if ($countFitRules == 0) {
			Log::info('Соответствующих запросу правил не найдено');

			return ['countFitRules' => $countFitRules];
		}
		Log::info("Найдено подходящих правил: $countFitRules");

		//Проходим циклом по каждому правилу и отправляем результат
		foreach ($fitRules as $fitRule) {
			Log::info("Соответствующее правило: ", $fitRule->getAttributes());
			$signalSid = $fitRule['signal_sid'];

			$signal = new Signal;
			$signal->newSignal($event->id, $signalSid);
			Log::info("Запись в таблицу сигналов: id  = $signal->id");

			//Отправляем результат по http
			$sendResults = App::make('FintechFab\ActionsCalc\Components\SendResults');
			$url = $event->terminal->url;
			if ($url != '') {
				$sendResults->makeCurl($url, $signalSid);
				$signal->setFlagUrlTrue();
			}

			//Отправляем результат в очередь
			$queue = $event->terminal->queue;
			if ($queue != '') {
				$sendResults->sendQueue($queue, $signalSid);
				$signal->setFlagQueueTrue();
			}

		}

		return ['countFitRules' => $countFitRules];
	}

}