<?php
namespace FintechFab\QiwiShop\Components;


class QiwiGateConnector
{
	private static $config = array(
		'gate_url' => 'http://fintech-fab.dev/qiwi/gate/api/v2/prv/',
		'provider' => array(
			'name'     => 'Fintech-Fab',
			'id'       => '1',
			'password' => '1234',
		),
	);

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
		'300' => 'Техническая ошибка, повторите запрос позже',
	);

	public static function getConfig($key)
	{
		$keyArray = explode('.', $key);
		$config = self::$config;

		for ($i = 0; $i < count($keyArray); $i++) {
			$config = $config[$keyArray[$i]];
		}

		return $config;

	}

	/**
	 * Проверка статуса
	 *
	 * @param $bill_id
	 *
	 * @return array|mixed
	 */
	public function checkStatus($bill_id)
	{
		$oResponse = Curl::makeCurl($bill_id);
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
			'prv_name' => self::getConfig('provider.name'),
		);
		$oResponse = Curl::makeCurl($orderId, 'PUT', $bill);
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
		$oResponse = Curl::makeCurl($orderId, 'PATCH', $reject);
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
	 * @return array|mixed
	 */
	public function payReturn($orderId, $payReturnId, $sum)
	{
		$amount = array('amount' => $sum);
		$oResponse = Curl::makeCurl($orderId, 'PUT', $amount, $payReturnId);
		if ($oResponse->response->result_code != 0) {
			return array('error' => $this->errorMap[$oResponse->response->result_code]);
		}

		return array('sum' => $oResponse->response->refund->amount);
	}

	/**
	 * @param $orderId
	 * @param $payReturnId
	 *
	 * @return array|mixed
	 */
	public function checkReturnStatus($orderId, $payReturnId)
	{
		$oResponse = Curl::makeCurl($orderId, 'GET', null, $payReturnId);
		if ($oResponse->response->result_code != 0) {
			return array('error' => $this->errorMap[$oResponse->response->result_code]);
		}

		$status = $oResponse->response->refund->status;

		return array('status' => $this->statusMap[$status]);
	}

} 