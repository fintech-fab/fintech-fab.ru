<?php

namespace App\Controllers\Site;


use App\Controllers\BaseController;
use FintechFab\Components\Form\Vanguard\Improver;
use FintechFab\Components\Helper;
use Input;
use Redirect;
use FintechFab\Components\MailSender;
use FintechFab\Components\Form\Vanguard\FormHelper;

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

			foreach ($formData['direction'] as $directionKey => $value) {
				$directionList[] = Improver::getDirectionName($directionKey);
			}
		}

		$formData['direction'] = implode(', ', $directionList);
		$result = array_diff_assoc($information['improver'], $formData);
		foreach ($result as $data) {
			$data = '';
			$formData[] = $data;
		}

		return $formData;
	}

}