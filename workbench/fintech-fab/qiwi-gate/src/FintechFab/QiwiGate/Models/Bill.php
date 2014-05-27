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
	const C_STATUS_REJECTED = 'rejected';
	const C_STATUS_EXPIRED = 'expired';


	protected $fillable = array(
		'merchant_id', 'bill_id', 'user', 'amount', 'ccy', 'comment', 'lifetime',
		'pay_source', 'prv_name', 'status'
	);
	protected $table = 'merchants_bills';
	protected $connection = 'qiwiGate';

	/**
	 * Отдаёт счёт по id счёта и id магазина
	 *
	 * @param $bill_id
	 * @param $provider_id
	 *
	 * @return Bill
	 */
	public static function getBill($bill_id, $provider_id)
	{
		$bill = Bill::whereBillId($bill_id)
			->whereMerchantId($provider_id)
			->first();

		return $bill;
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
	 * счет просрочен? Если да то меняем статус счёта
	 *
	 * @return bool
	 */
	public function isExpired()
	{

		if (
			$this->status == 'waiting' &&
			$this->lifetime != null &&
			$this->lifetime != '0000-00-00 00:00:00' &&
			strtotime($this->lifetime) <= time()
		) {
			Bill::whereBillId($this->bill_id)
				->whereStatus(self::C_STATUS_WAITING)
				->update(array('status' => self::C_STATUS_EXPIRED));

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
	 * платеж оплачен?
	 *
	 * @return bool
	 */
	public function isPaid()
	{
		return $this->status == self::C_STATUS_PAID;
	}

	/**
	 * оплатить найденный счёт
	 *
	 * @param $billId
	 *
	 * @return bool
	 */
	public static function doPay($billId)
	{
		$isUpdate = self::whereBillId($billId)
			->whereStatus(self::C_STATUS_WAITING)
			->update(
				array('status' => self::C_STATUS_PAID)
			);
		if ($isUpdate) {
			return true;
		}

		return false;
	}

	/**
	 * Отменяет найденный счёт, и отдаёт отменённвй счёт
	 *
	 * @param $bill_id
	 *
	 * @return Bill
	 */
	public static function doCancel($bill_id)
	{
		Bill::whereBillId($bill_id)
			->whereStatus(self::C_STATUS_WAITING)
			->update(
				array('status' => self::C_STATUS_REJECTED)
			);

		return Bill::whereBillId($bill_id)->first();
	}

}