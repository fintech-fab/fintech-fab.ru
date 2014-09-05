<?php

namespace FintechFab\ActionsCalc\Components;

use App;
use FintechFab\ActionsCalc\Models\Signal;
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
		// incoming request data
		Log::info('Request-data: ' . json_encode($aRequestData));
		$oCalcHandler = new CalcHandler;

		// request data validation
		if ($this->validate($aRequestData)) {
			// registering incoming event
			Registrator::registerEvent($aRequestData);
			// incoming data should be solid here, by now
			$oCalcHandler->calculate($aRequestData);
		} else {
			Log::info('Validation: failed.');
			App::abort(400, 'Validation failed');
		}

		// counting fitted rules
		$iFittedRules = $oCalcHandler->getFittedRulesCount();
		Log::info("Fitted rules count: $iFittedRules");

		// more than one rule fits, to queue
		if ($iFittedRules > 0) {
			$aoFittedRules = $oCalcHandler->getFittedRules();
			// All fitted rules, processing and to queue
			$this->resultsToQueue($aoFittedRules);
		}

		return ['status' => 'success', 'fittedRulesCount' => $iFittedRules];
	}

	/**
	 * Results to queue.
	 * 1. Curl response to queue.
	 * 2. Result to queue.
	 * 3. Listening connection(with results) and sending result to foreign queue.
	 *
	 * @param Rule[] $aoFittedRules
	 */
	private function resultsToQueue($aoFittedRules)
	{
		foreach ($aoFittedRules as $oRule) {
			/** @var ResultHandler $oResultHandler */
			$oResultHandler = App::make(ResultHandler::class);

			$sTerminalUrl = $oRule->terminal->url;
			$sTerminalQueue = $oRule->terminal->foreign_queue;
			$sForeignJob = $oRule->terminal->foreign_job;

			if ($sTerminalUrl != "") {
				$oResultHandler->sendHttpToQueue($sTerminalUrl, $oRule->signal_id);
			}

			if ($sTerminalQueue != "") {
				// registering of sending signal to queue
				$oSignal = Signal::find($oRule->signal_id)->first();
				$aSignalAttributes = $oSignal->getAttributes();

				// reqistering signal
				$sResultHash = $oResultHandler->getResultHash();
				Registrator::registerSignal($aSignalAttributes, false, true, $sResultHash);

				// result to queue to put it into external queue
				$oResultHandler->resultToQueue($sForeignJob, $sTerminalQueue, $oRule->signal->signal_sid);
			}
		}
	}

	/**
	 * Validate request data.
	 *
	 * @param $aRequestData
	 *
	 * @return string
	 * @throws Exception
	 */
	private function validate($aRequestData)
	{
		// check client signature
		if (!AuthHandler::checkSign($aRequestData)) {
			Log::info('Request. Wrong signature.');
			App::abort(401, 'Wrong signature. Unauthorized.');
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