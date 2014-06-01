<?php
namespace FintechFab\QiwiShop\Controllers;

use App;
use Config;
use FintechFab\QiwiSdk\Curl;
use FintechFab\QiwiSdk\Gateway;
use FintechFab\QiwiShop\Components\Dictionary;
use FintechFab\QiwiShop\Components\Orders;
use FintechFab\QiwiShop\Components\PaysReturn;
use FintechFab\QiwiShop\Components\Validators;
use FintechFab\QiwiShop\Models\Order;
use FintechFab\QiwiShop\Models\PayReturn;
use Input;
use Validator;

class OrderController extends BaseController
{

	public $layout = 'qiwiShop';

	//Передаём настройки в Gateway
	public function __construct()
	{
		$this->beforeFilter(function () {
			$config = array(
				'gateUrl'  => Config::get('ff-qiwi-shop::gateUrl'),
				'provider' => Config::get('ff-qiwi-shop::provider'),
			);
			Gateway::setConfig($config);
		});
	}

	//Определяем метод по $action переданному в url
	public function getAction($action)
	{
		//Получаем заказ по id пользователя и номеру заказа
		$order_id = Input::get('order_id');
		$order = Order::whereUserId(Config::get('ff-qiwi-shop::user_id'))
			->find($order_id);

		if (!$order) {
			return $this->resultMessage('Нет такого заказа');
		}

		// вызываем метод по названию переменной (data-action кнопки на странице)
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
		$orders = Order::whereUserId($user_id)
			->orderBy('id', 'desc')
			->paginate(10);
		$this->make('ordersTable', array('orders' => $orders));
	}

	/**
	 * Страница ошибки авторизации
	 */
	public function authError()
	{
		$this->make('authError');
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
	 * @return array
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
	 * @return array
	 */
	public function showStatus($order)
	{
		$gate = new Gateway($this->makeCurl());
		$isSuccess = $gate->doRequestBillStatus($order->id);

		if ($isSuccess) {
			$order->changeStatus($gate->getValueBillStatus());

			$message = 'Текущий статус счета - '
				. Dictionary::statusRussian($order->status);

			return $this->resultMessage($message, 'Сообщение');

		}

		return $this->resultMessage($gate->getError());
	}

	/**
	 * Выставление счёта
	 *
	 * @param Order $order
	 *
	 * @return array
	 */
	public function createBill($order)
	{
		if (!$order->isNew()) {
			return $this->resultMessage('Статус заказа не "Новый"');
		}

		$gate = new Gateway($this->makeCurl());
		$isSuccess = $gate->createBill(
			$order->id, $order->tel, $order->sum, $order->comment, $order->lifetime
		);

		if (!$isSuccess) {
			return $this->resultMessage($gate->getError());
		}
		$order->changeStatus(Order::C_ORDER_STATUS_PAYABLE);

		if ($order->status == Order::C_ORDER_STATUS_PAYABLE) {
			$message = 'Счёт выставлен';

			return $this->resultMessage($message, 'Сообщение');
		}

		return $this->resultMessage('Счёт не выставлен');
	}

	/**
	 * Отменить счёт.
	 *
	 * @param Order $order
	 *
	 * @return array
	 */
	public function cancelBill($order)
	{
		$gate = new Gateway($this->makeCurl());
		$isSuccess = $gate->cancelBill($order->id);
		if (!$isSuccess) {
			return $this->resultMessage($gate->getError());
		}
		$order->changeStatus(Order::C_ORDER_STATUS_CANCELED);

		if ($order->status == Order::C_ORDER_STATUS_CANCELED) {
			$message = 'Счёт отменён.';

			return $this->resultMessage($message, 'Сообщение');
		}

		return $this->resultMessage('Счёт не отменён');
	}

	/**
	 * Возврат оплаты
	 *
	 * @param Order $order
	 *
	 * @return array
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

		//Возможен ли возврат указанной суммы,
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
		$gate = new Gateway($this->makeCurl());
		$isSuccess = $gate->payReturn($payReturn->order_id, $payReturn->id, $payReturn->sum);

		//Если ошибка, то удаляем наш возврат из таблицы
		if (!$isSuccess) {
			PayReturn::find($payReturn->id)->delete();

			return $this->resultMessage($gate->getError());
		}

		//Меняем статус заказа при успешном возврате
		$order->changeAfterReturn($payReturn->id);
		$message = 'Сумма ' . $data['sum'] .
			' руб. по счёту № ' . $order->id . ' отправлена на возврат';

		return $this->resultMessage($message, 'Сообщение');

	}

	/**
	 * Проверка статуса возврат оплаты
	 *
	 * @param Order $order
	 *
	 * @return array
	 */
	public function statusReturn($order)
	{
		$payReturn = PayReturn::find($order->idLastReturn);
		if (!$payReturn) {
			return $this->resultMessage('Нет такого возврата');
		}

		$gate = new Gateway($this->makeCurl());
		$isSuccess = $gate->doRequestReturnStatus($payReturn->order_id, $payReturn->id);
		if (!$isSuccess) {
			return $this->resultMessage($gate->getError());
		}
		$newReturnStatus = $payReturn->changeStatus($gate->getValuePayReturnStatus());

		$message = 'Текущий статус возврата - '
			. Dictionary::statusRussian($newReturnStatus);

		return $this->resultMessage($message, 'Сообщение');

	}

	/**
	 * @param string $messages
	 * @param string $title
	 *
	 * @return array
	 */
	private function resultMessage($messages, $title = 'Ошибка')
	{
		$result['message'] = $messages;
		$result['title'] = $title;

		return $result;
	}

	/**
	 * @return Curl
	 */
	private function makeCurl()
	{
		return App::make('FintechFab\QiwiSdk\Curl');
	}


}
