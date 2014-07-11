<?php

namespace FintechFab\ActionsCalc\Controllers;

use Controller;
use FintechFab\ActionsCalc\Components\MainHandler;
use Input;
use Log;

class RequestController extends Controller {

	public function getRequest(){
		$input = Input::only('term', 'sid', 'data', 'signal');
		Log::info('Получен http запрос с параметрами:', $input);

		MainHandler::processRequest($input);

	}

} 