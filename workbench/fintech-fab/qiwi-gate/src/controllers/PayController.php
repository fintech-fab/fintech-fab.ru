<?php

namespace FintechFab\QiwiGate\Controllers;

use Controller;
use FintechFab\QiwiGate\Models\Bill;
use Input;
use View;

class PayController extends Controller
{
	/**
	 * @return View
	 */
	public function index()
	{
		$data = Input::all();
		$bill = Bill::whereMerchantId($data['shop'])->whereBillId($data['transaction'])->first();
		if (!$bill) {
			$error = 'Такого счёта нет.';

			return View::make('ff-qiwi-gate::paymentMessage', array('message' => $error));
		}
		if (($bill->lifetime != '0000-00-00 00:00:00') && ($bill->lifetime <= date('Y-m-d H:i:s', time()))) {
			$error = 'Счёт просрочен.';

			return View::make('ff-qiwi-gate::paymentMessage', array('message' => $error));
		}
		if ($bill->status == 'waiting') {
			return View::make('ff-qiwi-gate::payment', array('bill' => $bill));
		}
		$error = 'Счёт не может быть оплачен.';

		return View::make('ff-qiwi-gate::paymentMessage', array('message' => $error));
	}

	public function postPay()
	{
		$data = Input::all();
		$bill = Bill::whereUser('tel:' . $data['user'])->whereBillId($data['bill_id'])->first();

		if ($bill) {
			$update = Bill::whereBillId($bill->bill_id)->whereStatus('waiting')->update(array('status' => 'paid'));
			if ($update) {
				$message = 'Счёт успешно оплачен.';

				return View::make('ff-qiwi-gate::paymentMessage', array('message' => $message));
			}
		}
		$error = 'Счёт не оплачен, проверьте статус счёта.';

		return View::make('ff-qiwi-gate::paymentMessage', array('message' => $error));
	}

}