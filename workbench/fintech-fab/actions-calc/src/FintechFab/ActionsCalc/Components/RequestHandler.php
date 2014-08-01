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
	 * @return array
	 * @throws Exception
	 */
	public function process($aRequestData)
	{
		Log::info('Request-data: ' . json_encode($aRequestData));

		$oCalcHandler = new CalcHandler;

		if ($this->validate($aRequestData)) {
			// incoming data should be solid here, by now
			$oCalcHandler->calculate($aRequestData);
			dd($oCalcHandler->getFittedRules());
		} else {
			App::abort(400, 'Bad request');
		}

		$iFittedRules = $oCalcHandler->getFittedRulesCount();

		Log::info("Fitted rules count: $iFittedRules");

		return ['status' => 'success', 'fittedRulesCount' => $iFittedRules];
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

		json_decode($aRequestData['data']);
		if (json_last_error() != JSON_ERROR_NONE) {
			Log::info('Request JSON bad data');
			App::abort(400, 'Request JSON bad data');
		}

		return true;
	}

}