<?php

namespace FintechFab\ActionsCalc\Components;


use App;
use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\IncomeEvent;
use FintechFab\ActionsCalc\Models\ResultSignal;
use FintechFab\ActionsCalc\Models\Rule;
use Log;

class MainHandler
{
	private $incomeEvent;

	/**
	 * @param $data
	 *
	 * @return Rule[]
	 */
	public function CalcFitRules($data)
	{
		$eventData = json_decode($data['data'], true);

		if (json_last_error() != JSON_ERROR_NONE) {
			return ['error' => 'JSON error'];
		}

		//Записываем событие в базу
		$this->incomeEvent = new IncomeEvent();
		$this->incomeEvent->newIncomeEvent($data['term'], $data['event'], $eventData);

		//Если не находим событие - отдаём ошибку
		$event = Event::getEvent($data['term'], $data['event']);

		if ($event == null) {
			Log::info('Правило ' . $data['event'] . ' не найдено');

			return ['error' => 'Unknown event'];
		}

		//Получаем все правила теминала по событию
		$rules = Rule::getRules($data['term'], $event->id);
		$countRules = count($rules);
		Log::info("Всего найдено правил: $countRules");

		//Определяем соответсвующие запросу правила
		$rulesHandler = new RulesHandler();
		$fitRules = $rulesHandler->getFitRules($rules, $eventData);

		return $fitRules;
	}

	/**
	 * @param Rule[] $fitRules
	 * @param array  $data
	 *
	 * @return array
	 */
	public function processSendResults($fitRules, $data)
	{
		$countFitRules = count($fitRules);
		Log::info("Найдено подходящих правил: $countFitRules");

		//Проходим циклом по каждому правилу и отправляем результат
		foreach ($fitRules as $fitRule) {
			Log::info("Соответствующее правило: ", $fitRule->getAttributes());
			$signalSid = $fitRule->signal->signal_sid;

			$resultSignal = new ResultSignal;
			$resultSignal->newResultSignal($this->incomeEvent->id, $signalSid);
			Log::info("Запись в таблицу сигналов: id  = $resultSignal->id");

			//Отправляем результат по http
			$sendResults = App::make(SendResults::class);
			$url = $this->incomeEvent->terminal->url;
			if ($url != '') {
				$sendResults->sendHttp($url, $resultSignal->id, $data);
			}

			//Отправляем результат в очередь
			$queue = $this->incomeEvent->terminal->queue;
			if ($queue != '') {
				$sendResults->sendQueue($queue, $signalSid, $data);
				$resultSignal->setFlagQueueTrue();
			}

		}

		return ['countFitRules' => $countFitRules];
	}

}