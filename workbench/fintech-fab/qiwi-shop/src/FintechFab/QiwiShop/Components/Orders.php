<?php

namespace FintechFab\QiwiShop\Components;

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
		$order = new Order;
		$order->user_id = $data['user_id'];
		$order->item = $data['item'];
		$order->sum = $data['sum'];
		$order->tel = $data['tel'];
		$order->comment = $data['comment'];
		$order->lifetime = $data['lifetime'];
		$order->status = 'new';
		$order->save();

		return $order;
	}

} 