<?php
namespace FintechFab\QiwiGate\Components;

use FintechFab\QiwiGate\Models\Bill;

class Bills
{

	/**
	 * @param $data
	 *
	 * @return Bill
	 */
	public static function NewBill($data)
	{
		$bill = new Bill;
		$merchantData = Headers::GetMerchant();
		$bill->merchant_id = $merchantData['login'];
		$bill->bill_id = $data['bill_id'];
		$bill->user = $data['user'];
		$bill->amount = $data['amount'];
		$bill->ccy = $data['ccy'];
		$bill->comment = isset($data['comment']) ? $data['comment'] : '';
		$bill->lifetime = isset($data['lifetime']) ? $data['lifetime'] : 0;
		$bill->pay_source = isset($data['pay_source']) ? $data['pay_source'] : 'qw';
		$bill->prv_name = isset($data['prv_name']) ? $data['prv_name'] : '';
		$bill->status = $data['status'];
		$bill->save();

		return $bill;

	}

}