<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use Auth;
use FintechFab\Components\Helper;
use FintechFab\Models\User;
use Hash;
use Input;
use Redirect;
use Validator;

class AuthController extends BaseController
{
	public $layout = 'vanguard';
	public function postAuth()
	{
		$email = Input::get('email');
		$password = Input::get('password');

		if (Auth::attempt(array('email' => $email, 'password' => $password))) {
			$title = 'Приветствуем ' . Auth::user()->first_name;

			return Redirect::intended('vanguard')->with('userMessage', 'Вы успешно авторизовались')
				->with('title', $title);
		}

		return Redirect::intended('registration')->with('userMessage', 'Такого пользователя нет.')
			->with('title', 'Ошибка');
	}

	public function postRegistration()
	{
		$data = Input::all();
		$validator = Validator::make($data, Helper::rulesForInput(), Helper::messagesForErrors());
		$userMessage = $validator->messages()->first();
		$title = 'Ошибка';

		if ($userMessage != null) {
			return Redirect::to('registration')->with('userMessage', $userMessage)
				->with('title', $title)
				->withInput(Input::except('password'));
		}

		$user = new User;

		$user->first_name = Input::get('first_name');
		$user->last_name = Input::get('last_name');
		$user->email = Input::get('email');
		$user->password = Hash::make(Input::get('password'));
		$user->save();

		Auth::login($user);

		$userMessage = "Спасибо за регистрацию";
		$title = 'Регистрация прошла успешно';

		return Redirect::to('vanguard')->with('userMessage', $userMessage)->with('title', $title);
	}

	public function logout()
	{
		Auth::logout();

		return Redirect::intended('vanguard')->with('userMessage', 'Приходите к нам ещё.')
			->with('title', 'Всего доброго');
	}

}