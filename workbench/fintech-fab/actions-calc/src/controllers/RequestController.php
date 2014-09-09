<?php
/**
 * Class RequestController
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

namespace FintechFab\ActionsCalc\Controllers;

use App;
use Input;
use FintechFab\ActionsCalc\Components\RequestHandler;
use Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RequestController extends BaseController
{
	/**
	 * Actions calculator main entry point
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getRequest()
	{
		// Recieving data from post request
		$aRequestData = Input::only('terminal_id', 'event_sid', 'auth_sign', 'data');

		$oRequestHandler = new RequestHandler();

		try {
			$responseData = $oRequestHandler->process($aRequestData);

			return Response::json($responseData);
		} catch (HttpException $e) {
			return Response::json([
				'status'  => 'error',
				'message' => $e->getStatusCode() . ' ' . $e->getMessage()
			], $e->getStatusCode());
		}
	}
}