<?php
namespace FintechFab\QiwiShop\Components\Sdk;


class QiwiGateConnector
{

	/**
	 * @var Curl
	 */
	private $curl;

	/**
	 * @var string
	 */
	private $errorMessage;
	private $billStatus;
	private $payReturnStatus;

	const C_ERROR_FORMAT = '5';
	const C_ERROR_SERVER_BUSY = '13';
	const C_AUTH_ERROR = '150';
	const C_BILL_NOT_FOUND = '210';
	const C_BILL_ALREADY_EXIST = '215';
	const C_SMALL_AMOUNT = '241';
	const C_BIG_AMOUNT = '242';
	const C_TECHNICAL_ERROR = '242';

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
		self::C_ERROR_FORMAT       => 'Неверный формат параметров запроса',
		self::C_ERROR_SERVER_BUSY  => 'Сервер занят, повторите запрос позже',
		self::C_AUTH_ERROR         => 'Ошибка авторизации',
		self::C_BILL_NOT_FOUND     => 'Счет не найден',
		self::C_BILL_ALREADY_EXIST => 'Счет с таким bill_id уже существует',
		self::C_SMALL_AMOUNT       => 'Сумма слишком мала',
		self::C_BIG_AMOUNT         => 'Сумма слишком велика',
		self::C_TECHNICAL_ERROR    => 'Техническая ошибка, повторите запрос позже',
	);

	public function __construct(Curl $curl)
	{
		$this->curl = $curl;
	}


	/**
	 * Возвращает значение конфига по ключу
	 *
	 * @param $key
	 *
	 * @return string
	 */
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
	 * Установить конфиг перед использованием
	 *
	 * @param $config
	 */
	public static function setConfig($config)
	{
		self::$config = $config;
	}

	/**
	 * Если статус получен - возвращает true
	 * Значение полученного статуса счёта - getValueBillStatus()
	 *
	 * Получает уникальный в магазине id заказа
	 *
	 * @param string $orderId
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
	 * Если счёт создан - возвращает true
	 *
	 * @param string $orderId  - Уникальный в магазине id заказа
	 * @param string $tel      - Номер телефона клиента
	 * @param float  $sum      - Сумма заказа
	 * @param string $comment  - Комментарий к заказу
	 * @param string $lifetime - Срок действия заказа
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
	 * Если счёт отменён - возвращает true
	 *
	 * @param string $orderId - Уникальный в магазине id заказа
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
	 * Если возврат оплаты создан - возвращает true
	 *
	 * @param string $orderId     - Уникальный в магазине id заказа
	 * @param string $payReturnId - Уникальный для заказа id возврата
	 * @param float  $sum         - Сумма возврата
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
	 * Если статус возврата получен - возвращает true
	 * Значение полученного статуса возврата - getValuePayReturnStatus()
	 *
	 * @param string $orderId     - Уникальный в магазине id заказа
	 * @param string $payReturnId - Уникальный для заказа id возврата
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

	/**
	 * Парсинг ошибок
	 * Получить значение ошибки - getError()
	 *
	 * @param $oResponse
	 */
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

	/**
	 * Отдаёт ошибки
	 *
	 * @return string
	 */

	public function getError()
	{
		return $this->errorMessage;
	}

	/**
	 * Устанавливает ошибки
	 *
	 * @param $message
	 */
	private function setError($message)
	{
		$this->errorMessage = $message;
	}

	/**
	 * Отдаёт значение статуса полученного статуса счёта
	 *
	 * @return string
	 */
	public function getValueBillStatus()
	{
		return $this->billStatus;
	}

	/**
	 * Устанавливает полученное значение статуса счёта
	 *
	 * @param string $status
	 */
	private function setValueBillStatus($status)
	{
		$this->billStatus = $status;
	}

	/**
	 * Отдаёт значение статуса полученного статуса возврата
	 *
	 * @return string
	 */
	public function getValuePayReturnStatus()
	{
		return $this->payReturnStatus;
	}

	/**
	 * Устанавливает полученное значение статуса возврата
	 *
	 * @param string $status
	 */
	private function setValuePayReturnStatus($status)
	{
		$this->payReturnStatus = $status;
	}

	/**
	 * Проверяет что сумма > 0
	 *
	 * @param float $sum
	 */
	private function checkSum($sum)
	{
		if ($sum <= 0) {
			$this->setError($this->errorMap[self::C_SMALL_AMOUNT]);
		}
	}

} 