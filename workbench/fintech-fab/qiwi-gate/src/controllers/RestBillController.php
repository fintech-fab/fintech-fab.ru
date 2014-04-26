<?php

namespace FintechFab\QiwiGate\Controllers;

use Controller;
use FintechFab\QiwiGate\Components\Bills;
use FintechFab\QiwiGate\Components\Validators;
use FintechFab\QiwiGate\Models\Bill;
use Input;
use Request;
use Response;
use Validator;


class RestBillController extends Controller
{

	/**
	 * создание нового счета
	 *
	 * @param int    $provider_id
	 * @param string $bill_id
	 *
	 * @return Response
	 */
	public function store($provider_id, $bill_id)
	{
		$data = Input::all();
		$data['bill_id'] = $bill_id;
		$validator = Validator::make($data, Validators::rulesForNewBill());

		if (!$validator->passes()) {
			$data['error'] = 5;
			$code_response = 400;

			return $this->responseFromGate($data, $code_response);
		}

		if ($data['amount'] < 10) {
			$data['error'] = 241;
			$code_response = 403;

			return $this->responseFromGate($data, $code_response);
		}

		if ($data['amount'] > 5000) {
			$data['error'] = 242;
			$code_response = 403;

			return $this->responseFromGate($data, $code_response);
		}
		$data['status'] = 'waiting';
		$existBill = Bill::whereBillId($bill_id)->whereMerchantId($provider_id)->first();
		if ($existBill != null) {

			$data['error'] = 215;
			$code_response = 403;

			return $this->responseFromGate($data, $code_response);
		}

		$bill = Bills::NewBill($data);
		if ($bill) {

			$data['error'] = 0;

			return $this->responseFromGate($data);
		}

		$data['error'] = 13;
		$code_response = 500;

		return $this->responseFromGate($data, $code_response);
	}

	/**
	 * Проверка статуса счета
	 *
	 * @param  int    $provider_id
	 * @param  string $bill_id
	 *
	 * @return Response
	 */
	public function show($provider_id, $bill_id)
	{
		$bill = Bill::whereBillId($bill_id)
			->whereMerchantId($provider_id)
			->first();

		if ($bill == null) {
			$data['error'] = 210;
			$code_response = 404;

			return $this->responseFromGate($data, $code_response);
		}

		$data = $this->dataFromObj($bill);
		$data['error'] = 0;

		return $this->responseFromGate($data);
	}

	/**
	 * Выставление счета
	 *
	 * @param  int    $provider_id
	 * @param  string $bill_id
	 *
	 * @return Response
	 */
	public function update($provider_id, $bill_id)
	{

		if ($this->isCreateBill()) {
			return $this->store($provider_id, $bill_id);
		}

		if ($this->isCancelBill()) {

			$bill = Bill::whereBillId($bill_id)
				->whereMerchantId($provider_id)
				->first();
			if ($bill == null) {
				$data['error'] = 210;
				$code_response = 404;

				return $this->responseFromGate($data, $code_response);
			}
			if ($bill['status'] == 'waiting') {
				$bill->status = 'rejected';
				$bill->save();
				$data = $this->dataFromObj($bill);
				$data['error'] = 0;

				return $this->responseFromGate($data);
			}

			$data['error'] = 210;
			$code_response = 403;

			return $this->responseFromGate($data, $code_response);
		}

		return null;

	}

	/**
	 * запрос на отмену счета?
	 *
	 * @return bool
	 */
	private function isCancelBill()
	{

		$method = Request::method();

		if ($method !== 'PATCH') {
			return false;
		}

		$params = Input::all();

		return ($params['status'] === 'rejected');

	}

	/**
	 * запрос на создание счета?
	 *
	 * @return bool
	 */
	private function isCreateBill()
	{

		$method = Request::method();

		return ($method === 'PUT');

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
	 * @param Bill $bill
	 *
	 * @return array
	 */
	private function dataFromObj(Bill $bill)
	{
		$data = array();

		$data['bill_id'] = $bill->bill_id;
		$data['amount'] = $bill->amount;
		$data['ccy'] = $bill->ccy;
		$data['status'] = $bill->status;
		$data['user'] = $bill->user;
		$data['comment'] = $bill->comment;
		$data['prv_name'] = $bill->prv_name;

		foreach ($data as $key => $value) {
			if ($value === null) {
				unset($data[$key]);
			}
		}

		return $data;
	}

}