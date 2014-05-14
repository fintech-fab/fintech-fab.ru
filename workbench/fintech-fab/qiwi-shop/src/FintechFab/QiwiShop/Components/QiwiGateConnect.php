<?php

namespace FintechFab\QiwiShop\Components;


use Config;
use FintechFab\QiwiShop\Models\Order;
use FintechFab\QiwiShop\Models\PayReturn;

class QiwiGateConnect
{

	private $statusMap = array(
		'waiting'    => 'payable',
		'paid'       => 'paid',
		'rejected'   => 'canceled',
		'expired'    => 'expired',
		'processing' => 'onReturn',
		'success'    => 'returned',
	);
	private $errorMap = array(
		'5'   => 'Неверный формат параметров запроса',
		'150' => 'Ошибка авторизации',
		'210' => 'Счет не найден',
		'215' => 'Счет с таким bill_id уже существует',
		'241' => 'Сумма слишком мала',
		'242' => 'Сумма слишком велика',
	);

	/**
	 * Проверка статуса
	 *
	 * @param $bill_id
	 *
	 * @return array|mixed
	 */
	public function checkStatus($bill_id)
	{
		$oResponse = $this->makeCurl($bill_id);
		$result_code = $oResponse->response->result_code;
		if ($result_code != 0) {
			return array('error' => $this->errorMap[$result_code]);
		}

		$status = $oResponse->response->bill->status;

		return array('status' => $this->statusMap[$status]);
	}


	/**
	 * Получение счёта
	 *
	 * @param Order $order
	 *
	 * @return array
	 */
	public function getBill($order)
	{
		$bill = array(
			'user'     => 'tel:' . $order->tel,
			'amount'   => $order->sum,
			'ccy'      => 'RUB',
			'comment'  => $order->comment,
			'lifetime' => date('Y-m-d\TH:i:s', strtotime($order->lifetime)),
			'prv_name' => Config::get('ff-qiwi-shop::provider.name'),
		);
		$oResponse = $this->makeCurl($order->id, 'PUT', $bill);
		$result_code = $oResponse->response->result_code;
		if ($result_code != 0) {
			return array('error' => $this->errorMap[$result_code]);
		}

		return array('billId' => $oResponse->response->bill->bill_id);
	}

	/**
	 * @param Order $order
	 *
	 * @return array
	 */
	public function cancelBill($order)
	{
		$reject = array('status' => 'rejected');
		$oResponse = $this->makeCurl($order->id, 'PATCH', $reject);
		if ($oResponse->response->result_code != 0) {
			return array('error' => $this->errorMap[$oResponse->response->result_code]);
		}

		return array('billId' => $oResponse->response->bill->bill_id);

	}

	/**
	 * @param PayReturn $refund
	 *
	 * @return array|mixed
	 */
	public function payReturn($refund)
	{
		$amount = array('amount' => $refund->sum);
		$url = Config::get('ff-qiwi-shop::gate_url') . Config::get('ff-qiwi-shop::provider.id') .
			'/bills/' . $refund->order_id . '/refund/' . $refund->id;
		$oResponse = $this->makeCurl($refund->order_id, 'PUT', $amount, $url);
		if ($oResponse->response->result_code != 0) {
			return array('error' => $this->errorMap[$oResponse->response->result_code]);
		}

		return array('sum' => $oResponse->response->refund->amount);
	}

	/**
	 * @param PayReturn $refund
	 *
	 * @return array|mixed
	 */
	public function checkRefundStatus($refund)
	{
		$url = Config::get('ff-qiwi-shop::gate_url') . Config::get('ff-qiwi-shop::provider.id') .
			'/bills/' . $refund->order_id . '/refund/' . $refund->id;
		$oResponse = $this->makeCurl($refund->order_id, 'GET', null, $url);
		if ($oResponse->response->result_code != 0) {
			return array('error' => $this->errorMap[$oResponse->response->result_code]);
		}

		$status = $oResponse->response->refund->status;

		return array('status' => $this->statusMap[$status]);
	}

	/**
	 * @param int    $order_id
	 * @param string $method
	 * @param null   $query
	 * @param null   $url
	 *
	 * @return mixed
	 */

	private function makeCurl($order_id, $method = 'GET', $query = null, $url = null)
	{
		if ($url == null) {
			$url = Config::get('ff-qiwi-shop::gate_url') . Config::get('ff-qiwi-shop::provider.id') .
				'/bills/' . $order_id;
		}

		$headers = array(
			"Accept: text/json",
			"Content-Type: application/x-www-form-urlencoded; charset=utf-8",
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt(
			$ch,
			CURLOPT_USERPWD,
			Config::get('ff-qiwi-shop::provider.id') . ':' . Config::get('ff-qiwi-shop::provider.password')
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		if ($query != null) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
		}

		$httpResponse = curl_exec($ch);
		$httpError = curl_error($ch);
		$info = curl_getinfo($ch);
		$response = @json_decode($httpResponse);

		if (!$response || !$httpResponse || $httpError) {

			$aResponse = array(
				'url'      => $url,
				'code'     => $info['http_code'],
				'error'    => $httpError,
				'response' => $httpResponse,
			);

			echo json_encode($aResponse);
			die();
		}


		return $response;
	}

} 