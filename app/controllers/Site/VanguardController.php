<?php

namespace App\Controllers\Site;


use App\Controllers\BaseController;
use FintechFab\Components\Form\Vanguard\FormHelper;
use FintechFab\Components\Form\Vanguard\Improver;
use FintechFab\Components\Helper;
use FintechFab\Components\MailSender;
use Input;
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
		$information = FormHelper::getInformation();
		$formData = Input::all();
		$directionList = array();

		if (isset($formData['direction'])) {
			if (is_array($formData['direction'])) {

				foreach ($formData['direction'] as $directionKey => $value) {
					$directionList[] = Improver::getDirectionName($directionKey);
				}
				$formData['direction'] = implode(', ', $directionList);
			}
		} else {
			$formData['direction'] = '';
		}

		$difference = array_diff_key($information['improver'], $formData);

		foreach ($difference as $key => $value) {
			$formData[$key] = '';
		}

		return $formData;
	}

}