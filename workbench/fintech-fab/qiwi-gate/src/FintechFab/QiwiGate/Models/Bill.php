<?php

namespace FintechFab\QiwiGate\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $merchant_id
 * @property integer $bill_id
 * @property string  $user
 * @property string  $amount
 * @property string  $ccy
 * @property string  $comment
 * @property string  $lifetime
 * @property string  $pay_source*
 * @property string  $prv_name
 * @property string  $status
 * @property string  $created_at
 * @property string  $updated_at
 *
 * @method static Bill find()
 * @method static Bill where()
 * @method static Bill first()
 * @method static Bill whereBillId($id)
 * @method static Bill whereMerchantId($id)
 * @method static Bill whereStatus($status)
 * @method static Bill whereUser($user)
 * @method static Bill links()
 */
class Bill extends Eloquent
{

	const C_STATUS_WAITING = 'waiting';
	const C_STATUS_PAID = 'paid';


	protected $fillable = array(
		'merchant_id', 'bill_id', 'user', 'amount', 'ccy', 'comment', 'lifetime',
		'pay_source', 'prv_name', 'status'
	);
	protected $table = 'merchants_bills';
	protected $connection = 'qiwiGate';

	/**
	 * @param integer $shopId
	 * @param string  $billId
	 *
	 * @return Bill
	 */
	public static function getByShopAndBill($shopId, $billId)
	{
		return Bill::whereMerchantId($shopId)->whereBillId($billId)->first();
	}

	/**
	 * @return Merchant
	 */
	public function merchant()
	{
		return $this->belongsTo('\FintechFab\QiwiGate\Models\Merchant');
	}

	/**
	 * @return Refund
	 */
	public function refunds()
	{
		return $this->hasMany('FintechFab\QiwiGate\Models\Refund');
	}


	/**
	 * счет просрочен?
	 *
	 * @return bool
	 */
	public function isExpired()
	{

		if (
			$this->status == 'waiting' &&
			$this->lifetime != '0000-00-00 00:00:00' &&
			strtotime($this->lifetime) <= time()
		) {
			return true;
		}

		return false;

	}


	/**
	 * платеж в ожидании?
	 *
	 * @return bool
	 */
	public function isWaiting()
	{
		return $this->status == self::C_STATUS_WAITING;
	}

	/**
	 * оплатить найденный счёт
	 *
	 * @param $billId
	 *
	 * @return mixed
	 */
	public static function doPay($billId)
	{
		return self::whereBillId($billId)
			->whereStatus(self::C_STATUS_WAITING)
			->update(
				array('status' => self::C_STATUS_PAID)
			);
	}

}