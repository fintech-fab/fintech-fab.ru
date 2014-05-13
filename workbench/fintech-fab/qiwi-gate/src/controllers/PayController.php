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

		$billId = Input::get('transaction');
		$shopId = Input::get('shop');
		$bill = Bill::getByShopAndBill($shopId, $billId);

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

		$shopId = Input::get('shop');
		$billId = Input::get('transaction');
		$bill = Bill::getByShopAndBill($shopId, $billId);

		$error = 'Ошибка оплаты, проверьте статус.';

		if ($bill) {

			$isUpdate = Bill::doPay($bill->bill_id);

			if ($isUpdate) {

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