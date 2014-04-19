<?php

namespace FintechFab\QiwiGate\Controllers;

use Controller;
use FintechFab\QiwiGate\Components\ValidateForFields;
use Input;
use Request;
use Response;
use Validator;


class RestController extends Controller
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
		$validator = Validator::make($data, ValidateForFields::rulesForNewBill());
		$userMessages = $validator->messages();


		if ($userMessages->has('amount') || $userMessages->has('user')) {
			$amountError = $userMessages->first('amount');
			$userError = $userMessages->first('user');
			$result['errors'] = array($amountError, $userError);
			dd($result);

			return $result;
		}

		$amount = Input::get('amount');
		$ccy = Input::get('ccy');
		$user = Input::get('user');
		$comment = Input::get('comment');
		$response = array(
			'response' => array(
				'result_code' => 0,
				'bill'        => array(
					'bill_id' => $bill_id,
					'amount'  => $amount,
					'ccy'     => $ccy,
					'status'  => 'waiting',
					'error'   => 0,
					'user'    => $user,
					'comment' => $comment,
				),
			),
		);

		return Response::json($response);

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
		dd('show - проверка статуса ' . $provider_id . ' / ' . $bill_id);
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
			dd('update - отмена счета ' . $provider_id . ' / ' . $bill_id);
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

		$content = file_get_contents('php://input');
		$params = null;
		@parse_str($content, $params);

		return (
			!empty($params['status']) &&
			$params['status'] === 'rejected'
		);

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

}