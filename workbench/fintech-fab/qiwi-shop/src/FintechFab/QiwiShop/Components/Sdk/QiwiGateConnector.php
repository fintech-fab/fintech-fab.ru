<?php
namespace FintechFab\QiwiShop\Components\Sdk;


class QiwiGateConnector
{

	/**
	 * @var Curl
	 */
	private $curl;


	private static $config;

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
		'13'  => 'Сервер занят, повторите запрос позже',
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
	private $billStatus;
	private $payReturnStatus;

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
	 * Установить настройки перед использованием
	 *
*@param $config
	 */
	public static function setConfig($config)
	{
		self::$config = $config;
	}

	/**
	 * Проверка статуса
	 *
	 * @param $orderId
	 *
	 * @return bool
	 */
	public function getBillStatus($orderId)
	{
		$oResponse = $this->curl->request($orderId);
		$this->parseError($oResponse);

		if ($this->getError()) {
			return false;
		}

		$status = $oResponse->response->bill->status;
		$this->setValueBillStatus($this->statusMap[$status]);

		return true;
	}


	/**
	 * Получение счёта
	 *
	 * @param string $orderId
	 * @param string $tel
	 * @param string $sum
	 * @param string $comment
	 * @param string $lifetime
	 *
	 * @return bool
	 */
	public function createBill(
		$orderId, $tel, $sum, $comment = null, $lifetime = null
	)
	{
		$this->checkSum($sum);
		if ($this->getError()) {
			return false;
		}

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
		$this->parseError($oResponse);

		return $this->getError()
			? false
			: true;

	}

	/**
	 * @param $orderId
	 *
	 * @return bool
	 */
	public function cancelBill($orderId)
	{
		$reject = array('status' => 'rejected');
		$oResponse = $this->curl->request($orderId, 'PATCH', $reject);
		$this->parseError($oResponse);

		return $this->getError()
			? false
			: true;

	}

	/**
	 * @param $orderId
	 * @param $payReturnId
	 * @param $sum
	 *
	 * @return bool
	 */
	public function payReturn($orderId, $payReturnId, $sum)
	{

		$this->checkSum($sum);
		if ($this->getError()) {
			return false;
		}

		$amount = array('amount' => $sum);
		$oResponse = $this->curl->request($orderId, 'PUT', $amount, $payReturnId);
		$this->parseError($oResponse);

		return $this->getError()
			? false
			: true;

	}

	/**
	 * @param $orderId
	 * @param $payReturnId
	 *
	 * @return bool
	 */
	public function getPayReturnStatus($orderId, $payReturnId)
	{
		$oResponse = $this->curl->request($orderId, 'GET', null, $payReturnId);
		$this->parseError($oResponse);

		if ($this->getError()) {
			return false;
		}

		$status = $oResponse->response->refund->status;
		$this->setValuePayReturnStatus($this->statusMap[$status]);

		return true;
	}

	private function parseError($oResponse)
	{

		if (!empty($oResponse->curlError)) {
			$this->setError($oResponse->curlError);

			return;
		}

		if (
			$oResponse->response->result_code !== 0 &&
			$oResponse->response->result_code !== '0' &&
			empty($oResponse->response->result_code)
		) {
			$this->setError('Error response format');

			return;
		}

		// код ответа от гейта
		$code = $oResponse->response->result_code;
		if ($code != 0) {
			$this->setError($this->errorMap[$code]);

			return;
		}

	}

	public function getError()
	{
		return $this->errorMessage;
	}

	private function setError($message)
	{
		$this->errorMessage = $message;
	}

	public function getValueBillStatus()
	{
		return $this->billStatus;
	}

	private function setValueBillStatus($status)
	{
		$this->billStatus = $status;
	}

	public function getValuePayReturnStatus()
	{
		return $this->payReturnStatus;
	}

	private function setValuePayReturnStatus($status)
	{
		$this->payReturnStatus = $status;
	}

	private function checkSum($sum)
	{
		if ($sum <= 0) {
			$this->setError($this->errorMap['241']);
		}
	}

} 