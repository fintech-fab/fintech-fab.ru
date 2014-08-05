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

		if ($this->validate($aRequestData)) {
			// registering incoming event
			Registrator::registerEvent($aRequestData);
			// incoming data should be solid here, by now
			$oCalcHandler->calculate($aRequestData);
		} else {
			Log::info('Validation: failed.');
			App::abort(400, 'Validation failed');
		}

		$iFittedRules = $oCalcHandler->getFittedRulesCount();
		Log::info("Fitted rules count: $iFittedRules");

		if ($iFittedRules > 0) {
			$aoFittedRules = $oCalcHandler->getFittedRules();
			// All fitted rules, processing and to queue
			$this->resultsToQueue($aoFittedRules);
		}

		return ['status' => 'success', 'fittedRulesCount' => $iFittedRules];
	}

	/**
	 * Results to queue.
	 * Curl request to queue | Нужно поставить в очередь, постановку в очередь результата, для внешнего слушателя.
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

			$sTerminalUrl = $oRule->terminal->url;
			$sTerminalQueue = $oRule->terminal->queue;

			if ($sTerminalUrl != "") {
				$oResultHandler->sendHttpToQueue($sTerminalUrl, $oRule->signal_id);
			}

			if ($sTerminalQueue != "") {
				$oSignal = Signal::find($oRule->signal_id)->first();
				$aSignalAttributes = $oSignal->getAttributes();
				Registrator::registerSignal($aSignalAttributes, false, true);

				$oResultHandler->resutlToQueue($sTerminalQueue, $oRule->signal->signal_sid);
			}
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