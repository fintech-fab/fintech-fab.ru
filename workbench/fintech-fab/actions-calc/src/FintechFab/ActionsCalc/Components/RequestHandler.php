<?php

namespace FintechFab\ActionsCalc\Components;

use App;
use Log;
use Validator;
use Exception;
use FintechFab\ActionsCalc\Models\Rule;

/**
 * Class RequestHandler
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
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
		} else {
			Log::info('Validation: failed.');
			App::abort(400, 'Validation failed');
		}

		$iFittedRules = $oCalcHandler->getFittedRulesCount();
		Log::info("Fitted rules count: $iFittedRules");

		$aoFittedRules = $oCalcHandler->getFittedRules();
		$this->resultsToQueue($aoFittedRules);

		return ['status' => 'success', 'fittedRulesCount' => $iFittedRules];
	}

	/**
	 * Results to queue.
	 * Request result to queue | From queue - process - to queue(by queue name)
	 *
	 * @param Rule[] $aoFittedRules
	 */
	private function resultsToQueue($aoFittedRules)
	{
		foreach ($aoFittedRules as $oRule) {
			/**
			 * @var ResultHandler $oResultHandler
			 */
			$oResultHandler = App::make(ResultHandler::class);
			$oResultHandler->sendHttpToQueue($oRule->terminal->url, $oRule->signal_id);
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
		// check clien signature
		if (!AuthHandler::checkSign($aRequestData)) {
			Log::info('Request. Wrong signature.');
			App::abort(400, 'Wrong signature');
		}

		// validation terminal_id and event_sid
		$oValidator = Validator::make($aRequestData, Validators::getRequestRules());

		// json validation
		json_decode($aRequestData['data']);
		if (json_last_error() != JSON_ERROR_NONE) {
			Log::info('Request JSON bad data');
			App::abort(400, 'Request JSON bad data');
		}

		return !$oValidator->fails();
	}

}