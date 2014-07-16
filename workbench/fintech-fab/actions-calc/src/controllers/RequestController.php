<?php

namespace FintechFab\ActionsCalc\Controllers;

use Controller;
use FintechFab\ActionsCalc\Components\MainHandler;
use Input;
use Log;

class RequestController extends Controller {

	/**
	 * @var int $input['term']
	 * @var string $input['sid']
	 * @var string $input['data']
	 * @var string $input['signal']
	 */
	public function getRequest(){
		$input = Input::only('term', 'sid', 'data', 'signal');
		Log::info('Получен http запрос с параметрами:', $input);

		$mainHandler = new MainHandler();
		$mainHandler->processRequest($input);

	}

} 