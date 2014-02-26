<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use Config;
use FintechFab\Components\Helper;
use Illuminate\Mail\Message;
use Input;
use Mail;
use Redirect;
use Session;

class VanguardController extends BaseController
{

	public $layout = 'vanguard';

	public function index()
	{
		$userMessage = Session::get('userMessage');

		return $this->make('probation', array('userMessage' => $userMessage));
	}

	public function order()
	{
		$data = $this->getOrderFormData();

		Mail::send('emails.newImprover', $data, function (Message $message) {
			$message->to(Config::get('mail.recipient_order_form'))->subject('Новая заявка');
		});

		if (0 == count(Mail::failures())) {
			$feedback = Helper::ucwords($data['name']);
			$feedback .= ', cпасибо за регистрацию. Ожидайте ответа по электронной почте.';
		} else {
			$feedback = 'Что-то сломалось, попробуйте ещё раз';
		}

		return Redirect::to('vanguard')->with('userMessage', $feedback);

	}

	private function getOrderFormData()
	{
		$name = Input::get('name');
		$about = Input::get('about');
		$email = Input::get('email');

		$data = array(
			'name'  => $name,
			'about' => $about,
			'email' => $email,
		);

		return $data;

	}
}