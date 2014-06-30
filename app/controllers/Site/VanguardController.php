<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use Config;
use FintechFab\Components\Helper;
use Illuminate\Mail\Message;
use Input;
use Mail;
use Redirect;
use FintechFab\Components\MailSender;

class VanguardController extends BaseController
{

	public $layout = 'vanguard';

	public function vanguard()
	{
		return $this->make('vanguard');
	}

	public function postOrder()
	{
		$mailSender = new MailSender();
		$data = $this->getOrderFormData();

		if ($mailSender->doVanguardOrder($data)) {
			$mailSender->doVanguardOrderAuthor(array(
				'to' => $data['email'],
				'name' => $data['name']
			));

			$title = 'Все получилось';
			$userMessage = Helper::ucwords($data['name']);
			$userMessage .= ',
				вы поразительно инициативны! :-)
				Мы ответим вам не позже следующего рабочего дня.
			';

		} else {
			$title = 'Отправка заявки';
			$userMessage = 'Что-то сломалось, но вы можете попробовать еще раз';
		}


		return Redirect::to('vanguard')
			->with('userMessage', $userMessage)
			->with('userMessageTitle', $title);

	}

	private function getOrderFormData()
	{
		$res = false;
		$direction = Input::get('direction');
		foreach ($direction as $quan) {
			$res = $res . '  ' . $quan;
		}

		$data = array(
			'name'      => Input::get('name'),
			'direction' => $res,
			'works'     => Input::get('works'),
			'time'      => Input::get('time'),
			'visit'     => Input::get('visit'),
			'about'     => Input::get('about'),
			'email'     => Input::get('email'),
		);

		return $data;

	}

}