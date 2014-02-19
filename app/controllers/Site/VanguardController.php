<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use Mail;

class VanguardController extends BaseController
{

	public $layout = 'vanguard';

	public function index()
	{
		return $this->make('probation');
	}

	public function order()
	{
		$name = $_POST['name'];
		$about = $_POST['about'];
		$email = $_POST['email'];

		$data = array('name' => $name, 'about' => $about, 'email' => $email);

		if (Mail::send('emails.newImprover', $data, function ($message) {
			$message->to('eupathy@gmail.com')->subject('Новая заявка');
		})
		) {
			$feedback = ucwords((string)$name);
			$feedback .= ', cпасибо за регистрацию. Ожидайте ответа по электронной почте.';
		} else {
			$feedback = 'Что сломалось, попробуйте ещё раз';
		}

		return $this->make('test', array('feedback' => $feedback));
	}
}