<?php

namespace FintechFab\ActionsCalc\Controllers;

use Controller;
use FintechFab\ActionsCalc\Components\MainHandler;
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

		MainHandler::processRequest($input);

	}

} 