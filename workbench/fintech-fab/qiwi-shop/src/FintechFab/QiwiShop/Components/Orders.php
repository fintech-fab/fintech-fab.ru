<?php

namespace FintechFab\QiwiShop\Components;

use Config;
use FintechFab\QiwiShop\Models\Order;

class Orders
{
	/**
	 * @param $data
	 *
	 * @return Order
	 */
	public static function newOrder($data)
	{
		$day = Config::get('ff-qiwi-shop::lifetime');
		$lifetime = date('Y-m-d H:i:s', time() + 3600 * 24 * $day);

		$order = new Order;
		$order->user_id = Config::get('ff-qiwi-shop::user_id');;
		$order->item = $data['item'];
		$order->sum = $data['sum'];
		$order->tel = $data['tel'];
		$order->comment = $data['comment'];
		$order->lifetime = $lifetime;
		$order->status = Order::C_ORDER_STATUS_NEW;
		$order->save();

		return $order;
	}

}