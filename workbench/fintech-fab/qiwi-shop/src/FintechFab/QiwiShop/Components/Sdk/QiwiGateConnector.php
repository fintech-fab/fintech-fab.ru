<?php
namespace FintechFab\QiwiShop\Components\Sdk;


class QiwiGateConnector
{

	/**
	 * @var Curl
	 */
	private $curl;

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
		'13' => 'Сервер занят, повторите запрос позже',
		'150' => 'Ошибка авторизации',
		'210' => 'Счет не найден',
		'215' => 'Счет с таким bill_id уже существует',
		'241' => 'Сумма слишком мала',
		'242' => 'Сумма слишком велика',
		'300' => 'Техническая ошибка, повторите запрос позже',
	);

	/**
	 * @var string
	 */
	private $errorMessage;

	public function __construct(Curl $curl)
	{
		$this->curl = $curl;
	}


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
		$oResponse = $this->curl->request($bill_id);
		if ($this->parseError($oResponse)) {
			return array('error' => 'Ошибка сервера Qiwi');
		}
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
		$dateExpired = (null == $lifetime)
			? null
			: date('Y-m-d\TH:i:s', strtotime($lifetime));

		$bill = array(
			'user'     => 'tel:' . $tel,
			'amount'   => $sum,
			'ccy'      => 'RUB',
			'comment'  => $comment,
			'lifetime' => $dateExpired,
			'prv_name' => self::getConfig('provider.name'),
		);
		$oResponse = $this->curl->request($orderId, 'PUT', $bill);

		if ($this->parseError($oResponse)) {
			$this->setError('Ошибка сервера Qiwi');

			return false;
		}

		$result_code = $oResponse->response->result_code;

		if ($result_code != 0) {
			$this->setError($this->errorMap[$result_code]);

			return false;
		}

		return true;
	}

	/**
	 * @param $orderId
	 *
	 * @return array
	 */
	public function cancelBill($orderId)
	{
		$reject = array('status' => 'rejected');
		$oResponse = $this->curl->request($orderId, 'PATCH', $reject);
		if ($this->parseError($oResponse)) {
			return array('error' => 'Ошибка сервера Qiwi');
		}
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
		$oResponse = $this->curl->request($orderId, 'PUT', $amount, $payReturnId);
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
		$oResponse = $this->curl->request($orderId, 'GET', null, $payReturnId);
		if ($this->parseError($oResponse)) {
			return array('error' => 'Ошибка сервера Qiwi');
		}
		if ($oResponse->response->result_code != 0) {
			return array('error' => $this->errorMap[$oResponse->response->result_code]);
		}

		$status = $oResponse->response->refund->status;

		return array('status' => $this->statusMap[$status]);
	}

	private function parseError($aResponse)
	{
		return array_key_exists('curlError', $aResponse);
	}

	public function getError()
	{
		return $this->errorMessage;
	}

	private function setError($message)
	{
		$this->errorMessage = $message;
	}
} 