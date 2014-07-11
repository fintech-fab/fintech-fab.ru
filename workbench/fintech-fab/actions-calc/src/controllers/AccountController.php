<?php

namespace FintechFab\ActionsCalc\Controllers;


use Config;
use FintechFab\ActionsCalc\Components\Validators;
use FintechFab\ActionsCalc\Models\Terminal;
use Input;
use Log;

class AccountController extends BaseController
{

	public $layout = 'account';

	public function account()
	{
		$termId = Config::get('ff-actions-calc::config.termId');
		$terminal = Terminal::find($termId);
		if ($terminal == null) {
			return $this->make('registration', array(
				'termId' => $termId,
			));
		}

		return $this->make('account');
	}

	public function about()
	{
		return $this->make('about');
	}

	public function newTerminal()
	{
		$input = Input::only('termId', 'username', 'url', 'queue', 'password', 'confirmPassword');
		Log::info('На регистрацию нового пользователя получены данные:', $input);

		$errors = Validators::getErrorFromRegData($input);

		if ($errors) {
			Log::info('Найдены ошибки в данных для регистрации:', $errors);

			return $errors;
		}
		$terminal = new Terminal;
		$terminal->newTerminal($input);

		if ($terminal) {
			Log::info('Реистрация успешна. ID = ' . $terminal->id);
			$message = 'Вы успешно зарегистрировались.';

			return array('message' => $message);
		}
		$message = 'Ошибка, попробуйте ещё раз.';

		return array('message' => $message);
	}


} 