<?php

namespace FintechFab\QiwiGate\Controllers;

use Controller;
use FintechFab\QiwiGate\Components\Bills;
use FintechFab\QiwiGate\Components\Catalog;
use FintechFab\QiwiGate\Components\Validators;
use FintechFab\QiwiGate\Models\Bill;
use Input;
use Request;
use Response;
use Validator;


class RestBillController extends Controller
{

	/**
	 * Создание нового счета
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

		//Если не пройдена валидация - отдаём ошибку формата
		if (!$validator->passes()) {
			$data['error'] = Catalog::C_WRONG_FORMAT;

			return $this->responseFromGate($data);
		}

		//Если сумма мала - отдаём ошибку маленькой суммы
		if ($data['amount'] < 10) {
			$data['error'] = Catalog::C_SMALL_AMOUNT;

			return $this->responseFromGate($data);
		}

		//Если сумма велика - отдаём ошибку большой суммы
		if ($data['amount'] > 15000) {
			$data['error'] = Catalog::C_BIG_AMOUNT;

			return $this->responseFromGate($data);
		}

		//Отдаём ошибку если счёт уже существует
		if (Bill::getBill($bill_id, $provider_id)) {
			$data = array('error' => Catalog::C_BILL_ALREADY_EXIST);

			return $this->responseFromGate($data);
		}

		//Создаём счёт и возвращаем его
		$bill = Bills::NewBill($data);
		if ($bill) {
			$data = $this->dataFromObj($bill);
			$data['error'] = 0;

			return $this->responseFromGate($data);
		}

		//Если не создан счёт, то отдаём техническую ошибку
		$data = array('error' => Catalog::C_TECHNICAL_ERROR);

		return $this->responseFromGate($data);
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
		//Находим счёт
		$bill = Bill::getBill($bill_id, $provider_id);

		//Отдаём ошибку если счёт не найден
		if ($bill == null) {
			$data['error'] = Catalog::C_BILL_NOT_FOUND;

			return $this->responseFromGate($data);
		}

		//Если счёт стал просроченным - обновляем счёт (т.к. изменился статус)
		if ($bill->isExpired()) {
			$bill = Bill::find($bill->id);
		}

		//Фотмируем ответ и возвращаем
		$data = $this->dataFromObj($bill);
		$data['error'] = 0;

		return $this->responseFromGate($data);
	}

	/**
	 * Отмена счёта (или отправить на создание счёта)
	 *
	 * @param  int    $provider_id
	 * @param  string $bill_id
	 *
	 * @return Response
	 */
	public function update($provider_id, $bill_id)
	{
		//Если это создание - счёта отправляем на создание
		if ($this->isCreateBill()) {
			return $this->store($provider_id, $bill_id);
		}

		if ($this->isCancelBill()) {
			//Находим счёт
			$bill = Bill::getBill($bill_id, $provider_id);
			//Если не нашли - ошибка "Счёт не найден"
			if ($bill == null) {
				$data['error'] = Catalog::C_BILL_NOT_FOUND;

				return $this->responseFromGate($data);
			}
			//Если статус waiting - отменяем счёт и отдаём его в ответе
			if ($bill->isWaiting()) {
				$canceledBill = $bill->doCancel($bill_id);
				$data = $this->dataFromObj($canceledBill);
				$data['error'] = 0;

				return $this->responseFromGate($data);
			}

			$data['error'] = Catalog::C_BILL_NOT_FOUND;

			return $this->responseFromGate($data);
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
	 * Формирует ответ от сервера
	 *
	 * @param  $data
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
		//Если код ошибки 0 - добавляем к ответу данные счёта
		if ($data['error'] == Catalog::C_WITHOUT_ERRORS) {
			$response['response']['bill'] = $data;
		}
		$code_response = Catalog::serverCode($data['error']);

		return Response::json($response, $code_response);
	}

	/**
	 * Формирует массив ответа из объекта счёта
	 *
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
		$data['lifetime'] = $bill->lifetime;
		$data['pay_source'] = $bill->pay_source;
		$data['prv_name'] = $bill->prv_name;

		foreach ($data as $key => $value) {
			if ($value === null) {
				unset($data[$key]);
			}
		}

		return $data;
	}

}