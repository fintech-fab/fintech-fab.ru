<?php

namespace FintechFab\ActionsCalc\Controllers;

use Controller;
use FintechFab\ActionsCalc\Components\MainHandler;
use FintechFab\ActionsCalc\Models\Terminal;
use Input;
use Log;

class RequestController extends Controller {

	/**
	 * @var int    $input ['term']
	 * @var string $input ['sid']
	 * @var string $input ['data']
	 * @var string $input ['signal']
	 *
	 * @return string JSON $response
	 */
	public function getRequest()
	{
		$input = Input::only('term', 'sid', 'data', 'signal');
		Log::info('Получен http запрос с параметрами:', $input);

		$terminal = Terminal::find($input['term']);
		if ($terminal == null) {
			return $message = 'Auth error';
		}


		$signature = md5('terminal_id=' . $input ['term'] . '|event=' . $input ['sid'] . '|' . $terminal['key']);
		if ($signature = $input['signal']) {

			$mainHandler = new MainHandler();

			return $mainHandler->processRequest($input);
		}

		return $message = 'no signature';
	}
}
