<?php

namespace FintechFab\ActionsCalc\Controllers;

use Controller;
use FintechFab\ActionsCalc\Components\MainHandler;
use FintechFab\ActionsCalc\Components\Validators;
use FintechFab\ActionsCalc\Models\Terminal;
use Input;
use Log;

class RequestController extends Controller
{

	/**
	 * @var int    $input ['term']
	 * @var string $input ['sid']
	 * @var string $input ['data']
	 * @var string $input ['sign']
	 *
	 * @return string JSON
	 */
	public function getRequest()
	{
		$input = Input::all();
		Log::info('Получен http запрос с параметрами:', $input);

		Validators::ValidateRequest($input);

		if (!$this->checkSign($input)) {
			return json_encode(['error' => 'Auth error']);
		}

		$mainHandler = new MainHandler();
		$fitRules = $mainHandler->CalcFitRules($input);
		if (isset($fitRules['error'])) {
			return json_encode($fitRules);
		}

		if ($fitRules == null) {
			Log::info('Соответствующих запросу правил не найдено');

			return ['countFitRules' => 0];
		}

		$countFitRules = $mainHandler->processSendResults($fitRules, $input);

		return json_encode($countFitRules);
	}

	/**
	 * @param $input
	 *
	 * @return bool
	 */
	private function checkSign($input)
	{
		$terminal = Terminal::find($input['term']);

		if ($terminal == null) {
			return false;
		}

		if (!isset($input['sign'])) {
			return false;
		}

		$signature = md5('terminal=' . $input ['term'] . '|event=' . $input ['event'] . '|' . $terminal->key);


		return $signature == $input['sign'];
	}
}
