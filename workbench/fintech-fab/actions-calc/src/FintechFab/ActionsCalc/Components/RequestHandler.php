<?php

namespace FintechFab\ActionsCalc\Components;

use App;
use Log;
use Validator;

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
	 * @param       $aRequestData
	 *
	 * @var integer $aRequestData ['terminal_id']
	 * @var string  $aRequestData ['event_sid']
	 * @var string  $aRequestData ['auth_sign']
	 * @var string  $aRequestData ['data']
	 *
	 * @return string
	 */
	public function process($aRequestData)
	{
//		Log::info('Request-data: ' . json_encode($aRequestData));

		// terminal_id and event_sid validation
		$oValidator = Validator::make($aRequestData, Validators::getRequestRules());

		if ($oValidator->fails()) {
			Log::info('Validators: terminal_id, event_sid failed.');
			return json_encode(['status' => 'error', 'message' => 'Validation failed.']);
		}

		// Check clien signature
		if (!AuthHandler::checkSign($aRequestData)) {
			App::abort(400, 'Wrong signature');
		}
	}

}