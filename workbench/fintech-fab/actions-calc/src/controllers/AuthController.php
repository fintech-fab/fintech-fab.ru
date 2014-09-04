<?php

namespace FintechFab\ActionsCalc\Controllers;

use Config;
use FintechFab\ActionsCalc\Components\Validators;
use FintechFab\ActionsCalc\Models\Terminal;
use Hash;
use Input;
use Redirect;
use Validator;
use Request;

/**
 * Class AuthController
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class AuthController extends BaseController
{
	protected $layout = 'main';

	/**
	 * Registering new client
	 */
	public function registration()
	{
		$iTerminalId = Config::get('ff-actions-calc::terminal_id');

		// view form on GET
		if (Request::isMethod('GET')) {
			return $this->make('auth.registration', ['terminal_id' => $iTerminalId]);
		}

		// data
		$aRequestData = Input::all();

		// validation
		$validator = Validator::make($aRequestData, Validators::getTerminalValidators());

		if ($validator->fails()) {
			$iTerminalId = Config::get('ff-actions-calc::terminal_id');
			$aRequestData['id'] = $iTerminalId;

			return Redirect::to(route('auth.registration'))->withInput($aRequestData)->withErrors($validator);
		}

		// data valid
		$aRequestData['password'] = Hash::make($aRequestData['password']);
		$aRequestData['key'] = (strlen($aRequestData['key']) < 1) ?
			sha1($aRequestData['name'] . microtime(true) . rand(10000, 90000)) : $aRequestData['key'];

		Terminal::create($aRequestData);

		return Redirect::route('calc.manage');
	}
}