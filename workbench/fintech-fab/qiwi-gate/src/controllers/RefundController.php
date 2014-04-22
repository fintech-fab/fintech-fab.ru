<?php

namespace FintechFab\QiwiGate\Controllers;

use Controller;
use FintechFab\QiwiGate\Components\Refunds;
use FintechFab\QiwiGate\Components\Validators;
use FintechFab\QiwiGate\Models\Bill;
use FintechFab\QiwiGate\Models\Refund;
use Input;
use Response;
use Validator;

class RefundController extends Controller
{

	/**
	 * Возврат средств
	 *
	 * @param int    $provider_id
	 * @param string $bill_id
	 * @param int    $refund_id
	 *
	 * @internal param int $id
	 *
	 * @return Response
	 */
	public function update($provider_id, $bill_id, $refund_id)
	{
		//Проверяем наличие счёта
		$bill = Bill::where('bill_id', '=', $bill_id)->first();
		if ($bill == null) {
			$data['error'] = 210;
			$code_response = 404;

			return $this->responseFromGate($data, $code_response);
		}

		//Проверяем refund_id на уникальность в этом счёте
		$unique_refund = Refund::where('bill_id', '=', $bill_id)
			->where('refund_id', '=', $refund_id)->first();
		if ($unique_refund != null) {
			$data['error'] = 5;
			$code_response = 403;

			return $this->responseFromGate($data, $code_response);
		}

		//Проверяем статус
		if ($bill->status != 'paid') {
			$data['error'] = 'Wrong status';
			$code_response = 403;

			return $this->responseFromGate($data, $code_response);
		}

		//Берём общую сумму счёта
		$amount_bill = $bill->amount;
		//Проверяем запрошенную сумму для отмены
		$amount_query = Input::get('amount');
		$validator = Validator::make(array('amount' => $amount_query), Validators::rulesForRefundBill());
		$messages = $validator->messages()->first();

		if ($messages) {
			if (strpos($messages, '5000')) {
				$data['error'] = 242;
				$code_response = 403;

				return $this->responseFromGate($data, $code_response);
			}
			if (strpos($messages, '10')) {
				$data['error'] = 241;
				$code_response = 403;

				return $this->responseFromGate($data, $code_response);
			}
			$data['error'] = 5;
			$code_response = 400;

			return $this->responseFromGate($data, $code_response);
		}
		//Берём суммы прошлых возвратов
		$refundsBefor = Refund::where('bill_id', '=', $bill_id)->get();
		$amount_refund = 0;
		foreach ($refundsBefor as $one) {
			$amount_refund += $one->amount;
		}
		//Вычисляем возможную сумму возвтрата и определяем возврат
		$rest = $amount_bill - $amount_refund;
		$amount = $amount_query > $rest ? $rest : $amount_query;

		if ($amount <= 0) {
			$data['error'] = 'Unknown error';
			$code_response = 403;

			return $this->responseFromGate($data, $code_response);
		}

		$data = array(
			'bill_id'   => $bill_id,
			'refund_id' => $refund_id,
			'amount'    => $amount,
			'status'    => 'processing',
		);
		$refund = Refunds::NewRefund($data);
		$data['user'] = $refund->bill->user;
		$data['error'] = 0;
		if ($amount_refund + $amount == $amount_bill) {
			$bill->status = 'rejected';
			$bill->save();
		}

		return $this->responseFromGate($data);

	}

	/**
	 * Проверка статуса возврата
	 *
	 * @param int    $provider_id
	 * @param string $bill_id
	 * @param int    $refund_id
	 *
	 * @internal param int $id
	 *
	 * @return Response
	 */
	public function show($provider_id, $bill_id, $refund_id)
	{
		$refund = Refund::where('bill_id', '=', $bill_id)
			->where('refund_id', '=', $refund_id)->first();

		if ($refund == null) {
			$data['error'] = 210;
			$code_response = 404;

			return $this->responseFromGate($data, $code_response);
		}

		$data = $this->dataFromObj($refund);
		$data['error'] = 0;

		return $this->responseFromGate($data);

	}

	/**
	 * @param     $data
	 * @param int $code_response
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */

	private function responseFromGate($data, $code_response = 200)
	{

		$response = array(
			'response' => array(
				'result_code' => $data['error'],
			),
		);
		if (isset($data['status'])) {
			$response['response']['bill'] = $data;
		}

		return Response::json($response, $code_response);
	}

	/**
	 * @param $bill
	 *
	 * @metod toArray
	 */
	private function dataFromObj($bill)
	{
		$data = $bill->toArray();

		foreach ($data as $key => $value) {
			if ($value === null || $key == 'id' || $key == 'merchant_id') {
				unset($bill[$key]);
			}
		}

		return $data;
	}

}