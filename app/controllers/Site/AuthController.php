<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use FintechFab\Components\Helper;
use FintechFab\Models\Users;
use Hash;
use Input;
use Redirect;
use Validator;

class AuthController extends BaseController
{

	public $layout = 'vanguard';

	public function postAuth()
	{
		$data = Input::all();
		dd($data);


		return Redirect::to('vanguard')->with('userMessage', $data);

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

		$password = Hash::make('$data[password]');

		$user = new Users;
		$user->setAttribute('first_name', $data['first_name']);
		$user->setAttribute('last_name', $data['last_name']);
		$user->setAttribute('email', $data['email']);
		$user->setAttribute('password', $password);
		$user->save();

		$userMessage = "Спасибо за регистрацию";
		$title = 'Регистрация прошла успешно';

		return Redirect::to('vanguard')->with('userMessage', $userMessage)->with('title', $title);

	}


}