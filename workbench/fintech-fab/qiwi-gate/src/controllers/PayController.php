<?php

namespace FintechFab\QiwiGate\Controllers;

use FintechFab\QiwiGate\Components\Validators;
use FintechFab\QiwiGate\Models\Bill;
use Input;
use Validator;
use View;

class PayController extends BaseController
{
	public $layout = 'payment';

	/**
	 * @return View
	 */
	public function index()
	{
		$data = Input::all();
		$bill = Bill::whereMerchantId($data['shop'])->whereBillId($data['transaction'])->first();

		if (!$bill) {
			$error = 'Такого счёта нет.';

			return $this->make('error', array('message' => $error));
		}
		if (($bill->lifetime != '0000-00-00 00:00:00') && ($bill->lifetime <= date('Y-m-d H:i:s', time()))) {
			$error = 'Счёт просрочен.';

			return $this->make('error', array('message' => $error));
		}
		if ($bill->status == 'waiting') {
			return $this->make('index', array('bill' => $bill));
		}
		$error = 'Счёт не может быть оплачен.';

		return $this->make('error', array('message' => $error));
	}

	public function postPay()
	{
		$data = Input::all();
		$validator = Validator::make($data, Validators::rulesForPayment(), Validators::messagesForErrors());
		$userMessages = $validator->messages()->first();

		if ($validator->fails()) {
			return array('error' => $userMessages);
		}
		$bill = Bill::whereUser('tel:' . $data['user'])->whereBillId($data['transaction'])->first();
		if (!$bill) {
			$error = 'Счёт выставлен на другой номер';

			return array('error' => $error);
		}
		$update = Bill::whereBillId($bill->bill_id)->whereStatus('waiting')->update(array('status' => 'paid'));
		if ($update) {
			$result['message'] = 'Счёт успешно оплачен.';

			return $result;
		}

		$error = 'Счёт не оплачен, проверьте статус счёта.';

		return array('error' => $error);
	}

}