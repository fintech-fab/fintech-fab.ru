<?php

namespace App\Controllers\Site;

use FintechFab\Models\vanguardForms;
use Models\User;
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
		$allForms = Input::all();
		$directionList = array();
		foreach ($allForms['direction'] as $directionKey => $value) {
			$directionList[] = Improver::getDirectionName($directionKey);
		}
		$allForms['direction'] = implode(', ', $directionList);

		$data = array();
		foreach ($allForms as $allFormsKey => $value) {
			$data[$allFormsKey] = $value;
		}

		return $data;
	}

}