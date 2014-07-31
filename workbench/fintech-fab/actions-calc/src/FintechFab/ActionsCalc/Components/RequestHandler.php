<?php

namespace FintechFab\ActionsCalc\Components;

use App;
use Log;
use Validator;
use Exception;

/**
 * Class RequestHandler
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @return string JSON
 */
class RequestHandler
{

	/**
	 * All request and response stuff boiling here.
	 *
	 * @param       $aRequestData
	 *
	 * @var integer $aRequestData ['terminal_id']
	 * @var string  $aRequestData ['event_sid']
	 * @var string  $aRequestData ['auth_sign']
	 * @var string  $aRequestData ['data']
	 *
	 * @return string JSON
	 * @throws Exception
	 */
	public function process($aRequestData)
	{
		Log::info('Request-data: ' . json_encode($aRequestData));

		if ($this->validate($aRequestData)) {
			$oCalcHandler = new CalcHandler;
			// incoming data should be solid here, by now
			$oCalcHandler->calculate($aRequestData);
			dd($oCalcHandler->getFittedRules());
		} else {
			App::abort(400, 'Bad request');
		}
	}

	/**
	 * @param $aRequestData
	 *
	 * @return string
	 * @throws Exception
	 */
	private function validate($aRequestData)
	{
		// validation terminal_id and event_sid
		$oValidator = Validator::make($aRequestData, Validators::getRequestRules());

		if ($oValidator->fails()) {
			Log::info('Validators: terminal_id, event_sid failed.');
			App::abort(400, 'Validation failed');
		}

		// check clien signature
		if (!AuthHandler::checkSign($aRequestData)) {
			Log::info('Request. Wrong signature.');
			App::abort(400, 'Wrong signature');
		}

		return true;
	}

}