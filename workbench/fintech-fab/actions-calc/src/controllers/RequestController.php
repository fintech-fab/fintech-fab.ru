<?php

namespace FintechFab\ActionsCalc\Controllers;

use App;
use Controller;
use FintechFab\ActionsCalc\Components\MainHandler;
use FintechFab\ActionsCalc\Components\Validators;
use FintechFab\ActionsCalc\Models\Terminal;
use Input;
use Log;
use Validator;

class RequestController extends Controller {

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
		$input = Input::only('term', 'sid', 'data', 'sign');
		Log::info('Получен http запрос с параметрами:', $input);

		$this->ValidateInput($input);

		if (!$this->checkSign($input)) {
			return json_encode(['error' => 'Auth error']);
		}

		$mainHandler = new MainHandler();

		return json_encode($mainHandler->processRequest($input));
	}

	/**
	 * @param $input
	 *
	 * @return bool
	 */
	private function checkSign($input)
	{
		$terminal = Terminal::find($input['term']);

		$signature = md5('terminal=' . $input ['term'] . '|event=' . $input ['sid'] . '|' . $terminal->key);


		return $signature == $input['sign'];
	}

	/**
	 * @param $input
	 */
	private function ValidateInput($input)
	{
		$sidTermValidator = Validator::make($input, Validators::rulesForRequest());

		if ($sidTermValidator->fails()) {
			$aFailMessages = $sidTermValidator->failed();
			Log::info('Ошибки валидации: ', $aFailMessages);
			App::abort(500);
			exit();
		}
	}
}
