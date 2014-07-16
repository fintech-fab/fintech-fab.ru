<?php

namespace FintechFab\ActionsCalc\Components;


use App;
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
	public function processRequest($data)
	{
		$eventData = (array)json_decode($data['data']);

		//Записываем событие в базу
		$event = new Event();
		$event->newEvent($data['term'], $data['sid'], $eventData);
		$this->validate($data);


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

	}

	/**
	 * @param $data
	 */
	private function validate($data)
	{
		// Валидация term sid
		$sidTermValidator = Validator::make($data, [
			'term' => 'required|integer',
			'sid'  => 'required|alpha_dash'
		]);

		// Без term и sid не имеет смысла гнать скрипт
		if ($sidTermValidator->fails()) {
			$aFailMessages = $sidTermValidator->failed();
			Log::info('Ошибки валидации: ', $aFailMessages);
			App::abort(500);
			exit();
		}
	}

} 