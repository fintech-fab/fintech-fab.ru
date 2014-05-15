<?php

namespace FintechFab\QiwiGate\Controllers;

use Controller;
use FintechFab\QiwiGate\Components\Catalog;
use FintechFab\QiwiGate\Components\Refunds;
use FintechFab\QiwiGate\Components\Validators;
use FintechFab\QiwiGate\Models\Bill;
use FintechFab\QiwiGate\Models\Refund;
use Input;
use Response;
use Validator;

class RestRefundController extends Controller
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
		if (!$bill = Bill::isBillExist($bill_id, $provider_id)) {
			$data['error'] = Catalog::C_BILL_NOT_FOUND;

			return $this->responseFromGate($data);
		}

		//Проверяем refund_id на уникальность в этом счёте
		if (Refund::isRefundExist($bill_id, $refund_id)) {
			$data['error'] = Catalog::C_BILL_ALREADY_EXIST;

			return $this->responseFromGate($data);
		}

		//Проверяем статус
		if (!$bill->isPaid()) {
			$data['error'] = Catalog::C_BILL_NOT_FOUND;

			return $this->responseFromGate($data);
		}

		//Проверяем запрошенную сумму для отмены
		$amountQuery = Input::get('amount');
		$validator = Validator::make(
			array('amount' => $amountQuery),
			Validators::rulesForRefundBill()
		);
		if (!$validator->passes()) {
			$data['error'] = Catalog::C_WRONG_FORMAT;

			return $this->responseFromGate($data);
		}

		$refundAmount = Refunds::calculateAmount($bill, $amountQuery);

		$data = array(
			'bill_id'   => $bill_id,
			'refund_id' => $refund_id,
			'amount'    => $refundAmount,
			'status'    => 'processing',
		);
		$refund = Refunds::NewRefund($data);
		$data['user'] = $refund->bill->user;
		$data['error'] = Catalog::C_WITHOUT_ERRORS;

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
		if (!$bill = Bill::isBillExist($bill_id, $provider_id)) {
			$data['error'] = Catalog::C_BILL_NOT_FOUND;

			return $this->responseFromGate($data);
		}

		$refund = Refund::whereBillId($bill_id)
			->whereRefundId($refund_id)->first();

		if ($refund == null) {
			$data['error'] = Catalog::C_BILL_NOT_FOUND;

			return $this->responseFromGate($data);
		}

		//Проводим возврат по прошествии минуты
		if ($refund->isProcessing()) {
			$refund = $refund->doSuccess();
		}

		$data = $this->dataFromObj($refund);
		$data['error'] = 0;

		return $this->responseFromGate($data);

	}

	/**
	 * @param     $data
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */

	private function responseFromGate($data)
	{

		$response = array(
			'response' => array(
				'result_code' => $data['error'],
			),
		);
		if (isset($data['status'])) {
			$response['response']['refund'] = $data;
		}
		$code_response = Catalog::serverCode($data['error']);

		return Response::json($response, $code_response);
	}

	/**
	 * @param Refund $refund
	 *
	 * @return array
	 */
	private function dataFromObj(Refund $refund)
	{
		$data = array();
		$data['refund_id'] = $refund->refund_id;
		$data['amount'] = $refund->amount;
		$data['status'] = $refund->status;
		$data['user'] = $refund->bill->user;

		foreach ($data as $key => $value) {
			if ($value === null) {
				unset($data[$key]);
			}
		}

		return $data;
	}

}