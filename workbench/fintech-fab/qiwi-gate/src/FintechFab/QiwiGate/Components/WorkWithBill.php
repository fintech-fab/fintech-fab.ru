<?php
namespace FintechFab\QiwiGate\Components;

use FintechFab\QiwiGate\Models\Bill;

class WorkWithBill
{

	public static function NewBill($data)
	{
		$bill = new Bill;
		$merchantData = InfoFromHeaders::GetMerchant();
		$bill->merchant_id = $merchantData['login'];
		$bill->bill_id = $data['bill_id'];
		$bill->user = $data['user'];
		$bill->amount = $data['amount'];
		$bill->ccy = $data['ccy'];
		@$bill->comment = $data['comment'];
		@$bill->lifetime = $data['lifetime'];
		if (isset($data['pay_source'])) {
			$bill->pay_source = $data['pay_source'];
		}
		@$bill->prv_name = $data['prv_name'];
		@$bill->status = $data['status'];
		$bill->save();

		return $bill['id'];

	}


} 