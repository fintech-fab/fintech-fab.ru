<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use Config;
use FintechFab\Components\Helper;
use Illuminate\Mail\Message;
use Input;
use Mail;
use Redirect;

class VanguardController extends BaseController
{

	public $layout = 'vanguard';

	public function vanguard()
	{
		return $this->make('vanguard');
	}

	public function postOrder()
	{
		$data = $this->getOrderFormData();

		Mail::send('emails.newImprover', $data, function (Message $message) {
			$message->to(Config::get('mail.recipient_order_form'))->subject('Новая заявка');
		});

		if (0 == count(Mail::failures())) {

			$userMessage = Helper::ucwords($data['name']);
			$title = 'Все получилось';
			$userMessage .= ',
				вы поразительно инициативны! :-)
				Мы ответим вам не позже следующего рабочего дня.
			';

			Mail::send('emails.replyToNewImprover', $data, function (Message $message) {
				$message->to(Input::get('email'), Input::get('name'))->subject('Ваша заявка принята');
			});

		} else {
			$title = 'Все получилось';
			$userMessage = 'Что-то сломалось, но вы можете попробовать еще раз';
		}


		return Redirect::to('vanguard')
			->with('userMessage', $userMessage)
			->with('userMessageTitle', $title);

	}

	private function getOrderFormData()
	{
		$data = array(
			'name'  => Input::get('name'),
			'about' => Input::get('about'),
			'email' => Input::get('email'),
		);

		return $data;

	}

}