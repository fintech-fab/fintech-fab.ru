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
		$termId = Config::get('ff-actions-calc::termId');
		$terminal = Terminal::find($termId);
		if ($terminal == null) {
			return $this->make('registration', array(
				'termId' => $termId,
			));
		}

		return $this->make('account', array(
			'terminal' => $terminal,
		));
	}

	public function about()
	{
		return $this->make('about');
	}

	public function edit()
	{
		$termId = Config::get('ff-actions-calc::termId');
		$terminal = Terminal::find($termId);
		if ($terminal == null) {
			return $this->make('registration', array(
				'termId' => $termId,
			));
		}

		$str = $terminal->id . ':' . $terminal->password;

		return $this->make('edit', array(
			'header' => $str,
		));
	}

	public function postNewTerminal()
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

	public function postChangeData()
	{
		$input = Input::only('username', 'url', 'queue', 'password', 'confirmPassword', 'oldPassword');

		//проверяем данные
		$errors = Validators::getErrorFromChangeData($input);
		if ($errors) {
			return $errors;
		}

		//Проверяем текущий пароль
		$termId = Config::get('ff-actions-calc::termId');
		$terminal = Terminal::find($termId)->first();

		if ($input['oldPassword'] != $terminal->password) {
			$result['errors'] = array(
				'termId'          => '',
				'username'        => '',
				'url'             => '',
				'queue'           => '',
				'password'        => '',
				'confirmPassword' => '',
				'oldPassword'     => 'Неверный пароль',
			);

			return $result;
		}

		//Изменяем данные
		$terminal->changeTerminal($input);
		$message = 'Данные изменены';

		return array('message' => $message);

	}
} 