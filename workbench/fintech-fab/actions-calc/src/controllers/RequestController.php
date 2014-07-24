<?php
/**
 * Class RequestController
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

namespace FintechFab\ActionsCalc\Controllers;

use Controller;
use App;
use Input;
use Log;
use FintechFab\ActionsCalc\Components\AuthHandler;
use Config;

class RequestController extends Controller
{
	public function getRequest()
	{
		$aRequestData = Input::only('terminal_id', 'event_sid', 'auth_sign', 'data');

		if (!AuthHandler::checkSign($aRequestData)) {
			App::abort(400, 'Wrong signature');
		}

		// authenticate user
		$oRequestTestData = json_decode($aRequestData['data']);

		Log::info("Request in: " . json_encode($aRequestData));
		$oRequestTestData->test = 2;

		return json_encode(['data' => $oRequestTestData]);
	}
}