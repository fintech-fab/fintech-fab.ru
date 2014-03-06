<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
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

	private function getOrderFormData()
	{
		$name = Input::get('name');
		$password = Input::get('password');


		$data = array(
			'name'     => $name,
			'password' => $password,
		);

		return $data;

	}
}