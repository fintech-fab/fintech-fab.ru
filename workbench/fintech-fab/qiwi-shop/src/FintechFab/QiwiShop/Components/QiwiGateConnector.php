<?php
namespace FintechFab\QiwiShop\Components;

use Config;
use FintechFab\QiwiShop\Models\Order;
use FintechFab\QiwiShop\Models\PayReturn;

class QiwiGateConnector
{

	private $statusMap = array(
		'waiting'    => Order::C_ORDER_STATUS_PAYABLE,
		'paid'       => Order::C_ORDER_STATUS_PAID,
		'rejected'   => Order::C_ORDER_STATUS_CANCELED,
		'expired'    => Order::C_ORDER_STATUS_EXPIRED,
		'processing' => Order::C_RETURN_STATUS_ON_RETURN,
		'success'    => Order::C_RETURN_STATUS_RETURNED,
	);
	private $errorMap = array(
		'5'   => 'Неверный формат параметров запроса',
		'150' => 'Ошибка авторизации',
		'210' => 'Счет не найден',
		'215' => 'Счет с таким bill_id уже существует',
		'241' => 'Сумма слишком мала',
		'242' => 'Сумма слишком велика',
		'300' => 'Техническая ошибка, повторите запрос позже',
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
		$oResponse = CurlMaker::makeCurl($bill_id);
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
	 * @param        $orderId
	 * @param string $tel
	 * @param string $sum
	 * @param string $comment
	 * @param string $lifetime
	 *
	 * @internal param string $order_id
	 * @internal param $
	 *
	 * @return array
	 */
	public function createBill(
		$orderId, $tel, $sum, $comment = null, $lifetime = null
	)
	{
		$dateExpired = $lifetime = null ? null : date('Y-m-d\TH:i:s', strtotime($lifetime));
		$bill = array(
			'user'     => 'tel:' . $tel,
			'amount'   => $sum,
			'ccy'      => 'RUB',
			'comment'  => $comment,
			'lifetime' => $dateExpired,
			'prv_name' => Config::get('ff-qiwi-shop::provider.name'),
		);
		$oResponse = CurlMaker::makeCurl($orderId, 'PUT', $bill);
		$result_code = $oResponse->response->result_code;
		if ($result_code != 0) {
			return array('error' => $this->errorMap[$result_code]);
		}

		return array('billId' => $oResponse->response->bill->bill_id);
	}

	/**
	 * @param $orderId
	 *
	 * @return array
	 */
	public function cancelBill($orderId)
	{
		$reject = array('status' => 'rejected');
		$oResponse = CurlMaker::makeCurl($orderId, 'PATCH', $reject);
		if ($oResponse->response->result_code != 0) {
			return array('error' => $this->errorMap[$oResponse->response->result_code]);
		}

		return array('billId' => $oResponse->response->bill->bill_id);

	}

	/**
	 * @param $orderId
	 * @param $payReturnId
	 * @param $sum
	 *
	 * @internal param \FintechFab\QiwiShop\Models\PayReturn $refund
	 *
	 * @return array|mixed
	 */
	public function payReturn($orderId, $payReturnId, $sum)
	{
		$amount = array('amount' => $sum);
		$url = CurlMaker::getConfig('gate_url') . CurlMaker::getConfig('provider.id') .
			'/bills/' . $orderId . '/refund/' . $payReturnId;
		$oResponse = CurlMaker::makeCurl($orderId, 'PUT', $amount, $url);
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
	public function checkReturnStatus($refund)
	{
		$url = CurlMaker::getConfig('gate_url') . CurlMaker::getConfig('provider.id') .
			'/bills/' . $refund->order_id . '/refund/' . $refund->id;
		$oResponse = CurlMaker::makeCurl($refund->order_id, 'GET', null, $url);
		if ($oResponse->response->result_code != 0) {
			return array('error' => $this->errorMap[$oResponse->response->result_code]);
		}

		$status = $oResponse->response->refund->status;

		return array('status' => $this->statusMap[$status]);
	}

} 