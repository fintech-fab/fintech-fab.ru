<?php
namespace FintechFab\QiwiShop\Controllers;

use Config;
use FintechFab\QiwiShop\Components\Orders;
use FintechFab\QiwiShop\Components\QiwiGateConnect;
use FintechFab\QiwiShop\Components\Validators;
use FintechFab\QiwiShop\Models\Order;
use Input;
use Response;
use Validator;
use View;

class OrderController extends BaseController
{

	public $layout = 'qiwiShop';
	private $statusMap;

	public function __construct()
	{
		$this->statusMap = array(
			'waiting'  => 'payable',
			'paid'     => 'paid',
			'rejected' => 'canceled',
			'expired'  => 'expired',
		);
	}

	public function getAction($action)
	{

		$order_id = Input::get('order_id');
		$order = Order::find($order_id);

		if ($order->user_id != Config::get('ff-qiwi-shop::user_id')) {
			return $this->resultMessage('Нет такого заказа');
		}

		return $this->$action($order);
	}

	/**
	 * Страница таблицы заказов
	 *
	 * @return void
	 */
	public function ordersTable()
	{
		$user_id = Config::get('ff-qiwi-shop::user_id');
		$orders = Order::whereUserId($user_id)->orderBy('id', 'desc')->paginate(10);
		$this->layout->content = View::make('ff-qiwi-shop::qiwiShop.ordersTable', array('orders' => $orders));
	}

	/**
	 * Страница создания заказа
	 *
	 * @return void
	 */
	public function createOrder()
	{
		$this->layout->content = View::make('ff-qiwi-shop::qiwiShop.createOrder');
	}

	/**
	 * Создание счёта.
	 *
	 * @return array $result
	 */
	public function postCreateOrder()
	{
		$data = Input::all();
		$validator = Validator::make($data, Validators::rulesForNewOrder(), Validators::messagesForErrors());
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result['errors'] = array(
				'item'    => $userMessages->first('item'),
				'sum'     => $userMessages->first('sum'),
				'tel'     => $userMessages->first('tel'),
				'comment' => $userMessages->first('comment'),
			);
			return $result;
		}
		$data['user_id'] = Config::get('ff-qiwi-shop::user_id');
		$day = Config::get('ff-qiwi-shop::lifetime');
		$data['lifetime'] = date('Y-m-d H:i:s', time() + 3600 * 24 * $day);
		$order = Orders::newOrder($data);

		if ($order) {
			$result = array(
				'result'   => 'ok',
				'order_id' => $order->id,
				'message'  => 'Заказ №' . $order->id . ' успешно создан.',
			);

			return $result;
		}
		$result['errors'] = array(
			'common' => 'Неизвестная ошибка. Повторите ещё раз.',
		);
		return $result;
	}


	/**
	 * Статус счёта.
	 *
	 * @param Order $order
	 *
	 * @return mixed
	 */
	public function showStatus($order)
	{
		$gate = QiwiGateConnect::Instance();
		$oResponse = $gate->checkStatus($order->id);

		if (array_key_exists('error', $oResponse)) {
			return $this->resultMessage($oResponse['error']);
		}
		$currentOrderStatus = $order->status;
		$newBillStatus = $oResponse->response->bill->status;
		$newOrderStatus = $this->statusMap[$newBillStatus];

		if ($currentOrderStatus != $newOrderStatus) {
			Order::whereId($order->id)->whereStatus($currentOrderStatus)->update(array('status' => $newOrderStatus));
		}
		$message = 'Текущий статус счета - ' . $newOrderStatus;

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
		$gate = QiwiGateConnect::Instance();
		$oResponse = $gate->getBill($order);

		if (array_key_exists('error', $oResponse)) {
			return $this->resultMessage($oResponse['error']);
		}
		$update = Order::whereId($order->id)->whereStatus('new')->update(array('status' => 'payable'));
		if ($update) {
			$message = 'Счёт № ' . $order->id . ' выставлен';

			return $this->resultMessage($message, 'Сообщение');
		}

		return $this->resultMessage('Счёт не выставлен');

	}


	/**
	 * Оплатить счёт.
	 *
	 * @param Order $order
	 *
	 * @return Response
	 */
	public function payBill($order)
	{
		dd($order->id);
	}

	/**
	 * Отменить счёт.
	 *
	 * @param Order $order
	 *
	 * @return Response
	 */
	public function cancelBill($order)
	{
		$gate = QiwiGateConnect::Instance();
		$oResponse = $gate->cancelBill($order);
		if (array_key_exists('error', $oResponse)) {
			return $this->resultMessage($oResponse['error']);
		}
		$update = Order::whereId($order->id)->whereStatus('payable')->update(array('status' => 'canceled'));
		if ($update) {
			$message = 'Счёт № ' . $order->id . ' отменён.';

			return $this->resultMessage($message, 'Сообщение');
		}

		return $this->resultMessage('Счёт не отменён');

	}

	/**
	 * Возврат оплаты
	 *
	 * @param Order $order_id
	 *
	 * @return mixed|void
	 */
	public function payReturn($order_id)
	{


	}

	/**
	 * Возврат оплаты
	 *
	 * @param Order $order
	 *
	 * @return mixed|void
	 */
	public function statusReturn($order)
	{


	}


	private function resultMessage($messages, $title = 'Ошибка')
	{
		$result['message'] = $messages;
		$result['title'] = $title;

		return $result;
	}


}
