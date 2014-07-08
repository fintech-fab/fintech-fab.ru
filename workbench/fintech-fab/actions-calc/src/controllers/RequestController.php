<?php

namespace FintechFab\ActionsCalc\Controllers;

use Controller;
use FintechFab\ActionsCalc\Components\Events;
use FintechFab\ActionsCalc\Components\RulesHandler;
use FintechFab\ActionsCalc\Components\SenderOfResults;
use FintechFab\ActionsCalc\Components\Signals;
use FintechFab\ActionsCalc\Models\Rule;
use Input;
use Log;

class RequestController extends Controller {

	public function getRequest(){
		$input = Input::only('term', 'sid', 'data', 'signal');
		Log::info('Получен запрос с параметрами:', $input);

		$data = (array)json_decode($input['data']);

		//Записываем событие в базу
		$event = Events::newEvent($input['term'], $input['sid'], $data);

		//Получаем все правила теминала по событию
		$rules = Rule::getRules($input['term'], $input['sid']);
		$countRules = count($rules);
		Log::info("Всего найдено правил: $countRules");


		//Определяем соответсвующие запросу правила
		$rulesHandler = new RulesHandler();
		$fitRules = $rulesHandler->getFitRules($rules, $data);
		$countFitRules = count($fitRules);
		if ($countFitRules == 0) {
			Log::info('Соответствующих запросу правил не найдено');
			die;
		}
		Log::info("Найдено подходящих правил: $countFitRules");

		foreach ($fitRules as $fitRule) {
			Log::info("Соответствующее правило: ", $fitRule);
			$signalSid = $fitRule['signal_sid'];

			$signal = Signals::newSignal($event->id, $signalSid);
			Log::info("Запись в таблицу сигналов: id  = $signal->id");

			//Отправляем результат
			$url = $event->terminal->url;
			if ($url != '') {
				SenderOfResults::makeCurl($url, $signal->signal_sid);
				$signal->setFlagUrlTrue();
			}
			$queue = $event->terminal->queue;
			if ($queue != '') {
				SenderOfResults::sendQueue($queue, $signal->signal_sid);
				$signal->setFlagQueueTrue();
			}
		}

	}

} 