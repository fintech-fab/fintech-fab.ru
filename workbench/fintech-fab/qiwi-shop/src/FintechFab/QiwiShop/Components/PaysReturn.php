<?php

namespace FintechFab\QiwiShop\Components;

use FintechFab\QiwiShop\Models\Order;
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
		$order->status = Order::C_RETURN_STATUS_ON_RETURN;
		$order->save();

		return $order;
	}

	/**
	 * @param $order
	 * @param $sumReturn
	 *
	 * @return bool
	 */
	public static function isAllowedSum($order, $sumReturn)
	{
		$returnsBefore = PayReturn::whereOrderId($order->id)->get();
		$sumOldReturn = 0;
		foreach ($returnsBefore as $one) {
			$sumOldReturn += $one->sum;
		}
		$possibleReturn = $order->sum - $sumOldReturn;

		return $sumReturn <= $possibleReturn;
	}
} 