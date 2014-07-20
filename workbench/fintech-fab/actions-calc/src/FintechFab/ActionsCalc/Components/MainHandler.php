<?php

namespace FintechFab\ActionsCalc\Components;


use App;
use FintechFab\ActionsCalc\Models\IncomeEvent;
use FintechFab\ActionsCalc\Models\ResultSignal;
use FintechFab\ActionsCalc\Models\Rule;
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
		$incomeEvent = new IncomeEvent();
		$incomeEvent->newEvent($data['term'], $data['event'], $eventData);


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

			$resultSignal = new ResultSignal;
			$resultSignal->newSignal($incomeEvent->id, $signalSid);
			Log::info("Запись в таблицу сигналов: id  = $resultSignal->id");

			//Отправляем результат по http
			$sendResults = App::make('FintechFab\ActionsCalc\Components\SendResults');
			$url = $incomeEvent->terminal->url;
			if ($url != '') {
				$sendResults->sendHttp($url, $resultSignal->id);
			}

			//Отправляем результат в очередь
			$queue = $incomeEvent->terminal->queue;
			if ($queue != '') {
				$sendResults->sendQueue($queue, $signalSid);
				$resultSignal->setFlagQueueTrue();
			}

		}

		return ['countFitRules' => $countFitRules];
	}

}