<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use FintechFab\Models\Users;
use Hash;
use Input;
use Redirect;

class AuthController extends BaseController
{

	public $layout = 'vanguard';

	public function postAuth()
	{
		$data = $this->getOrderFormData();
		dd($data);


		return Redirect::to('vanguard')->with('userMessage', $data);

	}

	public function postRegistration()
	{
		$data = Input::all();
		$user = new Users;

		$password = Hash::make('$data[password]');

		$user->setAttribute('first_name', $data['first_name']);
		$user->setAttribute('last_name', $data['last_name']);
		$user->setAttribute('email', $data['email']);
		$user->setAttribute('password', $password);
		$user->save();

		$userMessage = "Спасибо за регистрацию";
		$title = 'Регистрация прошла успешно';

		return Redirect::to('vanguard')->with('userMessage', $userMessage)->with('title', $title);

	}

	private function getOrderFormData()
	{
		$email = Input::get('email');
		$password = Input::get('password');


		$data = array(
			'email' => $email,
			'password' => $password,
		);

		return $data;

	}
}