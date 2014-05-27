<?php
namespace FintechFab\QiwiGate\Components;

use FintechFab\QiwiGate\Models\Bill;
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

	/**
	 * Если сумма возврата больше возможного, то сумма возарвта равна возможной
	 *
	 * @param Bill   $bill
	 * @param string $amountQuery
	 *
	 * @return string
	 */
	public static function calculateAmount($bill, $amountQuery)
	{
		//Берём общую сумму счёта
		$amountBill = $bill->amount;
		//Берём суммы прошлых возвратов
		$refundsBefore = Refund::whereBillId($bill->bill_id)->get();
		$amount_refund = 0;
		foreach ($refundsBefore as $one) {
			$amount_refund += $one->amount;
		}
		//Вычисляем возможную сумму возвтрата и отдаём её
		$rest = $amountBill - $amount_refund;
		$amount = ($amountQuery > $rest)
			? $rest
			: $amountQuery;

		return $amount;
	}


} 