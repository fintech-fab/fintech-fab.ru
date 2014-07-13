<?php

namespace FintechFab\ActionsCalc\Controllers;

use Controller;
use FintechFab\ActionsCalc\Components\Events;
use FintechFab\ActionsCalc\Components\RulesHandler;
use FintechFab\ActionsCalc\Components\SendResults;
use FintechFab\ActionsCalc\Components\Signals;
use FintechFab\ActionsCalc\Models\Rule;
use Input;
use Log;
use Validator;

class RequestController extends Controller {

	/**
	 * @var int $input['term']
	 * @var string $input['sid']
	 * @var string $input['data']
	 * @var string $input['signal']
	 */
	public function getRequest(){
		$input = Input::only('term', 'sid', 'data', 'signal');

		// Валидация term sid
		$sidTermValidator = Validator::make($input, [
			'term' => 'required|numeric',
			'sid' => 'required|alpha|min:1'
		]);

		// Без term и sid не имеет смысла гнать скрипт
		if($sidTermValidator->fails()) {
			$aFailMessages = $sidTermValidator->failed();
			$sLogMessage = 'Ошибки валидации: (';
			foreach($aFailMessages as $param => $err) {
				$sLogMessage .= ($param . ":" . key($err) . ",");
			}
			$sLogMessage .= ')';
			Log::info($sLogMessage);
			exit();
		}

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