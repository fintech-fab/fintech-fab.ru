<?php
namespace FintechFab\QiwiShop\Controllers;

use Config;
use FintechFab\QiwiShop\Components\Orders;
use FintechFab\QiwiShop\Components\Validators;
use FintechFab\QiwiShop\Models\Order;
use FintechFab\QiwiShop\Widgets\MakeTable;
use Input;
use Request;
use Response;
use Validator;
use View;

class RestOrderController extends BaseController
{

	public $layout = 'qiwiShop';

	/**
	 * @param $user_id
	 *
	 * @return \Illuminate\Http\Response|void
	 */
	public function index($user_id)
	{
		if (!$this->checkAuth($user_id)) {
			return Response::view('ff-qiwi-shop::errors.401', array(), 401);
		}

		$ordersTable = MakeTable::displayTable($user_id);
		$this->layout->content = View::make('ff-qiwi-shop::qiwiShop.ordersTable', array('ordersTable' => $ordersTable));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @param $user_id
	 *
	 * @return void|Response
	 */
	public function create($user_id)
	{
		if (!$this->checkAuth($user_id)) {
			return Response::view('ff-qiwi-shop::errors.401', array(), 401);
		}

		$this->layout->content = View::make('ff-qiwi-shop::qiwiShop.createOrder');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param $user_id
	 *
	 * @return array $result
	 */
	public function store($user_id)
	{
		if (!$this->checkAuth($user_id)) {
			$result['errors'] = array(
				'common' => 'Ошибка авторизации',
			);

			return $result;
		}

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
		$data['lifetime'] = date('Y-m-d\TH:i:s', time() + 3600 * 24 * $day);

		$order = Orders::newOrder($data);

		if ($order) {
			$response = array(
				'result'   => 'ok',
				'order_id' => $order->id,
				'message'  => 'Заказ №' . $order->id . ' успешно создан.',
			);
			return $response;
		}
		$result['errors'] = array(
			'common' => 'Неизвестная ошибка. Повторите ещё раз.',
		);
		return $result;
	}


	/**
	 * Статус счёта.
	 *
	 * @param $user_id
	 * @param $order_id
	 *
	 * @internal param int $id
	 *
	 * @return Response
	 */
	public function show($user_id, $order_id)
	{
		if (!$this->checkAuth($user_id)) {
			return $this->errorResponse('150');
		}

		$response = $this->makeCurl($order_id);


		if ($response->response->result_code != 0) {
			return $this->errorResponse($response->response->result_code);
		}

		$currentOrderStatus = Order::find($order_id)->status;
		$newBillStatus = $response->response->bill->status;
		$newOrderStatus = Config::get('ff-qiwi-shop::statusMapping.' . $newBillStatus);

		if ($currentOrderStatus != $newOrderStatus) {
			Order::whereId($order_id)->whereStatus($currentOrderStatus)->update(array('status' => $newOrderStatus));
			$result['change'] = true;
		}
		$result['message'] = 'Текущий статус счета - ' . $newOrderStatus;
		$result['title'] = 'Сообщение';

		return $result;

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Возврат оплаты
	 *
	 * @param $user_id
	 * @param $order_id
	 *
	 * @return Response
	 */
	public function update($user_id, $order_id)
	{
		if (!$this->checkAuth($user_id)) {
			return $this->errorResponse('150');
		}

		if ($this->isCreateBill()) {
			return $this->CreateBill($order_id);
		}

	}

	/**
	 * Выставление счёта
	 *
	 * @param $order_id
	 *
	 * @return mixed
	 */
	private function CreateBill($order_id)
	{
		$order = Order::find($order_id);

		$bill = array(
			'user'     => 'tel:' . $order->tel,
			'amount'   => $order->sum,
			'ccy'      => 'RUB',
			'comment'  => $order->comment,
			'lifetime' => $order->lifetime,
			'prv_name' => Config::get('ff-qiwi-shop::prv_name'),
		);

		$response = $this->makeCurl($order_id, 'PUT', $bill);
		if ($response->response->result_code != 0) {
			return $this->errorResponse($response->response->result_code);
		}
		$update = Order::whereId($order->id)->whereStatus('new')->update(array('status' => 'payable'));
		if ($update) {
			$result['message'] = 'Счёт № ' . $order->id . ' выставлен';
			$result['title'] = 'Сообщение';
			$result['change'] = true;

			return $result;
		}


	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	/**
	 * @param $user_id
	 *
	 * @return bool
	 */
	private function checkAuth($user_id)
	{
		$session_id = Config::get('ff-qiwi-shop::user_id');
		return $session_id == $user_id;
	}

	/**
	 * @param int        $order_id
	 * @param string     $method
	 * @param null|array $query
	 *
	 * @return mixed
	 */

	private function makeCurl($order_id, $method = 'GET', $query = null)
	{
		$url = Config::get('ff-qiwi-shop::qiwiGateUrl') . Config::get('ff-qiwi-shop::bill_id') . '/bills/' . $order_id;

		$headers = array(
			"Accept: text/json",
			"Content-Type: application/x-www-form-urlencoded; charset=utf-8",
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, Config::get('ff-qiwi-shop::Authorization'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		if ($query != null) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
		}

		$httpResponse = curl_exec($ch);
		if (!$httpResponse) {

			$res = curl_error($ch) . '(' . curl_errno($ch) . ')';
			dd($res);
		}

		$httpResponse = json_decode($httpResponse);

		return $httpResponse;
	}

	private function isCreateBill()
	{
		$method = Request::method();

		return ($method === 'PUT');
	}

	private function errorResponse($result_code)
	{
		$result['message'] = Config::get('ff-qiwi-shop::gateErrors.' . $result_code);
		$result['title'] = 'Ошибка';

		return $result;
	}


}
