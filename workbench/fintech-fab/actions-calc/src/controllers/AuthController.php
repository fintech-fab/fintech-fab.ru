<?php

namespace FintechFab\ActionsCalc\Controllers;

use Config;
use FintechFab\ActionsCalc\Components\AuthHandler;
use FintechFab\ActionsCalc\Components\Validators;
use FintechFab\ActionsCalc\Models\Terminal;
use Hash;
use Input;
use Redirect;
use Session;
use Validator;
use Request;
use View;

/**
 * Class AuthController
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class AuthController extends BaseController
{
	/**
	 * @var string
	 */
	protected $layout = 'main';

	/**
	 * Registering new client terminal.
	 *
	 * @return \Illuminate\Http\RedirectResponse|View
	 */
	public function registration()
	{
		if (AuthHandler::isClientRegistered()) {
			return Redirect::route('calc.manage');
		}

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

	/**
	 * Updating terminal profile information.
	 *
	 * @return $this|array|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function profile() // TODO: Too fat. To several methods.
	{
		$iTerminalId = Config::get('ff-actions-calc::terminal_id');
		$aRequestData = Input::all();
		/** @var Terminal $oTerminal */
		$oTerminal = Terminal::find($iTerminalId);

		// on GET only opening, and fill in
		if (Request::isMethod('GET')) {
			return View::make('ff-actions-calc::auth.profile', ['terminal' => $oTerminal]);
		}

		$validator = Validator::make($aRequestData, Validators::getProfileValidators());

		// validation fails
		if ($validator->fails()) {
			$oErrors = $validator->errors();

			return Redirect::to(route('auth.profile'))->withInput($aRequestData)->withErrors($oErrors);
		}

		// password change
		if (isset($aRequestData['change_password']) && $aRequestData['change_password'] == 1) {

			$validator = Validator::make($aRequestData, Validators::getProfileChangePassValidators());

			if ($validator->fails()) {

				$oErrors = $validator->errors();

				return Redirect::to(route('auth.profile'))->withInput($aRequestData)->withErrors($oErrors);
			}

			// current password check
			if (Hash::check($aRequestData['current_password'], $oTerminal->password) === false) {
				$oErrors = $validator->errors();
				$oErrors->add('current_password', 'Текущий пароль и введённый, не совпадают.');

				return Redirect::to(route('auth.profile'))->withInput($aRequestData)->withErrors($oErrors);
			}

			// valid and saving
			$aRequestData['password'] = Hash::make(trim($aRequestData['password']));
			$oTerminal->password = $aRequestData['password'];
		} else {
			unset($aRequestData['password']);
		}

		$oTerminal->fill($aRequestData);

		if ($oTerminal->save()) {
			Session::flash('auth.profile.success', 'Данные успешно обновлены.');

			return Redirect::to(route('auth.profile'))->withInput($aRequestData);
		}

		return Redirect::to(route('auth.profile'))->withInput($aRequestData);
	}

}