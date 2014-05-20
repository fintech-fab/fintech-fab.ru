<?php
namespace FintechFab\QiwiShop\Controllers;

use Config;
use FintechFab\QiwiShop\Components\Dictionary;
use FintechFab\QiwiShop\Components\Orders;
use FintechFab\QiwiShop\Components\PaysReturn;
use FintechFab\QiwiShop\Components\Sdk\QiwiGateConnector;
use FintechFab\QiwiShop\Components\Validators;
use FintechFab\QiwiShop\Models\Order;
use FintechFab\QiwiShop\Models\PayReturn;
use Input;
use Validator;

class OrderController extends BaseController
{

	public $layout = 'qiwiShop';

	public function getAction($action)
	{
		//Получаем заказ по id пользователя и номеру заказа
		$order_id = Input::get('order_id');
		$order = Order::whereUserId(Config::get('ff-qiwi-shop::user_id'))->find($order_id);

		if (!$order) {
			return $this->resultMessage('Нет такого заказа');
		}

		return $this->$action($order); // вызываем метод по названию переменной (id кнопки во вьюхе)
	}

	/**
	 * Страница таблицы заказов
	 *
	 * @return void
	 */
	public function ordersTable()
	{
		$user_id = Config::get('ff-qiwi-shop::user_id');
		$orders = Order::whereUserId($user_id)
			->orderBy('id', 'desc')
			->paginate(10);
		$this->make('ordersTable', array('orders' => $orders));
	}

	/**
	 * Страница создания заказа
	 *
	 * @return void
	 */
	public function createOrder()
	{
		$this->make('createOrder');
	}

	/**
	 * Создание счёта.
	 *
	 * @return array $result
	 */
	public function postCreateOrder()
	{
		$data = Input::all();
		//Проверяем данные на валидность
		$validator = Validator::make(
			$data,
			Validators::rulesForNewOrder(),
			Validators::messagesForErrors()
		);
		$userMessages = $validator->messages();

		//Если есть ошибки, возвращаем
		if ($validator->fails()) {
			$result['errors'] = array(
				'item'    => $userMessages->first('item'),
				'sum'     => $userMessages->first('sum'),
				'tel'     => $userMessages->first('tel'),
				'comment' => $userMessages->first('comment'),
			);

			return $result;
		}
		//Предполагаем ошибку
		$result['errors'] = array(
			'common' => 'Ошибка. Повторите ещё раз.',
		);
		//Создаём заказ
		$order = Orders::newOrder($data);

		//Если заказ создан, то вместо ошибки отдаём ОК
		if ($order) {
			$result = array(
				'result'   => 'ok',
				'order_id' => $order->id,
				'message'  => 'Заказ №' . $order->id . ' успешно создан.',
			);
		}

		return $result;
	}

	/**
	 * Проверка статуса счёта.
	 *
	 * @param Order $order
	 *
	 * @return mixed
	 */
	public function showStatus($order)
	{
		$gate = new QiwiGateConnector;
		$response = $gate->checkStatus($order->id);

		if (isset($response['error'])) {
			return $this->resultMessage($response['error']);
		}

		$newStatus = $order->changeStatus($response['status']);

		$message = 'Текущий статус счета - '
			. Dictionary::statusRussian($newStatus);

		return $this->resultMessage($message, 'Сообщение');
	}

	/**
	 * Выставление счёта
	 *
	 * @param Order $order
	 *
	 * @return mixed
	 */
	public function createBill($order)
	{
		if (!$order->isNew()) {
			return $this->resultMessage('Статус заказа не "Новый"');
		}

		$gate = new QiwiGateConnector;
		$response = $gate->createBill(
			$order->id, $order->tel, $order->sum, $order->comment, $order->lifetime
		);

		if (isset($response['error'])) {
			return $this->resultMessage($response['error']);
		}
		$newStatus = $order->changeStatus(Order::C_ORDER_STATUS_PAYABLE);

		if ($newStatus == Order::C_ORDER_STATUS_PAYABLE) {
			$message = 'Счёт № ' . $response['billId'] . ' выставлен';

			return $this->resultMessage($message, 'Сообщение');
		}

		return $this->resultMessage('Счёт не выставлен');
	}

	/**
	 * Отменить счёт.
	 *
	 * @param Order $order
	 *
	 * @return mixed
	 */
	public function cancelBill($order)
	{
		$gate = new QiwiGateConnector;
		$response = $gate->cancelBill($order->id);
		if (isset($response['error'])) {
			return $this->resultMessage($response['error']);
		}
		$newStatus = $order->changeStatus(Order::C_ORDER_STATUS_CANCELED);

		if ($newStatus == Order::C_ORDER_STATUS_CANCELED) {
			$message = 'Счёт № ' . $response['billId'] . ' отменён.';

			return $this->resultMessage($message, 'Сообщение');
		}

		return $this->resultMessage('Счёт не отменён');
	}

	/**
	 * Возврат оплаты
	 *
	 * @param Order $order
	 *
	 * @return mixed
	 */
	public function payReturn($order)
	{
		$data = Input::all();

		//Проверяем данные на валидность и возвращаем если ошибка
		$validator = Validator::make(
			$data,
			Validators::rulesForPayReturn(),
			Validators::messagesForErrors()
		);
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result['error'] = array(
				'sum'     => $userMessages->first('sum'),
				'comment' => $userMessages->first('comment'),
			);

			return $result;
		}

		//Возможен ли возврат указанной суммы
		//учитывая прошлые возвраты по этому счёту
		$isAllowedSum = PaysReturn::isAllowedSum($order, $data['sum']);

		if (!$isAllowedSum) {
			$result['error'] = array(
				'sum' => 'Слишком большая сумма',
			);

			return $result;
		}

		//Если не закончен придыдущий возврат, то не даём сделать новый
		if ($order->isOnReturn()) {
			return $this->resultMessage('Дождитесь окончания предыдущего возврата.');
		}

		//Создаём возврат в таблице и начинаем возврат
		$payReturn = PaysReturn::newPayReturn($data);
		if (!$payReturn) {
			return $this->resultMessage('Возврат не создан, повторите попытку.');
		}
		$gate = new QiwiGateConnector;
		$response = $gate->payReturn($payReturn->order_id, $payReturn->id, $payReturn->sum);

		//Если ошибка, то удаляем наш возврат из таблицы
		if (isset($response['error'])) {
			PayReturn::find($payReturn->id)->delete();

			return $this->resultMessage($response['error']);
		}

		//Меняем статус заказа при успешном возврате
		$order->changeAfterReturn($payReturn->id);
		$message = 'Сумма ' . $response['sum'] .
			' руб. по счёту № ' . $order->id . ' отправлена на возврат';

		return $this->resultMessage($message, 'Сообщение');

	}

	/**
	 * Проверка статуса возврат оплаты
	 *
	 * @param Order $order
	 *
	 * @return mixed|void
	 */
	public function statusReturn($order)
	{
		$payReturn = PayReturn::find($order->idLastReturn);
		if (!$payReturn) {
			return $this->resultMessage('Нет такого возврата');
		}

		$gate = new QiwiGateConnector();
		$response = $gate->checkReturnStatus($payReturn->order_id, $payReturn->id);
		if (isset($response['error'])) {
			return $this->resultMessage($response['error']);
		}
		$newReturnStatus = $payReturn->changeStatus($response['status']);

		$message = 'Текущий статус возврата - '
			. Dictionary::statusRussian($newReturnStatus);

		return $this->resultMessage($message, 'Сообщение');

	}


	private function resultMessage($messages, $title = 'Ошибка')
	{
		$result['message'] = $messages;
		$result['title'] = $title;

		return $result;
	}


}
