<?php
namespace FintechFab\QiwiShop\Controllers;

use Config;
use FintechFab\QiwiShop\Components\Orders;
use FintechFab\QiwiShop\Components\Validators;
use FintechFab\QiwiShop\Widgets\OrdersTable;
use Input;
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

		$ordersTable = OrdersTable::displayTable($user_id);
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
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		//
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
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function update($id)
	{
		//
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

	private function checkAuth($user_id)
	{
		$session_id = Config::get('ff-qiwi-shop::user_id');

		return $session_id == $user_id;

	}


}
