<?php

namespace FintechFab\QiwiGate\Controllers;

use FintechFab\QiwiGate\Models\Bill;
use Input;
use View;

class PayController extends BaseController
{
	public $layout = 'payment';

	/**
	 * @return View
	 */
	public function index()
	{

		$bill_id = Input::get('transaction');
		$provider_id = Input::get('shop');
		$bill = Bill::getBill($bill_id, $provider_id);

		if ($bill && $bill->isWaiting()) {
			return $this->make('index', array('bill' => $bill));
		}

		$error = 'Счёт не может быть оплачен.';

		if (!$bill) {
			$error = 'Счет не найден.';
		}

		if ($bill->isExpired()) {
			$error = 'Счёт просрочен.';
		}

		return $this->make('error', array('message' => $error));

	}

	public function postPay()
	{

		$bill_id = Input::get('transaction');
		$provider_id = Input::get('shop');
		$bill = Bill::getBill($bill_id, $provider_id);

		$error = 'Ошибка оплаты, проверьте статус.';

		if ($bill) {

			if (Bill::doPay($bill->bill_id)) {

				return array(
					'message' => 'Счёт успешно оплачен.',
				);
			}

		} else {
			$error = 'Счет не найден.';
		}

		return array(
			'error' => $error,
		);

	}

}