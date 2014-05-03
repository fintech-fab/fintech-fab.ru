<?php

namespace FintechFab\QiwiShop\Components;

use FintechFab\QiwiShop\Models\PayReturn;

class PaysReturn
{
	/**
	 * @param $data
	 *
	 * @return PayReturn
	 */
	public static function newPayReturn($data)
	{
		$order = new PayReturn;
		$order->order_id = $data['order_id'];
		$order->sum = $data['sum'];
		$order->comment = isset($data['comment']) ? $data['comment'] : '';
		$order->status = 'onReturn';
		$order->save();

		return $order;
	}

} 