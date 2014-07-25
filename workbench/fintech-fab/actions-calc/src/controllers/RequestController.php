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
use FintechFab\ActionsCalc\Components\RequestHandler;

class RequestController extends Controller
{
	public function getRequest()
	{
		// Recieving data from post request
		$aRequestData = Input::only('terminal_id', 'event_sid', 'auth_sign', 'data');

		$oRequestHandler = new RequestHandler();
		return $oRequestHandler->process($aRequestData);
	}
}