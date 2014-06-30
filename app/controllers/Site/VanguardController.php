<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use FintechFab\Components\Form\Vanguard\Improver;
use FintechFab\Components\Helper;
use Input;
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

	/**
	 * @return array
	 */
	private function getOrderFormData()
	{
		$directions = Input::get('direction');
		$directionList = array();
		foreach ($directions as $directionKey => $value) {
			$directionList[] = Improver::getDirectionName($directionKey);
		}
		$directionText = implode(', ', $directionList);

		$data = array(
			'name'      => Input::get('name'),
			'direction' => $directionText,
			'works' => Input::get('projects'),
			'time'      => Input::get('time'),
			'visit'     => Input::get('visit'),
			'about'     => Input::get('about'),
			'email'     => Input::get('email'),
		);

		return $data;

	}

}