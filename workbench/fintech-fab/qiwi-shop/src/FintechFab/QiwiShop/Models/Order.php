<?php

namespace FintechFab\QiwiShop\Models;

use Eloquent;

/**
 * Class Order
 *
 * @package FintechFab\QiwiShop\Models
 *
 * @property integer $user_id
 * @property integer $id
 * @property string  $item
 * @property string  $sum
 * @property string  $tel
 * @property string  $comment
 * @property string  $lifetime
 * @property string  $status
 * @property integer $idLastReturn
 *
 * @method static Order whereUserId()
 * @method static Order whereStatus()
 * @method static Order orWhereStatus()
 * @method static Order whereId()
 * @method static Order find()
 * @method static Order links()
 */
class Order extends Eloquent
{

	const C_ORDER_STATUS_NEW = 'new';
	const C_ORDER_STATUS_PAYABLE = 'payable';
	const C_ORDER_STATUS_PAID = 'paid';
	const C_ORDER_STATUS_RETURNING = 'returning';
	const C_ORDER_STATUS_CANCELED = 'canceled';
	const C_ORDER_STATUS_EXPIRED = 'expired';
	const C_RETURN_STATUS_ON_RETURN = 'onReturn';
	const C_RETURN_STATUS_RETURNED = 'returned';

	protected $fillable = array(
		'user_id', 'item', 'sum', 'tel', 'comment', 'lifetime', 'status', 'idLastReturn'
	);
	protected $table = 'orders';
	protected $connection = 'qiwiShop';

	/**
	 * @return PayReturn
	 */
	public function PayReturn()
	{
		return $this->hasMany('FintechFab\QiwiShop\Models\PayReturn');
	}

	public function changeStatus($newStatus)
	{

		//Не меняем статус с "на возврате" на статус "оплачено"
		if ($this->status == self::C_ORDER_STATUS_RETURNING &&
			$newStatus == self::C_ORDER_STATUS_PAID
		) {

			return $this->status;
		}

		if ($this->status != $newStatus) {
			$isUpdate = Order::whereId($this->id)
				->whereStatus($this->status)
				->update(array('status' => $newStatus));
			if ($isUpdate) {
				return $newStatus;
			}
		}

		return $this->status;
	}
} 