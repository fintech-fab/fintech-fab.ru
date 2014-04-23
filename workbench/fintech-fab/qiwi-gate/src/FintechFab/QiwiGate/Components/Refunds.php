<?php
namespace FintechFab\QiwiGate\Components;

use FintechFab\QiwiGate\Models\Refund;


class Refunds
{

	/**
	 * @param $data
	 *
	 * @return Refund
	 */
	public static function NewRefund($data)
	{
		$refunds = new Refund;
		$refunds->bill_id = $data['bill_id'];
		$refunds->refund_id = $data['refund_id'];
		$refunds->amount = $data['amount'];
		$refunds->status = $data['status'];
		$refunds->save();

		return $refunds;

	}


} 